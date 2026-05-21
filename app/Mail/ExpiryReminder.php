<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExpiryReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $messageText;
    public $entityName;
    public $itemType;
    public $expiryDate;
    public $daysLeft;
    public $isExpired;
    public $redirectPath;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $title,
        string $messageText,
        string $entityName,
        string $itemType,
        $expiryDate,
        int $daysLeft,
        bool $isExpired,
        string $redirectPath = '/admin/dashboard'
    ) {
        $this->title = $title;
        $this->messageText = $messageText;
        $this->entityName = $entityName;
        $this->itemType = $itemType;
        $this->expiryDate = $expiryDate;
        $this->daysLeft = $daysLeft;
        $this->isExpired = $isExpired;
        $this->redirectPath = $redirectPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $prefix = $this->isExpired ? '⚠️ URGENT ALERT' : 'Reminder';
        return new Envelope(
            subject: "Mwigito Excel [{$prefix}]: {$this->entityName} - {$this->itemType}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.expiries.reminder',
            with: [
                'url' => route('login.force', ['redirect_to' => $this->redirectPath]),
            ],
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
