<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    public function __construct(public Product $product) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'low_stock',
            'product_id' => $this->product->id,
            'message'    => "Low stock alert: \"{$this->product->name_en}\" — {$this->product->stock_quantity} remaining",
            'url'        => "/admin/products/{$this->product->id}/edit",
        ];
    }
}
