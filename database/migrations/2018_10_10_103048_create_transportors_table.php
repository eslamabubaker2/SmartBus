<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transportors', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('no_bus')->unique();
            $table->unsignedBigInteger('driver_id')->nullable();//follow to driver
            $table->string('start_latitude');//start point to go to bus
            $table->string('start_longitude');
            $table->string('move_latitude')->nullable();
            $table->string('move_longitude')->nullable();
            $table->unsignedBigInteger('schoobus_id')->nullable();//follow to directore that add him
            $table->text('text_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transportors');
    }
}
