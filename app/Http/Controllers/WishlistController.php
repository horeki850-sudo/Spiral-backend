<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Resources\WishlistResource;

class WishlistController extends Controller
{
    public function index()
    {
        return WishlistResource::collection(
            Wishlist::with(['user', 'product'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::create($validated);

        return (new WishlistResource($wishlist))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Wishlist $wishlist)
    {
        return new WishlistResource(
            $wishlist->load(['user', 'product'])
        );
    }

    public function update(Request $request, Wishlist $wishlist)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist->update($validated);

        return new WishlistResource($wishlist);
    }

    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();

        return response()->json([
            'message' => 'Wishlist deleted successfully',
        ]);
    }
}