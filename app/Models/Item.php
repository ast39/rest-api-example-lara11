<?php

namespace App\Models;

use App\Models\Scopes\Filter\Filterable;
use App\Observers\ItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


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
        'id', 'article', 'title', 'body', 'category_id',
        'price', 'status',
        'created_at', 'updated_at',
    ];

    protected $hidden = [];
}
