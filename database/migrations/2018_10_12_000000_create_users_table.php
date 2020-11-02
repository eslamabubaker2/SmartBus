<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('secondname');
            $table->string('phone_no')->unique();
            $table->string('password');
            $table->string('image')->default('user.png');
            $table->string('code')->nullable();
            $table->integer('state')->default('0');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('text_adress')->nullable();
            //user udriver
            $table->string('Driving_License')->nullable();
            $table->string('Certificate_good_conduct')->nullable();
            $table->integer('no_bus')->unique()->nullable();
            //
            $table->integer('role');//school=1,driver=2,parent=3
            $table->string('watsapp')->nullable();
            $table->time('beginning_of_time')->format('%H:%i')->nullable();
            $table->time('End_of_time')->format('%H:%i')->nullable();
            $table->string('fcm_token')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')
            ->onDelete('cascade');
            $table->unsignedBigInteger('transport_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('schoobus_id')->nullable();//المدير الى ضاف الباص

            $table->date('beginning_semester')->nullable();


            $table->softDeletes();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
