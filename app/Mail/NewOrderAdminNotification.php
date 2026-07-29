<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function build()
    {
        $this->order->load('items', 'promoCode');

        return $this->subject("طلب جديد #{$this->order->order_number} - سوق")
            ->view('emails.new-order-admin');
    }
}