<?php

namespace App\Services;

use App\Interfaces\SendInterface;
use App\Mail\SendVerificationCodeMail;

class MailService implements SendInterface
{

    public function send($type, $data)
    {
        return match ($type) {
            'send_verification_code' => new SendVerificationCodeMail($data),
            default => throw new \Exception('Unknown mail type'),
        };
    }
}
