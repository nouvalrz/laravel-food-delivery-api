<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //index
    public function index()
    {
        $products = Product::with('merchant')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'List data products',
            'data' => $products
        ]);
    }

    //get all products by merchant
    public function getProductsByMerchant($id)
    {
        $products = Product::where('merchant_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'List data products by merchant',
            'data' => $products
        ]);
    }
}
