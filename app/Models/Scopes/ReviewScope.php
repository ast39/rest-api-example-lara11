<?php

namespace App\Models\Scopes;

use App\Models\Scopes\Filter\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;


class ReviewScope extends AbstractFilter {

    public const Q = 'q';

    public const USER = 'users';
    public const ITEM = 'items';
    public const STATUS = 'status';

    /**
     * @return array[]
     */
    protected function getCallbacks(): array
    {
        return [

            self::Q => [$this, 'q'],
            self::USER => [$this, 'users'],
            self::ITEM => [$this, 'items'],
            self::STATUS => [$this, 'status'],
        ];
    }

    public function q(Builder $builder, $value): void
    {
        $builder->where('body', 'like', '%' . $value . '%');
    }

    public function users(Builder $builder, $value): void
    {
        $builder->whereIn('user_id', $value);
    }

    public function items(Builder $builder, $value): void
    {
        $builder->whereIn('item_id', $value);
    }

    public function status(Builder $builder, $value): void
    {
        $builder->where('status', $value);
    }
}
