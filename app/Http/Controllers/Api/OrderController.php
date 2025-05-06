<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Stock;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'medicine_id'   => 'required|exists:medicines,id',
            'dispenser_id'  => 'required|exists:dispensers,id',
            'quantity'      => 'required|integer|min:1',
        ]);

        $stock = Stock::where('dispenser_id', $validated['dispenser_id'])
                      ->where('medicine_id', $validated['medicine_id'])
                      ->first();

        if (!$stock) {
            return response()->json(['error' => 'Stock not found for this dispenser and medicine.'], 404);
        }

        if ($stock->stock < $validated['quantity']) {
            return response()->json(['error' => 'Not enough stock available.'], 400);
        }

        $order = Order::create([
            'user_id'        => $validated['user_id'],
            'medicine_id'    => $validated['medicine_id'],
            'dispenser_id'   => $validated['dispenser_id'],
            'quantity'       => $validated['quantity'],
            'spiral'         => $stock->spiral, // ✅ include spiral like in Filament
            'status'         => 'requested',    // ✅ match Filament status
            'payment_status' => false,
        ]);

        return response()->json(['success' => true, 'order' => $order], 201);
    }
}