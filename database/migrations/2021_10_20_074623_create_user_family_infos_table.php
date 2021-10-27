<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFamilyInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_family_infos', function (Blueprint $table) {
            $table->id();
            $table->string('fathers_first_name', 50);
            $table->string('fathers_middle_name', 50);
            $table->string('fathers_last_name', 50);
            $table->string('mothers_first_name', 50);
            $table->string('mothers_maiden_name', 50);
            $table->string('mothers_last_name', 50);
            $table->string('fathers_monthly_salary', 30);
            $table->string('mothers_monthly_salary', 30);
            $table->string('siblings_monthly_salary', 30);
            $table->string('dswd_household_number', 40);
            $table->integer('house_member');
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
        Schema::dropIfExists('user_family_infos');
    }
}
