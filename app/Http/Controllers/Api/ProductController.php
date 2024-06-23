<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
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
        $merchant = Merchant::find($id);
        if (!$merchant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Merchant not found',
            ], 404);
        }

        $products = Product::where('merchant_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'List data products by merchant',
            'data' => [
                'merchant' => $merchant,
                'products' => $products
            ]
        ]);
    }
}
