<?php

namespace Database\Seeders;

use App\Enums\ESoftStatus;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder {

    public function run(): void
    {
        Review::query()->create([
            'user_id' => 3,
            'item_id' => 1,
            'body' => 'Test review',
            'rate' => 5,
            'status' => ESoftStatus::ACTIVE->value,
        ]);

        Review::query()->create([
            'user_id' => 4,
            'item_id' => 1,
            'body' => 'Test review',
            'rate' => 4,
            'status' => ESoftStatus::ACTIVE->value,
        ]);

        Review::query()->create([
            'user_id' => 3,
            'item_id' => 2,
            'body' => 'Test review',
            'rate' => 3,
            'status' => ESoftStatus::ACTIVE->value,
        ]);

        Review::query()->create([
            'user_id' => 4,
            'item_id' => 2,
            'body' => 'Test review',
            'rate' => 2,
            'status' => ESoftStatus::DISABLED->value,
        ]);
    }
}
