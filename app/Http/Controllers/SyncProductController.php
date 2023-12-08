<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SyncProductController extends Controller
{
    public function index()
    {
        $apiData = $this->fetchApiData();
        return view('sync.index', compact('apiData'));
    }

    public function getData()
    {
        try {
            $apiData = $this->fetchApiData();
            return response()->json(['data' => $apiData['products']]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sync()
    {
        try {
            DB::beginTransaction();
            $apiData = $this->fetchApiData();
            foreach ($apiData['products'] as $productData) {
                Product::create([
                    'product_name' => $productData['title'],
                    'price' => $productData['price'],
                ]);
            }
            DB::commit();

            return response()->json(['data' => $apiData['products']]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function fetchApiData()
    {
        $response = Http::get('https://dummyjson.com/products');
        return $response->json();
    }
}
