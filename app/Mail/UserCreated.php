<?php

namespace App\Mail;

use App\Dto\NewUserDto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class UserCreated extends Mailable {

    use Queueable, SerializesModels;


    private NewUserDto $user;

    /**
     * Create a new message instance.
     */
    public function __construct(NewUserDto $user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            to: $this->user->email,
            subject: 'Новый пользователь в системе',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.onboarding',
            with: [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => $this->user->password,
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
