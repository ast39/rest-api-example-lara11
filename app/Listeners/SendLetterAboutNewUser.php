<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Jobs\NewUserSendLetterJob;


class SendLetterAboutNewUser {

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        dispatch(new NewUserSendLetterJob($event->user));
    }
}
