<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone_no')->unique();
            $table->string('name');
            $table->integer('city_id');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->integer('no_students');
            $table->Integer('director_id')->nullable();
            $table->time('beginning_of_time')->default('00:00');
            $table->time('End_of_time')->default('00:00');
            $table->integer('no_buses');
             $table->softDeletes();
            $table->timestamps();
        });
        App\Models\School::create([
            'phone_no' => '+966585777777',
            'name' => 'balqees',
            'city_id' => 1,
            'no_students' => 3000,
            'no_buses' =>5 ,

        ]);
        App\Models\School::create([
            'phone_no' => '+966585777888',
            'name' => 'remal',
            'city_id' => 1,
            'no_students' => 3000,
            'no_buses' =>5 ,

        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
