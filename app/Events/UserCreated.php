<?php

namespace App\Events;

use App\Dto\NewUserDto;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class UserCreated {

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public NewUserDto $user;


    /**
     * Create a new event instance.
     */
    public function __construct(NewUserDto $user)
    {
        $this->user = $user;
    }
}
