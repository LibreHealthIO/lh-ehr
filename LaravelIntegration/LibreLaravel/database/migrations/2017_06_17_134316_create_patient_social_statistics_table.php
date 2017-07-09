<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientSocialStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates patient_social_statistics table.
     * This stores patient social information. One to one relationship with patient_datas table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_social_statistics', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary key Autoincrement.";
	    $table->integer('pid')->unsigned()->comment = "Foreign key to patient_datas table.";
	    $table->string('ethnicity')->comment = "Ethnicity.";
	    $table->string('religion')->comment = "Religion";
	    $table->string('interpreter')->comment = "Interpreter";
	    $table->string('migrant_seasonal')->comment = "Whetehr Migrant or Seasonal?";
	    $table->integer('family_size')->comment = "Family Size of Patient";
	    $table->decimal('monthly_income', 5, 2)->comment = "Mothly income of patient.";
	    $table->boolean('homeless')->default(0)->comment = "Is homeless or not? 0 -> No | 1 -> Yes";
	    $table->dateTime('financial_review')->comment = "Financial Review Date";
	    $table->string('language', 100)->comment = "Language.";
            $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade'); //Foreign key
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
