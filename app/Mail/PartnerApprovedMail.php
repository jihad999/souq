<?php

namespace App\Mail;

use App\Models\Partner;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PartnerApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Partner $partner) {}

    public function build()
    {
        return $this->subject('تهانينا! تمت الموافقة على طلب شراكتكم - سوق')
            ->view('emails.partner-approved');
    }
}