<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Jobs\SendLetterJob;


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
        dispatch(new SendLetterJob($event->user));
    }
}
