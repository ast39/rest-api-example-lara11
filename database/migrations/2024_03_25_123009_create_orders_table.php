<?php

use App\Enums\EOrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('user_id')
                ->nullable(false)
                ->comment('Покупатель');

            $table->string('body')
                ->comment('Комментарий к заказу');

            $table->unsignedTinyInteger('status_id')
                ->default(EOrderStatus::CREATED->value)
                ->comment('Статус заказа');

            $table->timestamps();

            $table->comment('Заказы');

            $table->foreign('user_id', 'order_user_key')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function(Blueprint $table) {
            $table->dropForeign('order_user_key');
        });

        Schema::dropIfExists('orders');
    }
};
