<?php

namespace App\Dto;

class DtoClass {

    public ?int $page = null;
    public ?int $limit = null;
    public ?string $order = null;
    public ?string $reverse = null;

    public function __construct(array $data)
    {
        if (array_key_exists('page', $data)) {
            $this->page = $data['page'];
        }

        if (array_key_exists('limit', $data)) {
            $this->limit = $data['limit'];
        }

        if (array_key_exists('order', $data)) {
            $this->order = $data['order'];
        }

        if (array_key_exists('reverse', $data)) {
            $this->reverse = $data['reverse'];
        }
    }

    public function toArray(): array
    {
        return collect($this)
            ->filter(function ($value) {
                return !is_null($value);
            })
            ->toArray();
    }
}
