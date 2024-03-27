<?php

namespace Database\Seeders;

use App\Enums\EUserRole;
use App\Models\Role;;
use Illuminate\Database\Seeder;


class RoleSeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => EUserRole::ADMIN->value,
            'title' => ucfirst(strtolower(EUserRole::ADMIN->name)),
            'note'  => 'Администратор ресурса',
        ]);

        Role::create([
            'title' => EUserRole::MODERATOR->value,
            'note'  => ucfirst(strtolower(EUserRole::MODERATOR->name)),
        ]);

        Role::create([
            'title' => EUserRole::USER->value,
            'note'  => ucfirst(strtolower(EUserRole::USER->name)),
        ]);
    }
}
