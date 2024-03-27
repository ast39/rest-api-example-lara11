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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')
                ->index()
                ->comment('ID пользователя');

            $table->unsignedBigInteger('role_id')
                ->index()
                ->comment('ID роли');

            $table->comment('Pivot роли пользователей');

            $table->foreign('user_id', 'ur_user_key')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('role_id', 'ur_role_key')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_roles', function(Blueprint $table) {
            $table->dropForeign('ur_user_key');
            $table->dropForeign('ur_role_key');
        });

        Schema::dropIfExists('user_roles');
    }
};
