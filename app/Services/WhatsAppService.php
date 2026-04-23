<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private bool $enabled;

    public function __construct()
    {
        $this->enabled = !empty(config('services.twilio.sid'))
                      && !empty(config('services.twilio.token'));
    }

    public function sendNewOrderNotification(Order $order): bool
    {
        if (!$this->enabled) {
            Log::info('WhatsApp disabled — skipping order notification for #' . $order->order_number);
            return false;
        }

        $order->load(['user', 'items', 'address']);

        $itemsList = $order->items->map(function ($item) {
            return "• {$item->product_name_en} × {$item->quantity} — {$item->subtotal} EGP";
        })->join("\n");

        $message = "🐰 *New Order on Pink Bunny!*\n\n"
                 . "📋 *Order:* #{$order->order_number}\n"
                 . "👤 *Customer:* {$order->user->name}\n"
                 . "📞 *Phone:* {$order->user->phone}\n"
                 . "📍 *Address:* {$order->address->governorate} — {$order->address->city}\n\n"
                 . "🛍️ *Products:*\n{$itemsList}\n\n"
                 . "💰 *Total:* {$order->total_amount} EGP\n"
                 . "🚚 *Shipping:* {$order->shipping_fee} EGP\n"
                 . "💳 *Payment:* {$order->payment_method}\n"
                 . "⏰ *Time:* {$order->created_at->format('Y-m-d H:i')}";

        try {
            $client = new \Twilio\Rest\Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $client->messages->create(config('services.twilio.admin_whatsapp'), [
                'from' => config('services.twilio.whatsapp_from'),
                'body' => $message,
            ]);

            $order->update(['whatsapp_notified' => true]);
            return true;
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage(), ['order_id' => $order->id]);
            return false;
        }
    }

    public function sendOrderStatusToCustomer(Order $order): bool
    {
        if (!$this->enabled || !$order->user->phone) return false;

        $message = "🐰 *Pink Bunny — Order Update*\n\n"
                 . "Order: #{$order->order_number}\n"
                 . "Status: *{$order->status_label}*\n";

        if ($order->tracking_number) {
            $message .= "Tracking: {$order->tracking_number}\n";
        }

        $message .= "\nThank you for shopping with us! 💕";

        try {
            $client = new \Twilio\Rest\Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $client->messages->create("whatsapp:{$order->user->phone}", [
                'from' => config('services.twilio.whatsapp_from'),
                'body' => $message,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Customer WhatsApp failed: ' . $e->getMessage());
            return false;
        }
    }
}
