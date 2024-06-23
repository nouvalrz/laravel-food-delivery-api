<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class MerchantOrderController extends Controller
{
    // get all order by merchant
    public function index()
    {
        $merchant = auth()->user()->merchant;
        $orders = $merchant->orders()
                    ->whereNotIn('status', [
                        Order::STATUS_PENDING,
                        Order::STATUS_CANCELLED_BY_BUYER,
                        Order::STATUS_CANCELLED_BY_SYSTEM
                    ])
                    ->with('buyer')
                    ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'List data orders by merchant',
            'data' => $orders
        ]);
    }

    public function getOrderByStatus(Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|in:paid,processing,ready,pickup,delivering,received'
            ]);

            $merchant = auth()->user()->merchant;
            if (!$merchant) {
                throw new \Exception('Merchant not found');
            }

            $orders = $merchant->orders()->where('status', $request->status)->with('buyer')->get();
            if (!$orders) {
                throw new \Exception('Orders not found');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'List data orders by merchant with status '.$request->status,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
