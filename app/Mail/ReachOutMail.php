<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReachOutMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;

    public function __construct(string $name = null)
    {
        $this->name = $name ?? '';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Request Has Been Received — Group Resilience',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reach_out_alert',
            with: [
                'name' => $this->name,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

