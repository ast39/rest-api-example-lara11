<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    public function up(): void
    {
        Schema::create('order_logs', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('order_id')
                ->comment('ID заказа');

            $table->unsignedBigInteger('status_id')
                ->comment('ID статуса');

            $table->timestamps();

            $table->comment('Лог выполнения заказов');

            $table->foreign('order_id', 'ol_order_key')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_logs', function(Blueprint $table) {
            $table->dropForeign('ol_order_key');
        });

        Schema::dropIfExists('order_logs');
    }
};
