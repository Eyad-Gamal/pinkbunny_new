<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class SendWhatsAppOrderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public Order $order) {}

    public function handle(WhatsAppService $whatsApp): void
    {
        $whatsApp->sendNewOrderNotification($this->order);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("WhatsApp job failed for order #{$this->order->order_number}: " . $exception->getMessage());
    }
}
