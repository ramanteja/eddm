<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Dispenser;
use App\Models\Stock;

class DispenserApiController extends Controller
{
    public function getNextOrder(Request $request)
    {
        $dispenser = $request->get('dispenser'); // set by auth.token middleware

        $order = Order::where('dispenser_id', $dispenser->id)
                      ->where('status', 'requested')
                      ->where('payment_status', 1) // strict check for paid
                      ->orderBy('created_at')
                      ->first();

        if (!$order) {
            return response()->json(['message' => 'No pending orders'], 404);
        }

        return response()->json([
            'order_id'     => $order->id,
            'medicine_id'  => $order->medicine_id,
            'quantity'     => $order->quantity,
            'spiral'       => $order->spiral,
        ]);
    }

    public function markAsDispensed(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($request->order_id);

        if (
            !$order ||
            $order->status !== 'requested' ||
            (int) $order->payment_status !== 1 // ensure payment is confirmed
        ) {
            return response()->json(['error' => 'Invalid or already processed order'], 400);
        }

        $stock = Stock::where('dispenser_id', $order->dispenser_id)
                      ->where('medicine_id', $order->medicine_id)
                      ->first();

        if ($stock) {
            $stock->stock = max(0, $stock->stock - $order->quantity);
            $stock->save();
        }

        $order->status = 'dispensed';
        $order->save();

        return response()->json(['message' => 'Order marked as dispensed']);
    }
}