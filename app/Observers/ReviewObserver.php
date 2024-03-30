<?php

namespace App\Observers;

use App\Models\Review;
use Illuminate\Support\Facades\Storage;


class ReviewObserver {

    public function deleting(Review $review): void
    {
        foreach ($review->images as $image) {
            $filePath = $image->full_path;

            if (Storage::disk(env('STORAGE_DRIVER_FOR_IMAGES'))->exists($filePath)) {
                Storage::disk(env('STORAGE_DRIVER_FOR_IMAGES'))->delete($filePath);
            }
        }
    }
}
