<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use MailService;

class SendVerificationCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_email;
    protected $verification_code;

    /**
     * Create a new job instance.
     */
    public function __construct($user_email, $verification_code)
    {
        $this->user_email = $user_email;
        $this->verification_code = $verification_code;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user_email)->send(MailService::send('send_verification_code', $this->verification_code));
            Log::channel('mail')->info("SendVerificationCode: Verification email successfully sent to {$this->user_email}");
        } catch (\Exception $e) {
            Log::channel('mail')->error("SendVerificationCode: Failed to send verification email to {$this->user_email}: " . $e->getMessage());
        }
    }
}
