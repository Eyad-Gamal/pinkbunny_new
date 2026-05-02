<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "فاتورة أوردر #{$this->order->order_number} — Pink Bunny 🐰",
        );
    }

    public function content(): Content
    {
        $this->order->loadMissing(['user', 'items', 'address']);

        return new Content(
            view: 'emails.order-invoice',
            with: ['order' => $this->order],
        );
    }
}
