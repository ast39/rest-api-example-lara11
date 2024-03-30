<?php

namespace App\Mail;

use App\Dto\NewReviewDto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class ReviewCreated extends Mailable {

    use Queueable, SerializesModels;


    private NewReviewDto $review;

    /**
     * Create a new message instance.
     */
    public function __construct(NewReviewDto $review)
    {
        $this->review = $review;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            to: $this->review->user->email,
            subject: 'Оставлен новый отзыв о товаре',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.review',
            with: [
                'item' => $this->review->item->title,
                'user' => $this->review->user->name,
                'rate' => $this->review->rate,
                'body' => $this->review->body,
            ]
        );
    }

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
