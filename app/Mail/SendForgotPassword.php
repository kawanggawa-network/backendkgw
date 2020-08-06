<?php

namespace App\Mail;

use App\Models\ForgotPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendForgotPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Forgot password object.
     *
     * @var \App\Models\ForgotPassword
     */
    protected $forgotPasswordObject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ForgotPassword $object)
    {
        $this->forgotPasswordObject = $object;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Reset Password Request";

        return $this->view('mail.forgot-password')->with([
            'object' => $this->forgotPasswordObject
        ])->subject($subject);
    }
}
