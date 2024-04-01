<?php

namespace App\Models\Scopes;

use App\Models\Scopes\Filter\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;


class OrderScope extends AbstractFilter {

    public const Q = 'q';

    public const USER = 'user';
    public const STATUS = 'status';

    /**
     * @return array[]
     */
    protected function getCallbacks(): array
    {
        return [

            self::Q => [$this, 'q'],
            self::USER => [$this, 'user'],
            self::STATUS => [$this, 'status'],
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

    public function status(Builder $builder, $value): void
    {
        $builder->whereIn('status_id', $value);
    }
}
