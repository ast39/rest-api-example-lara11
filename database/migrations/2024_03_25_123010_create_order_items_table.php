<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {

            $table->unsignedBigInteger('order_id')
                ->index()
                ->comment('ID заказа');

            $table->unsignedBigInteger('item_id')
                ->index()
                ->comment('ID товара');

            $table->decimal('price')
                ->comment('Цена на момент заказа');

            $table->comment('Pivot составы заказов');

            $table->foreign('order_id', 'oi_order_key')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('item_id', 'oi_item_key')
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
        Schema::table('review_images', function(Blueprint $table) {
            $table->dropForeign('oi_order_key');
            $table->dropForeign('oi_item_key');
        });

        Schema::dropIfExists('order_items');
    }
};
