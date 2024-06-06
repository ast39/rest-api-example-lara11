<?php

namespace App\Dto\Item;

use App\Dto\DtoClass;
use App\Enums\EItemStatus;

class ItemFilterDto extends DtoClass {

    public ?string $q = null;
    public ?array $category = null;
    public ?int $status = null;

    public function __construct(array $data)
    {
        parent::__construct($data);

        if (array_key_exists('q', $data)) {
            $this->q = $data['q'];
        }

        if (array_key_exists('category', $data)) {
            $this->category = $data['category'];
        }

        if (array_key_exists('status', $data)) {
            $this->status = EItemStatus::tryFrom($data['status'])->value;
        }
    }
}
