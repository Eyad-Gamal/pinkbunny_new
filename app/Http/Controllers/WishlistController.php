<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()->wishlistItems()->with('product.brand', 'product.category')->latest()->get();
        return view('wishlist.index', compact('items'));
    }

    public function toggle(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $item = WishlistItem::where('user_id', $request->user()->id)
            ->where('product_id', $data['product_id'])
            ->first();

        if ($item) {
            $item->delete();
            $payload = ['status' => 'removed', 'message' => __('messages.wishlist.removed')];
        } else {
            WishlistItem::create([
                'user_id'    => $request->user()->id,
                'product_id' => $data['product_id'],
            ]);
            $payload = ['status' => 'added', 'message' => __('messages.wishlist.added')];
        }

        if ($request->expectsJson()) {
            return response()->json($payload);
        }

        return back()->with('toast', $payload['message']);
    }
}
