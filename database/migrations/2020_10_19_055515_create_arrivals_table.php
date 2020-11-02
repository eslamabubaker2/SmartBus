<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArrivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrivals', function (Blueprint $table) {
            $table->id();
            $table->string('name_day_ar');
            $table->string('name_day_en');
            $table->time('going');
            $table->time('timereturn');
            $table->string('date');
            $table->integer('Sure_go')->default(0);//1 done
            $table->integer('Sure_return')->default(0);//1 done
            $table->integer('cancel_arrive')->default(0);//if 1 is cancelled
            $table->unsignedBigInteger('transport_id');
            $table->unsignedBigInteger('son_id');
            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('son_id')
                ->references('id')
                ->on('sons')
                ->onDelete('cascade');



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
        Schema::dropIfExists('arrivals');
    }
}
