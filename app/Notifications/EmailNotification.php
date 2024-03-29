<?php

namespace App\Notifications;

use App\Dto\NewUserDto;
use App\Events\NewUserEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;


class EmailNotification extends Mailable {

    use Queueable, SerializesModels;


    private NewUserDto $user;

    /**
     * Create a new event instance.
     */
    public function __construct(NewUserDto $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return EmailNotification
     */
    public function build(): EmailNotification
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Новый пользователь в системе')
            ->view('email.onboarding')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => $this->user->password,
            ]);
    }
}
