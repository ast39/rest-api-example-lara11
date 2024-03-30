<?php

namespace App\Jobs;

use App\Dto\NewReviewDto;
use App\Mail\ReviewCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class NewReviewSendLetterJob implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private NewReviewDto $review;

    /**
     * Create a new job instance.
     */
    public function __construct(NewReviewDto $review)
    {
        $this->review = $review;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::send(new ReviewCreated($this->review));
    }
}
