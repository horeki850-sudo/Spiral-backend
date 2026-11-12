<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    public function show(User $user)
    {
        return new UserResource(
            $user->load([
                'carts',
                'reviews.product',
                'wishlists.product',
                'orders.orderItems.product'
            ])
        );
    }

    public function update(Request $request, User $user)
{
    // Authorization: users can update only their own account
    if ($request->user()->id !== $user->id) {
        return response()->json([
            'message' => 'Forbidden. You can only update your own account.'
        ], 403);
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:8',
    ]);

    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    $user->update($validated);

    return new UserResource($user);
}

    public function destroy(Request $request, User $user)
{
    // Authorization: users can delete only their own account
    if ($request->user()->id !== $user->id) {
        return response()->json([
            'message' => 'Forbidden. You can only delete your own account.'
        ], 403);
    }

    $user->delete();

    return response()->json([
        'message' => 'User deleted successfully',
    ]);
}
}