<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public ?string $ctaUrl = null,
    ) {
        $this->order->loadMissing('items');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kim Loan Cake - Xác nhận thanh toán #' . $this->order->order_code
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.paid',
            with: [
                'order' => $this->order,
                'items' => $this->order->items,
                'ctaUrl' => $this->ctaUrl ?: url('/profile/orders/' . $this->order->order_code),
            ],
        );
    }
}
