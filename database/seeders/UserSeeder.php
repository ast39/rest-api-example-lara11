<?php

namespace Database\Seeders;

use App\Enums\ESoftStatus;
use App\Enums\EUserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder {

    public function run(): void
    {
        $user = User::create([
            'name' => 'Администратор',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin'),
            'status' => ESoftStatus::ACTIVE->value,
        ]);
        $user->roles()->attach([
            EUserRole::ADMIN->value,
            EUserRole::MODERATOR->value,
            EUserRole::USER->value,
        ]);

        $user = User::create([
            'name' => 'Модератор',
            'email' => 'employee@test.com',
            'password' => Hash::make('employee'),
            'status' => ESoftStatus::ACTIVE->value,
        ]);
        $user->roles()->attach([
            EUserRole::MODERATOR->value,
            EUserRole::USER->value,
        ]);

        $user = User::create([
            'name' => 'Пользователь 1',
            'email' => 'user1@test.com',
            'password' => Hash::make('user'),
            'status' => ESoftStatus::ACTIVE->value,
        ]);
        $user->roles()->attach([
            EUserRole::USER->value,
        ]);

        $user = User::create([
            'name' => 'Пользователь 2',
            'email' => 'user2@test.com',
            'password' => Hash::make('user'),
            'status' => ESoftStatus::DISABLED->value,
        ]);
        $user->roles()->attach([
            EUserRole::USER->value,
        ]);
    }
}
