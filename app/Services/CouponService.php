<?php

namespace App\Services;

use App\Models\{Coupon, CouponUsage};

class CouponService
{
    public function validate(string $code, float $subtotal, string $userId): array
    {
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon) {
            return ['valid' => false, 'message' => __('messages.cart.invalid_coupon')];
        }

        if (!$coupon->isValid()) {
            return ['valid' => false, 'message' => __('messages.cart.coupon_expired')];
        }

        if ($subtotal < (float) $coupon->min_order_amount) {
            return ['valid' => false, 'message' => __('messages.cart.coupon_min_order', ['amount' => $coupon->min_order_amount])];
        }

        if ($coupon->hasUserExceededLimit($userId)) {
            return ['valid' => false, 'message' => __('messages.cart.coupon_used')];
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return [
            'valid'    => true,
            'discount' => $discount,
            'message'  => __('messages.cart.coupon_applied'),
            'coupon'   => $coupon,
        ];
    }

    public function redeem(Coupon $coupon, string $userId, string $orderId, float $discount): void
    {
        CouponUsage::create([
            'coupon_id'        => $coupon->id,
            'user_id'          => $userId,
            'order_id'         => $orderId,
            'discount_applied' => $discount,
        ]);

        $coupon->increment('used_count');
    }
}
