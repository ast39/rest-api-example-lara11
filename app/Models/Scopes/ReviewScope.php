<?php

namespace App\Models\Scopes;

use App\Models\Scopes\Filter\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;


class ReviewScope extends AbstractFilter {

    public const Q = 'q';

    public const USER = 'user';
    public const ITEM = 'item';

    /**
     * @return array[]
     */
    protected function getCallbacks(): array
    {
        return [

            self::Q => [$this, 'q'],
            self::USER => [$this, 'user'],
            self::ITEM => [$this, 'item'],
        ];
    }

    public function q(Builder $builder, $value): void
    {
        $builder->where('body', 'like', '%' . $value . '%');
    }

    public function user(Builder $builder, $value): void
    {
        $builder->whereIn('user_id', $value);
    }

    public function item(Builder $builder, $value): void
    {
        $builder->whereIn('item_id', $value);
    }
}
