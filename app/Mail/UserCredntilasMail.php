<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserCredntilasMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $password;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($email, $password, $loginUrl)
    {
        $this->email = $email;
        $this->password = $password;
        $this->loginUrl = $loginUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Temporary Login Credentials',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.UserCredntilasMail', // Blade template path
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
