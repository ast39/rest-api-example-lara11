<?php

namespace App\Models;

use App\Enums\EUserRole;
use App\Models\Scopes\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable {

    use HasFactory, Notifiable, HasApiTokens, Filterable;


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function isAdmin(): bool
    {
        return in_array(EUserRole::ADMIN->name, array_map(function($role) {
            return $role['title'];
        }, $this->roles?->toArray() ?: []));
    }

    public function isModerator(): bool
    {
        return in_array(EUserRole::MODERATOR->name, array_map(function($role) {
            return $role['title'];
        }, $this->roles?->toArray() ?: []));
    }

    public function isUser(): bool
    {
        return in_array(EUserRole::USER->name, array_map(function($role) {
            return $role['title'];
        }, $this->roles?->toArray() ?: []));
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
