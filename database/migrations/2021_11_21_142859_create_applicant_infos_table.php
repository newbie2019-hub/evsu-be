<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_infos', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('first_name', 30);
            $table->string('middle_name', 30)->nullable();
            $table->string('last_name', 30);
            $table->string('ext_name', 30)->nullable();
            $table->string('contact_number', 11);
            $table->string('contact_number2', 11);
            $table->string('program');
            $table->string('year_level');
            $table->string('tes_award')->nullable();
            $table->string('tes_application_number')->nullable();
            $table->string('tes_grant_type')->nullable();
            // $table->string('academic_units');
            // $table->string('gwa');
            $table->string('street', 200);
            $table->string('barangay');
            $table->string('town', 150)->nullable();
            $table->string('province', 150)->nullable();
            $table->string('zipcode');
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
        Schema::dropIfExists('applicant_infos');
    }
}
