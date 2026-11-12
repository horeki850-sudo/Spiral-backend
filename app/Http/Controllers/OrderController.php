<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(
            Order::with(['user', 'orderItems.product'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:255',
        ]);

        $order = Order::create($validated);

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Order $order)
    {
        return new OrderResource(
            $order->load(['user', 'orderItems.product'])
        );
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:255',
        ]);

        $order->update($validated);

        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully',
        ]);
    }
}