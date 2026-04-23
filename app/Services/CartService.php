<?php

namespace App\Services;

use App\Models\{CartItem, Product, User};

class CartService
{
    public function add(User $user, string $productId, int $qty = 1): array
    {
        $product = Product::active()->inStock()->findOrFail($productId);

        $item = CartItem::firstOrNew([
            'user_id'    => $user->id,
            'product_id' => $productId,
        ]);

        $newQty = ($item->quantity ?? 0) + $qty;

        if ($newQty > $product->stock_quantity) {
            return ['success' => false, 'message' => __('messages.cart.stock_exceeded')];
        }

        $item->quantity = $newQty;
        $item->save();

        return [
            'success'    => true,
            'message'    => __('messages.cart.added'),
            'cart_count' => $user->cartItems()->sum('quantity'),
        ];
    }

    public function update(User $user, string $itemId, int $qty): array
    {
        $item = CartItem::where('user_id', $user->id)->findOrFail($itemId);

        if ($qty <= 0) {
            $item->delete();
            return ['success' => true, 'message' => __('messages.cart.removed')];
        }

        if ($qty > $item->product->stock_quantity) {
            return ['success' => false, 'message' => __('messages.cart.stock_exceeded')];
        }

        $item->update(['quantity' => $qty]);
        return ['success' => true, 'message' => __('messages.cart.updated')];
    }

    public function remove(User $user, string $itemId): void
    {
        CartItem::where('user_id', $user->id)->where('id', $itemId)->delete();
    }

    public function clear(User $user): void
    {
        CartItem::where('user_id', $user->id)->delete();
    }

    public function getSummary(User $user, float $discountAmount = 0): array
    {
        $items    = $user->cartItems()->with('product.brand')->get();
        $subtotal = $items->sum('subtotal');
        $shipping = $this->calculateShipping($subtotal);

        return [
            'items'           => $items,
            'subtotal'        => $subtotal,
            'shipping'        => $shipping,
            'discount'        => $discountAmount,
            'total'           => max(0, $subtotal + $shipping - $discountAmount),
            'items_count'     => $items->sum('quantity'),
        ];
    }

    private function calculateShipping(float $subtotal): float
    {
        return $subtotal >= 500 ? 0 : 50;
    }
}
