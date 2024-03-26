<?php

namespace App\Models\Scopes;

use App\Models\Scopes\Filter\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;


class CategoryScope extends AbstractFilter {

    public const Q = 'q';

    public const STATUS = 'status';

    /**
     * @return array[]
     */
    protected function getCallbacks(): array
    {
        return [

            self::Q => [$this, 'q'],
            self::STATUS => [$this, 'status'],
        ];
    }

    public function q(Builder $builder, $value): void
    {
        $builder->where('title', 'like', '%'.$value.'%');
    }

    public function status(Builder $builder, $value): void
    {
        $builder->where('status', $value);
    }
}
