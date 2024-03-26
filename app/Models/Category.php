<?php

namespace App\Models;

use App\Models\Scopes\Filter\Filterable;
use Illuminate\Database\Eloquent\Model;


class Category extends Model {

    use Filterable;


    protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public    $incrementing = true;

    public    $timestamps = true;


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
        'id', 'title', 'status',
        'created_at', 'updated_at',
    ];

    protected $hidden = [];
}
