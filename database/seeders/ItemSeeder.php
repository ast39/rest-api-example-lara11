<?php

namespace Database\Seeders;

use App\Enums\ESoftStatus;
use App\Models\Item;
use Illuminate\Database\Seeder;


class ItemSeeder extends Seeder {

    public function run(): void
    {
        Item::query()->create([
            'article' => 'ABCDEF',
            'title' => 'Test item 1',
            'body' => 'Test item 1',
            'category_id' => 1,
            'price' => 500,
            'status' => ESoftStatus::ACTIVE->value,
        ]);

        Item::query()->create([
            'article' => 'ASDFGH',
            'title' => 'Test item 2',
            'body' => 'Test item 2',
            'category_id' => 2,
            'price' => 600,
            'status' => ESoftStatus::ACTIVE->value,
        ]);

        Item::query()->create([
            'article' => 'QWERTY',
            'title' => 'Test item 3',
            'body' => 'Test item 3',
            'category_id' => 1,
            'price' => 700,
            'status' => ESoftStatus::ACTIVE->value,
        ]);

        Item::query()->create([
            'article' => 'ZXCVBN',
            'title' => 'Test item 4',
            'body' => 'Test item 4',
            'category_id' => 3,
            'price' => 800,
            'status' => ESoftStatus::DISABLED->value,
        ]);
    }
}
