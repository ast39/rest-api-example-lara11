<?php

namespace Database\Seeders;

use App\Enums\ESoftStatus;
use App\Models\Category;
use Illuminate\Database\Seeder;


class CategorySeeder extends Seeder {

    public function run(): void
    {
        Category::query()->create([
            'title' => 'Первая категория',
            'status' => ESoftStatus::ACTIVE->value
        ]);

        Category::query()->create([
            'title' => 'Вторая категория',
            'status' => ESoftStatus::ACTIVE->value
        ]);

        Category::query()->create([
            'title' => 'Третья категория',
            'status' => ESoftStatus::ACTIVE->value
        ]);

        Category::query()->create([
            'title' => 'Четвертая категория',
            'status' => ESoftStatus::DISABLED->value
        ]);
    }
}
