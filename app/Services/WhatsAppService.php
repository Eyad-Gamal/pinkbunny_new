<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\{Http, Log};

class WhatsAppService
{
    private string $baseUrl;
    private string $apiKey;
    private string $instance;
    private bool   $enabled;

    public function __construct()
    {
        $this->baseUrl  = rtrim(config('services.evolution.url', ''), '/');
        $this->apiKey   = config('services.evolution.api_key', '');
        $this->instance = config('services.evolution.instance', 'pinkbunny');
        $this->enabled  = !empty($this->apiKey) && !empty($this->baseUrl);
    }

    // ─── Public API ───────────────────────────────────────

    /**
     * Send order confirmation message to the CUSTOMER.
     * Asks them to reply 1 (confirm) or 2 (cancel).
     */
    public function sendOrderConfirmation(Order $order): bool
    {
        $order->loadMissing(['user', 'items', 'address']);

        $phone = $this->resolvePhone($order->address?->phone ?? $order->user->phone);
        if (!$phone) {
            Log::warning("WhatsApp: No phone for order #{$order->order_number}");
            return false;
        }

        $itemsList = $order->items->map(function ($item) {
            return "  • {$item->product_name_ar} × {$item->quantity} — {$item->subtotal} جنيه";
        })->join("\n");

        $message = "🐰 *أهلاً بيكِ في Pink Bunny!*\n\n"
                 . "تم استلام أوردر رقم: *#{$order->order_number}*\n\n"
                 . "🛍️ *المنتجات:*\n{$itemsList}\n\n"
                 . "💰 *الإجمالي:* {$order->total_amount} جنيه\n"
                 . "🚚 *الشحن:* {$order->shipping_fee} جنيه\n"
                 . "📍 *العنوان:* {$order->address?->governorate} — {$order->address?->city}\n\n"
                 . "━━━━━━━━━━━━━━━\n"
                 . "برجاء الرد بـ:\n"
                 . "*1* ✅ لتأكيد الأوردر\n"
                 . "*2* ❌ لإلغاء الأوردر\n"
                 . "━━━━━━━━━━━━━━━";

        $sent = $this->sendMessage($phone, $message);

        if ($sent) {
            $order->update(['whatsapp_notified' => true]);
        }

        return $sent;
    }

    /**
     * Send new-order notification to the ADMIN.
     */
    public function sendNewOrderNotification(Order $order): bool
    {
        $adminPhone = config('services.twilio.admin_whatsapp'); // reuse env
        if (!$adminPhone) {
            Log::info('WhatsApp: No admin phone configured — skipping admin notification.');
            return false;
        }

        $order->loadMissing(['user', 'items', 'address']);

        $itemsList = $order->items->map(function ($item) {
            return "• {$item->product_name_en} × {$item->quantity} — {$item->subtotal} EGP";
        })->join("\n");

        $message = "🐰 *New Order on Pink Bunny!*\n\n"
                 . "📋 *Order:* #{$order->order_number}\n"
                 . "👤 *Customer:* {$order->user->name}\n"
                 . "📞 *Phone:* {$order->user->phone}\n"
                 . "📍 *Address:* {$order->address?->governorate} — {$order->address?->city}\n\n"
                 . "🛍️ *Products:*\n{$itemsList}\n\n"
                 . "💰 *Total:* {$order->total_amount} EGP\n"
                 . "🚚 *Shipping:* {$order->shipping_fee} EGP\n"
                 . "💳 *Payment:* {$order->payment_method}\n"
                 . "⏰ *Time:* {$order->created_at->format('Y-m-d H:i')}";

        return $this->sendMessage($this->resolvePhone($adminPhone), $message);
    }

    /**
     * Send order status update to the CUSTOMER.
     */
    public function sendOrderStatusToCustomer(Order $order): bool
    {
        $order->loadMissing(['user', 'address']);

        $phone = $this->resolvePhone($order->address?->phone ?? $order->user->phone);
        if (!$phone) return false;

        $statusEmoji = match($order->status) {
            'confirmed'  => '✅',
            'processing' => '⚙️',
            'shipped'    => '🚚',
            'delivered'  => '🎉',
            'cancelled'  => '❌',
            'refunded'   => '💰',
            default      => '📋',
        };

        $statusAr = match($order->status) {
            'confirmed'  => 'تم تأكيد الأوردر',
            'processing' => 'جاري تجهيز الأوردر',
            'shipped'    => 'تم شحن الأوردر',
            'delivered'  => 'تم توصيل الأوردر',
            'cancelled'  => 'تم إلغاء الأوردر',
            'refunded'   => 'تم استرجاع المبلغ',
            default      => $order->status,
        };

        $message = "🐰 *Pink Bunny — تحديث الأوردر*\n\n"
                 . "📋 أوردر رقم: *#{$order->order_number}*\n"
                 . "{$statusEmoji} الحالة: *{$statusAr}*\n";

        if ($order->tracking_number) {
            $message .= "📦 رقم التتبع: {$order->tracking_number}\n";
        }

        $message .= "\nشكراً لتسوقك معانا! 💕";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send a confirmation success message after customer replies "1".
     */
    public function sendConfirmationSuccess(Order $order): bool
    {
        $phone = $this->resolvePhone($order->address?->phone ?? $order->user->phone);
        if (!$phone) return false;

        $message = "✅ *تم تأكيد الأوردر بنجاح!*\n\n"
                 . "📋 أوردر رقم: *#{$order->order_number}*\n"
                 . "💰 الإجمالي: *{$order->total_amount} جنيه*\n\n"
                 . "هيتم تجهيز أوردرك في أقرب وقت 🐰💕\n"
                 . "شكراً ليكِ!";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send a cancellation confirmation after customer replies "2".
     */
    public function sendCancellationConfirmation(Order $order): bool
    {
        $phone = $this->resolvePhone($order->address?->phone ?? $order->user->phone);
        if (!$phone) return false;

        $message = "❌ *تم إلغاء الأوردر*\n\n"
                 . "📋 أوردر رقم: *#{$order->order_number}*\n\n"
                 . "لو محتاجة أي حاجة تاني، إحنا هنا دايماً 🐰💕";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send a "no pending orders" fallback message.
     */
    public function sendNoPendingOrders(string $phone): bool
    {
        $message = "🐰 *Pink Bunny*\n\n"
                 . "مفيش أوردرات معلقة على الرقم ده حالياً.\n"
                 . "لو محتاجة مساعدة، تواصلي معانا! 💕";

        return $this->sendMessage($phone, $message);
    }

    // ─── Internal Helpers ─────────────────────────────────

    /**
     * Send a text message via Evolution API.
     */
    private function sendMessage(string $phone, string $text): bool
    {
        if (!$this->enabled) {
            Log::info("WhatsApp disabled — message not sent to {$phone}");
            return false;
        }

        $url = "{$this->baseUrl}/message/sendText/{$this->instance}";

        try {
            $response = Http::withHeaders([
                'apikey'       => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($url, [
                'number' => $phone,
                'text'   => $text,
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp sent to {$phone}");
                return true;
            }

            Log::error("WhatsApp API error: {$response->status()} — {$response->body()}");
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsApp send failed: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Normalize Egyptian phone numbers for WhatsApp.
     *
     * Converts various formats to the international format:
     *   01012345678  → 201012345678
     *   +201012345678 → 201012345678
     *   201012345678 → 201012345678
     */
    public function resolvePhone(?string $phone): ?string
    {
        if (!$phone) return null;

        // Strip whitespace, dashes, and plus sign
        $phone = preg_replace('/[\s\-\+\(\)]/', '', $phone);

        // Egyptian mobile starting with 0
        if (preg_match('/^0(1[0-9]{9})$/', $phone, $m)) {
            return '20' . $m[1];
        }

        // Already has country code 20
        if (preg_match('/^20(1[0-9]{9})$/', $phone)) {
            return $phone;
        }

        // If it starts with "whatsapp:", strip it
        $phone = preg_replace('/^whatsapp:/', '', $phone);
        $phone = ltrim($phone, '+');

        return $phone ?: null;
    }

    /**
     * Check if the service is enabled (API key & URL configured).
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
