<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('review_images', function (Blueprint $table) {

            $table->unsignedBigInteger('image_id')
                ->index()
                ->comment('ID файла');

            $table->unsignedBigInteger('review_id')
                ->index()
                ->comment('ID отзыва');

            $table->comment('Pivot изображения товаров');

            $table->foreign('image_id', 'ri_image_key')
                ->references('id')
                ->on('images')
                ->onDelete('cascade');

            $table->foreign('review_id', 'ri_review_key')
                ->references('id')
                ->on('reviews')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_images', function(Blueprint $table) {
            $table->dropForeign('ri_file_key');
            $table->dropForeign('ri_review_key');
        });

        Schema::dropIfExists('review_images');
    }
};
