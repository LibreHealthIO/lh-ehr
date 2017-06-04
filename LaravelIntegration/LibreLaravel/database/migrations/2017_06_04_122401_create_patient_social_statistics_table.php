<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientSocialStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     * Create patient_social_statistics to store social information. One to one relationship with patinet_data.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_social_statistics', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('ethnicity');
	    $table->string('religion');
	    $table->string('interpreter');
	    $table->string('migrant_seasonal');
	    $table->integer('family_size');
	    $table->decimal('monthly_income', 5, 2);
	    $table->boolean('homeless');
	    $table->dateTime('financial_review');
	    $table->string('language', 100);
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
        Schema::dropIfExists('patient_social_statistics');
    }
}
