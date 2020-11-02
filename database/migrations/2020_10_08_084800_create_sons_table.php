<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('gender');//male 1,female=2
            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->time('going')->default('00:00');
            $table->time('timereturn')->default('00:00');//when school is accept here don calculate
            $table->unsignedBigInteger('transport_id')->nullable();
            $table->integer('Is_agree')->default(0);//new=0,agree=1,disagree=2
            $table->softDeletes();
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
        Schema::dropIfExists('sons');
    }
}
