<?php

namespace App\Dto\Item;

use App\Dto\DtoClass;
use App\Enums\EItemStatus;

class ItemUpdateDto extends DtoClass {

    public ?string $article = null;
    public ?string $title = null;
    public ?string $body = null;
    public ?int $category_id = null;
    public ?float $price = null;
    public ?int $status = null;
    public ?array $images = null;

    public function __construct(array $data)
    {
        parent::__construct($data);

        if (array_key_exists('article', $data)) {
            $this->article = $data['article'];
        }

        if (array_key_exists('title', $data)) {
            $this->title = $data['title'];
        }

        if (array_key_exists('body', $data)) {
            $this->body = $data['body'];
        }

        if (array_key_exists('category_id', $data)) {
            $this->category_id = $data['category_id'];
        }

        if (array_key_exists('price', $data)) {
            $this->price = $data['price'];
        }

        if (array_key_exists('status', $data)) {
            $this->status = EItemStatus::tryFrom($data['status'])->value;
        }

        if (array_key_exists('images', $data)) {
            $this->images = $data['images'];
        }
    }
}
