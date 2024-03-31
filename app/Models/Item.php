<?php

namespace App\Models;

use App\Models\Scopes\Filter\Filterable;
use App\Observers\ItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


#[ObservedBy([ItemObserver::class])]
class Item extends Model {

    use Filterable;


    protected $table         = 'items';

    protected $primaryKey    = 'id';

    protected $keyType       = 'int';

    public    $incrementing  = true;

    public    $timestamps    = true;


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'item_images');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'item_id', 'id');
    }

    public function getRatingAttribute(): float
    {
        $reviews = $this->reviews()->get();
        $counter = $reviews->count();
        $rating = 0;

        if ($counter == 0) {
            return 0;
        }

        $rating = array_sum(
            array_map(function($review) {
                return $review['rate'];
            }, $reviews->toArray())
        );

        return round($rating / $counter, 2);
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
        'rating',
    ];

    protected $fillable = [
        'id', 'article', 'title', 'body', 'category_id',
        'price', 'status',
        'created_at', 'updated_at',
    ];

    protected $hidden = [];
}
