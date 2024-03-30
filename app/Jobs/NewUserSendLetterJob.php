<?php

namespace App\Jobs;

use App\Dto\NewUserDto;
use App\Mail\UserCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class NewUserSendLetterJob implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private NewUserDto $user;

    /**
     * Create a new job instance.
     */
    public function __construct(NewUserDto $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::send(new UserCreated($this->user));
    }
}
