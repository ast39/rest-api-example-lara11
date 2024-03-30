<?php

namespace App\Models;

use App\Models\Scopes\Filter\Filterable;
use App\Observers\ReviewObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


#[ObservedBy([ReviewObserver::class])]
class Review extends Model {

    use Filterable;


    protected $table         = 'reviews';

    protected $primaryKey    = 'id';

    protected $keyType       = 'int';

    public    $incrementing  = true;

    public    $timestamps    = true;


    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'review_images');
    }


    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }


    protected $with = [
        //
    ];

    protected $appends = [
        //
    ];

    protected $fillable = [
        'id', 'user_id', 'item_id', 'body', 'rate', 'status',
        'created_at', 'updated_at',
    ];

    protected $hidden = [];
}
