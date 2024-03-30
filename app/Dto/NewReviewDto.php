<?php

namespace App\Dto;

use App\Models\Item;
use App\Models\User;


readonly class NewReviewDto extends \App\Dto\NewUserDto
{

    public ?User $user;

    public ?Item $item;

    public int $rate;

    public string $body;


    public function __construct(array $data)
    {
        $this->user = $data['user'] ?? null;
        $this->item = $data['item'] ?? null;
        $this->rate = $data['rate'] ?? 0;
        $this->body = $data['body'] ?? 'Не указано';
    }
}
