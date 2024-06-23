<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DriverOrderController extends Controller
{
    //
    public function index(){
        $orders = Order::with('merchant')->where('status', Order::STATUS_READY)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List orders with status ready',
            'data' => $orders
        ]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        try{
            $request->validate([
                'status' => 'required|in:pickup,delivering,received'
            ]);

            $order = Order::findOrFail($id);

            if(!in_array($order->status, [Order::STATUS_READY, Order::STATUS_PICKUP, Order::STATUS_DELIVERING])){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order status not eligible for update (STATUS : ' . $order->status . ')'
                ], 400);
            }

            $order->status = $request->status;
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Success update order status to ' . $request->status,
                'data' => $order
            ]);

        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
