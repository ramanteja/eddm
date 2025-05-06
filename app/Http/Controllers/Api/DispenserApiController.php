<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Stock;

class DispenserApiController extends Controller
{
    // Fetch the next pending and paid order for a dispenser
    public function getNextOrder(Request $request)
    {
        $dispenser = $request->get('dispenser'); // Set by auth middleware

        $order = Order::where('dispenser_id', $dispenser->id)
                      ->where('status', 'requested')
                      ->where('payment_status', true)
                      ->orderBy('created_at')
                      ->first();

        if (!$order) {
            return response()->json(['message' => 'No pending paid orders'], 404);
        }

        return response()->json([
            'order_id'          => $order->id,
            'medicine_id'       => $order->medicine_id,
            'quantity'          => $order->quantity,
            'spiral'            => $order->spiral,
            'status'            => $order->status,
            'payment_status'    => $order->payment_status,
            'dispensation_time' => $order->updated_at,
        ]);
    }

    // Safely mark the order as dispensed
    public function markAsDispensed(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($validated['order_id']);

        if (!$order || $order->status !== 'requested') {
            return response()->json(['error' => 'Invalid or already processed order'], 400);
        }

        if (!$order->payment_status) {
            return response()->json(['error' => 'Payment not completed. Dispensing not allowed.'], 403);
        }

        $stock = Stock::where('dispenser_id', $order->dispenser_id)
                      ->where('medicine_id', $order->medicine_id)
                      ->first();

        if (!$stock) {
            return response()->json(['error' => 'Stock not found.'], 404);
        }

        if ($stock->stock < $order->quantity) {
            return response()->json(['error' => 'Insufficient stock.'], 400);
        }

        // Reduce stock and update order
        $stock->stock = max(0, $stock->stock - $order->quantity);
        $stock->save();

        $order->status = 'dispensed';
        $order->save();

        return response()->json([
            'message'           => 'Order successfully marked as dispensed.',
            'order_id'          => $order->id,
            'spiral'            => $order->spiral,
            'payment_status'    => $order->payment_status,
            'dispensation_time' => $order->updated_at,
        ]);
    }

    // Optional: Get current status of any order (for frontend/hardware debug)
    public function getOrderStatus(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($validated['order_id']);

        return response()->json([
            'order_id'       => $order->id,
            'status'         => $order->status,
            'payment_status' => $order->payment_status,
            'spiral'         => $order->spiral,
            'quantity'       => $order->quantity,
            'dispensed_at'   => $order->updated_at,
        ]);
    }
}