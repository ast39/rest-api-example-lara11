<?php

use App\Enums\ESoftStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('title')
                ->comment('Название категории');

            $table->unsignedTinyInteger('status')
                ->default(ESoftStatus::ACTIVE->value)
                ->comment('Статус категории');

            $table->timestamps();

            $table->comment('Категории товаров');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
