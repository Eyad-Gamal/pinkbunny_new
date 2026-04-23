<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'product'])
            ->when($request->approved === '1', fn($q) => $q->where('is_approved', true))
            ->when($request->approved === '0', fn($q) => $q->where('is_approved', false))
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(string $id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);
        $review->product->recalculateRating();

        return back()->with('toast', 'Review approved.');
    }

    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);
        $product = $review->product;
        $review->delete();
        $product->recalculateRating();

        return back()->with('toast', 'Review deleted.');
    }
}
