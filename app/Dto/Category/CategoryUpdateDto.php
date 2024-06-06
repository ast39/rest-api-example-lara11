<?php

namespace App\Dto\Category;

use App\Dto\DtoClass;
use App\Enums\ESoftStatus;

class CategoryUpdateDto extends DtoClass
{
    public ?string $title;
    public ?int $status;

    public function __construct(array $data)
    {
        parent::__construct($data);

        if (array_key_exists('title', $data)) {
            $this->title = $data['title'];
        }

        if (array_key_exists('status', $data)) {
            $this->status = ESoftStatus::tryFrom($data['status'])->value;
        }
    }
}
