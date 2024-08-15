<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendVerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verification_code;
    /**
     * Create a new message instance.
     */
    public function __construct($verification_code)
    {
        $this->verification_code = $verification_code;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Verification Code')
            ->view('emails.send_verification_code')
            ->with([
                'verification_code' => $this->verification_code,
            ]);
    }
}
