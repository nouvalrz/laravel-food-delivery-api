<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MerchantProductController extends Controller
{
    // get all products by merchant
    public function getProductsByMerchant($id)
    {
        $products = Product::where('merchant_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'List data products by merchant',
            'data' => $products
        ]);
    }

    // get all product by logged merchant
    public function index(){
        $merchant = auth()->user()->merchant;
        $products = $merchant->products;
        return response()->json([
            'status' => 'success',
            'message' => 'List data products by merchant',
            'data' => $products
        ]);
    }

    // store
    public function store(Request $request)
    {
        // dd($request);
        try {
            $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'description' => 'required|string',
                'image' => 'required|image',
                'stock' => 'required|numeric',
                'is_available' => 'required|boolean',
                'is_favorite' => 'required|boolean',
            ]);

            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            $merchant = $request->user()->merchant;
            $product = $merchant->products()->create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'image' => '/storage/products/' . $image->hashName(),
                'stock' => $request->stock,
                'is_available' => $request->is_available,
                'is_favorite' => $request->is_favorite,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Product created',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // update
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'description' => 'required|string',
                'image' => 'image',
                'stock' => 'required|numeric',
                'is_available' => 'required|boolean',
                'is_favorite' => 'required|boolean',
            ]);

            $product = Product::findOrFail($id);
            $product->name = $request->input('name');
            $product->price = $request->input('price');
            $product->description = $request->input('description');
            $product->stock = $request->input('stock');
            $product->is_available = $request->input('is_available');
            $product->is_favorite = $request->input('is_favorite');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image->storeAs('public/products', $image->hashName());
                $product->image = $image->hashName();
            }

            $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Product updated',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // destroy with soft delete
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
