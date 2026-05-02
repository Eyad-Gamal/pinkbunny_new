<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp service — currently disabled.
 * Kept as a stub for potential future WhatsApp integration.
 */
class WhatsAppService
{
    public function sendNewOrderNotification(Order $order): bool
    {
        Log::info('WhatsApp disabled — skipping notification for #' . $order->order_number);
        return false;
    }

    public function sendOrderStatusToCustomer(Order $order): bool
    {
        return false;
    }
}
