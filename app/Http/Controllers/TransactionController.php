<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class TransactionController extends Controller
{
    public function index()
    {

        return view('transaction.index');
    }

    public function dataTable()
    {
        try {
            $transactions = Transaction::all();

            return DataTables::of($transactions)
                ->addColumn('action', function ($transaction) {
                    return '<a href="' . route('transaction.show', $transaction->id) . '" class="btn btn-sm btn-info">View Details</a>';
                })
                ->toJson();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        $products = Product::all();
        return view('transaction.create', compact('products'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'customer_name' => 'required',
            'date' => 'required|date',
            'products_data.*.product_id' => 'required|exists:products,id',
            'products_data.*.quantity' => 'required|integer|min:1',
        ]);


        $totalPrice = 0;
        $totalQuantity = 0;

        $transaction = Transaction::create([
            'customer_name' => $request->input('customer_name'),
            'date' => $request->input('date'),
            'total_price' => 0,
            'total_quantity' => 0,
            'transaction_number' => $this->generateTransactionNumber(),
        ]);

        $productData = [];
        $totalQuantity = 0;
        $products_data = json_decode($request->input('products_data'));

        foreach ($products_data as $product) {
            $productData[] = [
                'transaction_id' => $transaction->id,
                'product_id' => $product->product_id,
                'quantity' => $product->quantity,
                'total_price' => $product->total_price,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $totalQuantity += $product->quantity;
        }

        TransactionDetail::insert($productData);

        foreach ($productData as &$product) {
            $productDetail = Product::find($product['product_id']);
            $product['total_price'] = $productDetail->price * $product['quantity'];
            $totalPrice += $product['total_price'];
        }


        $transaction->update([
            'total_price' => $totalPrice,
            'total_quantity' => $totalQuantity,
        ]);

        return redirect()->route('transaction.index')->with('success', 'Transaction created successfully!');
    }


    private function generateTransactionNumber()
    {
        $timestamp = now()->format('YmdHis');
        $uniqueId = uniqid();

        return 'TRX' . $timestamp . $uniqueId;
    }

    public function show($id)
    {
        try {
            $transaction = Transaction::with(['details.product'])->findOrFail($id);
            return view('transaction.show', compact('transaction'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->view('errors.404', [], 404);
        }
    }

    public function editProduct(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $detail = TransactionDetail::find($id);

            if (!$detail) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            $oldQuantity = $detail->quantity;
            $newQuantity = $request->quantity;

            // Update quantity
            $detail->update(['quantity' => $newQuantity]);

            $productDetail = Product::find($detail->product_id);
            $newTotalPrice = $productDetail->price * $newQuantity;
            $detail->update(['total_price' => $newTotalPrice]);
            $transaction = Transaction::find($detail->transaction_id);
            $transaction->total_quantity = $transaction->total_quantity - $oldQuantity + $newQuantity;
            $transaction->total_price = $transaction->details()->sum('total_price');

            $transaction->save();

            return response()->json(['message' => 'Product updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $detail = TransactionDetail::find($id);

            if (!$detail) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            $transactionId = $detail->transaction_id;

            // Delete the product
            $detail->delete();

            $this->recalculateTransactionTotalPrice($transactionId);

            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function recalculateTransactionTotalPrice($transactionId)
    {
        $transaction = Transaction::with('details')->find($transactionId);

        if (!$transaction) {
            return;
        }

        $totalPrice = 0;

        foreach ($transaction->details as $detail) {
            $totalPrice += $detail->total_price;
        }

        $transaction->update(['total_price' => $totalPrice]);
    }
}
