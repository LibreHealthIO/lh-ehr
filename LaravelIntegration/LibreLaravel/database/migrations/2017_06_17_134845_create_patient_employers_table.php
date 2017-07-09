<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientEmployersTable extends Migration
{
    /**
     * Run the migrations.
     * Creates patient_employers table. Previously employer_data.
     * Stores the patient's employer information. One to one relationship with patient_data and one to many relationship with addresses.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_employers', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
            $table->integer('pid')->unsigned()->comment = "Foreign key to patient_datas table.";
	    $table->integer('addressId')->unsigned()->comment = "To create link with addresses table.";
	    $table->string('name', 100)->comment = "Employers Name";
	    $table->foreign('addressId')->references('id')->on('addresses')->onDelete('cascade'); //Create Link
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
        Schema::dropIfExists('patient_employers');
    }
}
