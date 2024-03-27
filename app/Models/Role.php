<?php

namespace App\Models;

use App\Models\Scopes\Filter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Role extends Model {

    use Filterable;


    protected $table = 'roles';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public    $incrementing = true;

    public    $timestamps = true;


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles');
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
        'id', 'title', 'note',
        'created_at', 'updated_at',
    ];

    protected $hidden = [
        //
    ];
}
