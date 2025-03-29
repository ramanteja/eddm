<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class DispenserController extends Controller
{
    // Raspberry Pi checks if order is approved
    public function getStatus(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($validated['order_id']);

        return response()->json([
            'status' => $order->status,
            'payment_status' => $order->payment_status,
        ]);
    }

    // Raspberry Pi marks order as dispensed
    public function markDispensed(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($validated['order_id']);
        $order->status = 'dispensed';
        $order->save();

        return response()->json(['message' => 'Order marked as dispensed.']);
    }

    // Example endpoint for future extension
    public function dispense(Request $request)
    {
        return response()->json(['message' => 'Dispense endpoint reached.']);
    }
}