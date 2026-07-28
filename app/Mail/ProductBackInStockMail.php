<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductBackInStockMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Product $product) {}

    public function build()
    {
        return $this->subject('المنتج اللي كنت بتنتظره صار متوفر! 🎉')
            ->view('emails.product-back-in-stock');
    }
}