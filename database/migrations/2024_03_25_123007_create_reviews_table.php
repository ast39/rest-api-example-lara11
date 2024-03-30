<?php

use App\Enums\ERate;
use App\Enums\ESoftStatus;
use App\Http\ENums\SoftStatus;
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
        Schema::create('reviews', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('user_id')
                ->nullable(false)
                ->comment('Покупатель');

            $table->unsignedBigInteger('item_id')
                ->nullable(false)
                ->comment('Товар');

            $table->unsignedInteger('rate')
                ->default(ERate::EXCELLENT->value)
                ->comment('Оценка товара');

            $table->text('body')
                ->nullable()
                ->comment('Отзыв');

            $table->unsignedTinyInteger('status')
                ->default(ESoftStatus::ACTIVE->value)
                ->comment('Статус отзыва');

            $table->timestamps();

            $table->unique(['user_id', 'item_id']);

            $table->comment('Отзывы о товарах');

            $table->foreign('user_id', 'review_user_key')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('item_id', 'review_item_key')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function(Blueprint $table) {
            $table->dropForeign('review_user_key');
            $table->dropForeign('review_item_key');
        });

        Schema::dropIfExists('reviews');
    }
};
