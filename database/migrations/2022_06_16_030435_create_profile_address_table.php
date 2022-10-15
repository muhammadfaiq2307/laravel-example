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
        Schema::create('profile.address', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('urban');
            $table->string('district');
            $table->string('city');
            $table->string('province');
            $table->bigInteger('postal_code');
            $table->text('address_detail');
            $table->text('note');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')
            ->on('public.users')
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
        Schema::dropIfExists('profile.address');
    }
};
