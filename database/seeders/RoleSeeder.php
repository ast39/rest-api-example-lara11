<?php

namespace Database\Seeders;

use App\Enums\EUserRole;
use App\Models\Role;;
use Illuminate\Database\Seeder;


class RoleSeeder extends Seeder {

    public function run(): void
    {
        Role::create([
            'title' => EUserRole::ADMIN->name,
            'note'  => 'Администратор ресурса',
        ]);

        Role::create([
            'title' => EUserRole::MODERATOR->name,
            'note'  => 'Модератор ресурса',
        ]);

        Role::create([
            'title' => EUserRole::USER->name,
            'note'  => 'Пользователь ресурса',
        ]);
    }
}
