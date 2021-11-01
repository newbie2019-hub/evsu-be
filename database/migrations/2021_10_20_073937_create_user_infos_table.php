<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 30);
            $table->string('middle_name', 30);
            $table->string('last_name', 30);
            $table->string('contact_number', 30);
            $table->string('street', 200);
            $table->string('program');
            $table->string('year_level');
            $table->string('marital_status', 30);
            $table->string('barangay');
            $table->string('town', 150)->nullable();
            $table->string('province', 150)->nullable();
            $table->integer('zipcode');
            $table->boolean('has_disability');
            $table->string('gender', 20);
            $table->string('birthday', 50);
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
        Schema::dropIfExists('user_infos');
    }
}
