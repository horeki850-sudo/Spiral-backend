<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    public function index()
    {
        return ReviewResource::collection(
            Review::with(['user', 'product'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create($validated);

        return (new ReviewResource($review))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Review $review)
    {
        return new ReviewResource(
            $review->load(['user', 'product'])
        );
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($validated);

        return new ReviewResource($review);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ]);
    }
}