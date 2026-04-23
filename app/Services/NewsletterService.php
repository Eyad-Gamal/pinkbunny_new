<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Str;

class NewsletterService
{
    public function subscribe(string $contact): array
    {
        $normalized = trim($contact);

        if (NewsletterSubscriber::where('contact', $normalized)->exists()) {
            return [
                'success' => false,
                'message' => __('messages.newsletter.already_subscribed'),
            ];
        }

        $coupon = Coupon::firstOrCreate(
            ['code' => 'BUNNY10'],
            [
                'type'           => 'percentage',
                'value'          => 10,
                'is_active'      => true,
                'expires_at'     => now()->addMonths(6),
            ]
        );

        NewsletterSubscriber::create([
            'contact' => $normalized,
            'coupon_code' => $coupon->code,
        ]);

        return [
            'success' => true,
            'coupon_code' => $coupon->code,
            'message' => __('messages.newsletter.success', ['code' => $coupon->code]),
        ];
    }
}
