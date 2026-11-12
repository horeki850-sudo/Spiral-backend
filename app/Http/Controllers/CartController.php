<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
    public function index()
    {
        return CartResource::collection(
            Cart::with('user')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $cart = Cart::create($validated);

        return (new CartResource($cart))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Cart $cart)
    {
        return new CartResource(
            $cart->load(['user', 'cartItems.product'])
        );
    }

    public function update(Request $request, Cart $cart)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $cart->update($validated);

        return new CartResource($cart);
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        return response()->json([
            'message' => 'Cart deleted successfully',
        ]);
    }
}