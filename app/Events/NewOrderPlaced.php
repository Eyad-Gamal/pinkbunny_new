<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewOrderPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The order data to broadcast.
     * We store a plain array instead of the full model to keep the
     * queued payload small and avoid stale-model issues.
     */
    public array $orderData;

    public function __construct(Order $order)
    {
        $order->loadMissing('user');

        $this->orderData = [
            'id'           => $order->id,
            'order_number' => $order->order_number,
            'customer'     => $order->user?->name ?? 'Guest',
            'total'        => (float) $order->total_amount,
            'status'       => $order->status,
            'created_at'   => $order->created_at->toIso8601String(),
            'url'          => route('admin.orders.show', $order->id),
        ];

        Log::info('[NewOrderPlaced] Event created', ['order_number' => $order->order_number]);
    }

    /**
     * Broadcast on the private admin channel only.
     * Authorization is handled in routes/channels.php.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.orders'),
        ];
    }

    /**
     * Custom event name on the frontend.
     */
    public function broadcastAs(): string
    {
        return 'order.placed';
    }

    /**
     * Only the orderData array is sent over the wire — no extra model data.
     */
    public function broadcastWith(): array
    {
        return $this->orderData;
    }
}
