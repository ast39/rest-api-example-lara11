<?php

namespace App\Jobs;

use App\Events\NewUserEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class SendEmailJob implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private array $details;

    /**
     * Create a new job instance.
     */
    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to('alexandr.statut@gmail.com')
            ->send(new NewUserEvent($this->details));
    }
}
