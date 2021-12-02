<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficialStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_students', function (Blueprint $table) {
            $table->id();
            $table->string('student_number', 40)->unique();
            $table->string('given_name', 30);
            $table->string('middle_name', 30);
            $table->string('last_name', 30);
            $table->string('gender', 20);
            $table->string('program');
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
        Schema::dropIfExists('official_students');
    }
}
