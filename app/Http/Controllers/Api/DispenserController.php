<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Stock;

class DispenserController extends Controller
{
    // Check order status and payment (e.g., before dispensing)
    public function getStatus(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($validated['order_id']);

        return response()->json([
            'status'         => $order->status,
            'payment_status' => $order->payment_status,
            'spiral'         => $order->spiral,
        ]);
    }

    // Safely mark order as dispensed and reduce stock
    public function markDispensed(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($validated['order_id']);

        if ($order->status === 'dispensed') {
            return response()->json(['message' => 'Order already marked as dispensed.'], 200);
        }

        if ((int) $order->payment_status !== 1) {
            return response()->json(['error' => 'Payment not completed. Cannot dispense.'], 403);
        }

        $stock = Stock::where('dispenser_id', $order->dispenser_id)
                      ->where('medicine_id', $order->medicine_id)
                      ->first();

        if (!$stock) {
            return response()->json(['error' => 'Matching stock not found.'], 404);
        }

        if ($stock->stock < $order->quantity) {
            return response()->json(['error' => 'Insufficient stock for dispensing.'], 400);
        }

        // Reduce stock and mark as dispensed
        $stock->stock = max(0, $stock->stock - $order->quantity);
        $stock->save();

        $order->status = 'dispensed';
        $order->save();

        return response()->json(['message' => 'Order successfully marked as dispensed.']);
    }

    // Optional: placeholder for triggering actual hardware dispense logic
    public function dispense(Request $request)
    {
        return response()->json(['message' => 'Dispense endpoint reached.']);
    }
}