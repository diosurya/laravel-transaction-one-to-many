<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{

    public function index()
    {
        return view('product.index');
    }

    public function dataTable()
    {
        $products = Product::select(['id', 'product_name', 'price']);
        return DataTables::of($products)
            ->addColumn('action', function ($product) {
                return '
                <a href="' . route('product.edit', $product->id) . '" class="btn btn-sm btn-warning"><i class="fas fa-pencil"></i></a>
                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDeleteProduct(' . $product->id . ')"><i class="fas fa-trash"></i></button>
            ';
            })
            ->toJson();
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);
        Product::create($request->all());
        return redirect()->route('product.index')->with('success', 'Product created successfully!');
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return redirect()->route('product.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['success' => 'Product deleted successfully!']);
    }
}
