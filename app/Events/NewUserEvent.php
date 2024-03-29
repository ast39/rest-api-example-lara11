<?php

namespace App\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class NewUserEvent  extends Mailable {

    use Queueable, SerializesModels;


    private array $content;

    /**
     * Create a new event instance.
     */
    public function __construct(array $content)
    {
        $this->content = $content;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return NewUserEvent
     */
    public function build(): NewUserEvent
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Новый пользователь в системе')
            ->view('email.onboarding')
            ->with([
                'name' => $this->content['name']  ?? '',
                'email' => $this->content['email']  ?? '',
                'password' => $this->content['password']   ?? '',
            ]);
    }
}
