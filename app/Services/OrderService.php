<?php

namespace App\Services;

use App\Models\{Order, OrderItem, OrderTracking, User};
use App\Notifications\OrderPlacedNotification;
use App\Jobs\SendWhatsAppOrderNotification;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private CartService   $cartService,
        private CouponService $couponService,
    ) {}

    public function placeOrder(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $couponDiscount = 0;
            $coupon = null;

            if (!empty($data['coupon_code'])) {
                $cartSummary  = $this->cartService->getSummary($user);
                $couponResult = $this->couponService->validate($data['coupon_code'], $cartSummary['subtotal'], $user->id);
                if ($couponResult['valid']) {
                    $couponDiscount = $couponResult['discount'];
                    $coupon = $couponResult['coupon'];
                }
            }

            $summary = $this->cartService->getSummary($user, $couponDiscount);

            $order = Order::create([
                'user_id'         => $user->id,
                'address_id'      => $data['address_id'],
                'subtotal'        => $summary['subtotal'],
                'shipping_fee'    => $summary['shipping'],
                'discount_amount' => $summary['discount'],
                'total_amount'    => $summary['total'],
                'coupon_code'     => $data['coupon_code'] ?? null,
                'payment_method'  => $data['payment_method'],
                'notes'           => $data['notes'] ?? null,
            ]);

            foreach ($summary['items'] as $item) {
                OrderItem::create([
                    'order_id'        => $order->id,
                    'product_id'      => $item->product_id,
                    'product_name_en' => $item->product->name_en,
                    'product_name_ar' => $item->product->name_ar,
                    'product_image'   => $item->product->first_image,
                    'unit_price'      => $item->product->current_price,
                    'quantity'        => $item->quantity,
                    'subtotal'        => $item->subtotal,
                ]);

                $item->product->decrement('stock_quantity', $item->quantity);
                $item->product->increment('total_sold', $item->quantity);
            }

            $this->addTracking($order, 'pending', $user->id);

            if ($coupon) {
                $this->couponService->redeem($coupon, $user->id, $order->id, $couponDiscount);
            }

            $this->cartService->clear($user);

            try {
                $user->notify(new OrderPlacedNotification($order));
            } catch (\Exception $e) {
                \Log::warning('Order notification failed: ' . $e->getMessage());
            }

            try {
                SendWhatsAppOrderNotification::dispatch($order)->afterCommit();
            } catch (\Exception $e) {
                \Log::warning('WhatsApp dispatch failed: ' . $e->getMessage());
            }

            return $order;
        });
    }

    public function updateStatus(Order $order, string $newStatus, ?string $adminId = null, array $extra = []): void
    {
        $oldStatus = $order->status;
        $updates = ['status' => $newStatus];

        if ($newStatus === 'shipped') {
            $updates['shipped_at']       = now();
            $updates['tracking_number']  = $extra['tracking_number'] ?? null;
            $updates['shipping_company'] = $extra['shipping_company'] ?? null;
        }

        if ($newStatus === 'delivered') {
            $updates['delivered_at'] = now();
        }

        if (isset($extra['admin_notes'])) {
            $updates['admin_notes'] = $extra['admin_notes'];
        }

        $order->update($updates);
        $this->addTracking($order, $newStatus, $adminId);

        try {
            $order->user->notify(new \App\Notifications\OrderStatusUpdatedNotification($order, $oldStatus));
        } catch (\Exception $e) {
            \Log::warning('Status notification failed: ' . $e->getMessage());
        }
    }

    public function addTracking(Order $order, string $status, ?string $updatedBy = null): void
    {
        $descriptions = [
            'pending'    => ['en' => 'Order received and pending confirmation',  'ar' => 'تم استلام الطلب وبانتظار التأكيد'],
            'confirmed'  => ['en' => 'Order confirmed by our team',              'ar' => 'تم تأكيد الطلب من قِبل فريقنا'],
            'processing' => ['en' => 'Order is being prepared',                  'ar' => 'جاري تجهيز طلبك'],
            'shipped'    => ['en' => 'Order has been shipped',                   'ar' => 'تم شحن الطلب'],
            'delivered'  => ['en' => 'Order delivered successfully',             'ar' => 'تم توصيل الطلب بنجاح'],
            'cancelled'  => ['en' => 'Order has been cancelled',                 'ar' => 'تم إلغاء الطلب'],
            'refunded'   => ['en' => 'Order has been refunded',                  'ar' => 'تم استرجاع مبلغ الطلب'],
        ];

        OrderTracking::create([
            'order_id'       => $order->id,
            'status'         => $status,
            'description_en' => $descriptions[$status]['en'] ?? $status,
            'description_ar' => $descriptions[$status]['ar'] ?? $status,
            'updated_by'     => $updatedBy,
            'occurred_at'    => now(),
        ]);
    }
}
