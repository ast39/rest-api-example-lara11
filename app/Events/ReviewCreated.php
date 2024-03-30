<?php

namespace App\Events;

use App\Dto\NewReviewDto;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class ReviewCreated {

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public NewReviewDto $review;


    /**
     * Create a new event instance.
     */
    public function __construct(NewReviewDto $review)
    {
        $this->review = $review;
    }
}
