<?php

use App\Enums\ESoftStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('article')
                ->comment('Артикул товара');

            $table->string('title')
                ->comment('Название товара');

            $table->text('body')
                ->default(null)
                ->nullable()
                ->comment('Описание товара');

            $table->unsignedBigInteger('category_id')
                ->nullable(false)
                ->comment('Категория товара');

            $table->decimal('price', 8, 2)
                ->nullable(false)
                ->comment('Цена');

            $table->unsignedTinyInteger('status')
                ->default(ESoftStatus::ACTIVE->value)
                ->comment('Статус товара');

            $table->timestamps();

            $table->comment('Каталог товаров');

            $table->foreign('category_id', 'item_category_key')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function(Blueprint $table) {
            $table->dropForeign('item_category_key');
        });

        Schema::dropIfExists('categories');
    }
};
