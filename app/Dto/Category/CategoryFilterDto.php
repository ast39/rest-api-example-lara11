<?php

namespace App\Dto\Category;

use App\Dto\DtoClass;
use App\Enums\ESoftStatus;

class CategoryFilterDto extends DtoClass
{
    public ?string $q = null;
    public ?int $status = null;

    public function __construct(array $data)
    {
        parent::__construct($data);

        if (array_key_exists('q', $data)) {
            $this->q = $data['q'];
        }

        if (array_key_exists('status', $data)) {
            $this->status = ESoftStatus::tryFrom($data['status'])->value;
        }
    }
}
