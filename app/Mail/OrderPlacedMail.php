<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Recipient type: 'customer' (order confirmation) or 'shop' (new order alert).
     */
    public string $recipientType;

    public function __construct(Order $order, string $recipientType = 'customer')
    {
        $this->order = $order;
        $this->recipientType = in_array($recipientType, ['customer', 'shop'], true)
            ? $recipientType
            : 'customer';
    }

    public function build(): self
    {
        $orderNumber = $this->order->order_number;

        $subject = $this->recipientType === 'shop'
            ? "New order received — {$orderNumber}"
            : "Your order is confirmed — {$orderNumber}";

        return $this
            ->subject($subject)
            ->view('emails.order_placed');
    }
}
