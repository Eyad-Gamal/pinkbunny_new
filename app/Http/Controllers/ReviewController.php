<?php

namespace App\Http\Controllers;

use App\Models\{Review, Product};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|between:1,5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        $hasBought = $request->user()
            ->orders()
            ->whereHas('items', fn($q) => $q->where('product_id', $request->product_id))
            ->where('status', 'delivered')
            ->exists();

        if (!$hasBought) {
            return back()->with('error', 'You can only review products you have received.');
        }

        Review::updateOrCreate(
            ['product_id' => $request->product_id, 'user_id' => auth()->id()],
            ['rating' => $request->rating, 'comment' => $request->comment, 'is_approved' => false]
        );

        Product::find($request->product_id)->recalculateRating();

        return back()->with('toast', 'Thank you! Your review will be published after approval.');
    }
}
