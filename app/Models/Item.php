<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Item extends Model {

    protected $table = 'items';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public    $incrementing = true;

    public    $timestamps = true;


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
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
        'id', 'category_id', 'title', 'body', 'price', 'status',
        'created_at', 'updated_at',
    ];

    protected $hidden = [];
}
