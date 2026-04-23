<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order, public string $oldStatus) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'order_status_updated',
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status'   => $this->oldStatus,
            'new_status'   => $this->order->status,
            'message'      => "Order #{$this->order->order_number} — {$this->order->status_label}",
            'url'          => "/orders/{$this->order->id}",
        ];
    }
}
