<?php

namespace App\Models\Scopes;

use App\Models\Scopes\Filter\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;


class UserScope extends AbstractFilter {

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
        $builder->where(function($query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%')
                ->orWhere('email', 'like', '%' . $value . '%');
        });
    }

    public function status(Builder $builder, $value): void
    {
        $builder->where('status', $value);
    }
}
