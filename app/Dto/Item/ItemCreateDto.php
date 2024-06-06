<?php

namespace App\Dto\Item;

use App\Dto\DtoClass;
use App\Enums\EItemStatus;

class ItemCreateDto extends DtoClass {

    public string $article;
    public string $title;
    public string $body;
    public int $category_id;
    public float $price;
    public int $status;
    public array $images;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->article = $data['article'];
        $this->title = $data['title'];
        $this->body = $data['body'] ?? '';
        $this->category_id = $data['category_id'];
        $this->price = $data['price'] ?? 0;
        $this->status = EItemStatus::tryFrom($data['status'])->value ?? EItemStatus::AVAILABLE->value;
        $this->images = $data['images'] ?? [];
    }
}
