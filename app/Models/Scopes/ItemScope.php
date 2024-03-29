<?php

namespace App\Models\Scopes;

use App\Models\Scopes\Filter\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;


class ItemScope extends AbstractFilter {

    public const Q = 'q';

    public const CATEGORY = 'category';
    public const STATUS = 'status';

    /**
     * @return array[]
     */
    protected function getCallbacks(): array
    {
        return [

            self::Q => [$this, 'q'],
            self::CATEGORY => [$this, 'category'],
            self::STATUS => [$this, 'status'],
        ];
    }

    public function q(Builder $builder, $value): void
    {
        $builder->where(function($query) use ($value) {
            $query->where('article', 'like', '%' . $value . '%')
                ->orWhere('title', 'like', '%' . $value . '%')
                ->orWhere('body', 'like', '%' . $value . '%');
        });
    }

    public function category(Builder $builder, $value): void
    {
        $builder->whereIn('category_id', $value);
    }

    public function status(Builder $builder, $value): void
    {
        $builder->where('status', $value);
    }
}
