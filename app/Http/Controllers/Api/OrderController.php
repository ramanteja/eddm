<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'medicine_id' => 'required|exists:medicines,id',
            'dispenser_id' => 'required|exists:dispensers,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $order = Order::create([
            'user_id' => $validated['user_id'],
            'medicine_id' => $validated['medicine_id'],
            'dispenser_id' => $validated['dispenser_id'],
            'quantity' => $validated['quantity'],
            'status' => 'pending',
            'payment_status' => false,
        ]);

        return response()->json(['success' => true, 'order' => $order], 201);
    }
}
