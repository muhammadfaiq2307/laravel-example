<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system.role_main_menu', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('auth_item_id');
            $table->bigInteger('main_menu_id');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('auth_item_id')
            ->references('id')
            ->on('system.auth_item')
            ->onDelete('cascade');

            $table->foreign('main_menu_id')
            ->references('id')
            ->on('system.main_menu')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system.role_main_menu');
    }
};
