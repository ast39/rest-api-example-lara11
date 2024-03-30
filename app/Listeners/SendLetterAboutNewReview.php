<?php

namespace App\Listeners;

use App\Events\ReviewCreated;
use App\Jobs\NewReviewSendLetterJob;


class SendLetterAboutNewReview {

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
    public function handle(ReviewCreated $event): void
    {
        dispatch(new NewReviewSendLetterJob($event->review));
    }
}
