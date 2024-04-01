<?php

namespace App\Models;

use App\Models\Scopes\Filter\Filterable;
use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


#[ObservedBy([OrderObserver::class])]
class Order extends Model {

    use Filterable;


    protected $table         = 'orders';

    protected $primaryKey    = 'id';

    protected $keyType       = 'int';

    public    $incrementing  = true;

    public    $timestamps    = true;


    public function getVolumeAttribute(): int
    {
        return $this->items()->count();
    }

    public function getAmountAttribute(): float
    {
        return array_sum(
            array_map(function($item) {
                return $item['pivot']['price'];
            }, $this->items()->getResults()->toArray())
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'order_items')
            ->withPivot('price');
    }

    public function log(): HasMany
    {
        return $this->hasMany(OrderLog::class, 'order_id', 'id')
            ->orderBy('created_at');
    }


    protected $with = [
        //
    ];

    protected $appends = [
        'volume',
        'amount',
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'id', 'body', 'user_id', 'status_id',
        'created_at', 'updated_at',
    ];

    protected $hidden = [];
}
