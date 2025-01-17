<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgetpasswordMail extends Mailable
{
    use Queueable, SerializesModels;
   public $otp;
   public $user;
    /**
     * Create a new message instance.
     */
    public function __construct($otp, $user)
    {
    $this->otp=$otp;
    $this->user=$user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Forgetpassword Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->markdown('emails.forgetpassword');
    }

    // public function content(): Content
    // {
    //     return new Content(
    //         markdown: 'emails.forgetpassword',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
