<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\{OrderService, WhatsAppService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    public function __construct(
        private OrderService   $orderService,
        private WhatsAppService $whatsApp,
    ) {}

    /**
     * Handle incoming webhooks from Evolution API.
     *
     * Evolution sends a JSON payload for every incoming message.
     * We extract the sender's phone and message text, then look
     * for a pending order that matches the phone number.
     */
    public function handle(Request $request)
    {
        // ── Security: verify webhook secret ──
        $secret = config('services.evolution.webhook_secret');
        if ($secret && $request->header('apikey') !== $secret) {
            Log::warning('WhatsApp webhook: invalid secret', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // ── Extract message data from Evolution payload ──
        $data = $request->all();

        // Evolution API v2 payload structure
        $event = $data['event'] ?? '';

        // Only process incoming messages
        if (!in_array($event, ['messages.upsert', 'MESSAGES_UPSERT'])) {
            return response()->json(['status' => 'ignored']);
        }

        $messageData = $data['data'] ?? $data;

        // Extract key/remoteJid and message text
        $remoteJid  = $messageData['key']['remoteJid']
                   ?? $messageData['remoteJid']
                   ?? null;
        $fromMe     = $messageData['key']['fromMe'] ?? false;
        $messageText = $messageData['message']['conversation']
                    ?? $messageData['message']['extendedTextMessage']['text']
                    ?? null;

        // Ignore messages sent BY us, or empty messages
        if ($fromMe || !$messageText || !$remoteJid) {
            return response()->json(['status' => 'skipped']);
        }

        // Extract phone number from JID (format: 201234567890@s.whatsapp.net)
        $phone = str_replace('@s.whatsapp.net', '', $remoteJid);
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Normalize the reply text
        $reply = trim($messageText);

        Log::info("WhatsApp webhook received", [
            'phone' => $phone,
            'reply' => $reply,
        ]);

        // ── Find the latest pending order for this phone ──
        $order = $this->findPendingOrderByPhone($phone);

        if (!$order) {
            // No pending order found for this phone number
            if (in_array($reply, ['1', '2'])) {
                $this->whatsApp->sendNoPendingOrders($phone);
            }
            return response()->json(['status' => 'no_pending_order']);
        }

        // ── Process the reply ──
        if ($reply === '1') {
            return $this->confirmOrder($order, $phone);
        }

        if ($reply === '2') {
            return $this->cancelOrder($order, $phone);
        }

        // Unrecognized reply — ignore silently
        return response()->json(['status' => 'unrecognized_reply']);
    }

    /**
     * Confirm an order (customer replied "1").
     */
    private function confirmOrder(Order $order, string $phone)
    {
        $this->orderService->updateStatus($order, 'confirmed');

        $order->update(['whatsapp_confirmed_at' => now()]);

        $this->whatsApp->sendConfirmationSuccess($order->fresh());

        Log::info("Order #{$order->order_number} confirmed via WhatsApp by {$phone}");

        return response()->json([
            'status'       => 'confirmed',
            'order_number' => $order->order_number,
        ]);
    }

    /**
     * Cancel an order (customer replied "2").
     */
    private function cancelOrder(Order $order, string $phone)
    {
        // Restore stock quantities
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock_quantity', $item->quantity);
                $item->product->decrement('total_sold', $item->quantity);
            }
        }

        $this->orderService->updateStatus($order, 'cancelled');

        $this->whatsApp->sendCancellationConfirmation($order->fresh());

        Log::info("Order #{$order->order_number} cancelled via WhatsApp by {$phone}");

        return response()->json([
            'status'       => 'cancelled',
            'order_number' => $order->order_number,
        ]);
    }

    /**
     * Find the most recent pending order matching a phone number.
     *
     * Searches by:
     *  1. The address phone on the order
     *  2. The user's phone on their profile
     */
    private function findPendingOrderByPhone(string $phone): ?Order
    {
        // Build a list of possible phone formats to match
        $variants = $this->phoneVariants($phone);

        // Search by address phone first (most reliable — it's the delivery phone)
        $order = Order::where('status', 'pending')
            ->whereHas('address', function ($q) use ($variants) {
                $q->where(function ($q2) use ($variants) {
                    foreach ($variants as $v) {
                        $q2->orWhere('phone', $v);
                    }
                });
            })
            ->with(['items.product', 'user', 'address'])
            ->latest()
            ->first();

        if ($order) return $order;

        // Fallback: search by user phone
        return Order::where('status', 'pending')
            ->whereHas('user', function ($q) use ($variants) {
                $q->where(function ($q2) use ($variants) {
                    foreach ($variants as $v) {
                        $q2->orWhere('phone', $v);
                    }
                });
            })
            ->with(['items.product', 'user', 'address'])
            ->latest()
            ->first();
    }

    /**
     * Generate all possible phone format variants for matching.
     *
     * E.g. input "201012345678" → ["201012345678", "01012345678", "+201012345678"]
     */
    private function phoneVariants(string $phone): array
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $variants = [$phone, "+{$phone}"];

        // If starts with country code "20", also try local format
        if (str_starts_with($phone, '20') && strlen($phone) === 12) {
            $local = '0' . substr($phone, 2);
            $variants[] = $local;
        }

        // If starts with "0", also try with country code
        if (str_starts_with($phone, '0') && strlen($phone) === 11) {
            $intl = '20' . substr($phone, 1);
            $variants[] = $intl;
            $variants[] = "+{$intl}";
        }

        return array_unique($variants);
    }
}
