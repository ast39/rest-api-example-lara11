<?php

namespace App\Dto\Category;

use App\Dto\DtoClass;
use App\Enums\ESoftStatus;

class CategoryCreateDto extends DtoClass
{
    public string $title;
    public int $status;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->title = $data['title'];
        $this->status = ESoftStatus::tryFrom($data['status'])->value ?? ESoftStatus::ACTIVE->value;
    }
}
