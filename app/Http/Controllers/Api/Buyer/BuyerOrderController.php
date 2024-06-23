<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;


class BuyerOrderController extends Controller
{
    //index
    public function index()
    {
        $buyer = auth()->user()->buyer;
        $orders = $buyer->orders()->with('orderItems.product')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'List data orders',
            'data' => $orders
        ]);
    }

    // create
    public function create(Request $request)
    {
        try {
            $request->validate([
                'order_items' => 'required|array',
                'order_items.*.product_id' => 'required|exists:products,id',
                'order_items.*.quantity' => 'required|numeric',
                'merchant_id' => 'required|exists:merchants,id',
                'shipping_cost' => 'required|numeric',
                'shipping_latitude' => 'required|numeric',
                'shipping_longitude' => 'required|numeric',
                'shipping_address_detail' => 'required|string',
            ]);

            DB::beginTransaction();

            $totalPrice = 0;
            foreach ($request->order_items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception('Product not found');
                }
                $totalPrice += $product->price * $item['quantity'];
            }

            $buyer = $request->user()->buyer;
            $order = $buyer->orders()->create([
                'merchant_id' => $request->merchant_id,
                'shipping_cost' => $request->shipping_cost,
                'total_price' => $totalPrice,
                'total_bill' => $totalPrice + $request->shipping_cost,
                'shipping_latitude' => $request->shipping_latitude,
                'shipping_longitude' => $request->shipping_longitude,
                'shipping_address_detail' => $request->shipping_address_detail,
                'status' => Order::STATUS_PENDING,
            ]);

            foreach ($request->order_items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception('Product ' . $item['product_id'] . ' not found');
                }
                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => $order->load('orderItems')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // cancel order
    public function cancelOrder($id)
    {
        $buyer = auth()->user()->buyer;
        $order = $buyer->orders()->find($id);
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        if ($order->status !== Order::STATUS_PENDING) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order cannot be cancelled',
            ], 400);
        }

        $order->status = ORDER::STATUS_CANCELLED_BY_BUYER;
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Order cancelled successfully',
            'data' => $order
        ]);
    }
}
