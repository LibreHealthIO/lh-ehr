<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientFaceSheetsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates patient_face_sheets table. 
     * It stores the Face Sheet information from UI leaving the address field.
     * @author Priyanshu Sinha. <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_face_sheets', function (Blueprint $table) {
            $table->increments('id')->comment = "Autoincrement Primary Key";
	    $table->integer('pid')->unsigned()->comment = "Foreign key to patient_datas table."; 
	    $table->string('f_name', 100)->comment = "First Name of patient.";
	    $table->string('m_name', 100)->nullable()->comment = "Middle Name of Patient. Optional";
	    $table->string('l_name', 100)->comment = "Last Name of Patient";
	    $table->date('DOB')->comment = "Date of Birth of Patient.";
	    $table->string('marietal_status', 10)->comment = "Marietal Status of Patient.";
	    $table->string('license_id')->unique()->comment = "License Id of patient.";
	    $table->string('email')->unique()->comment = "Email Id of Patient";
	    $table->string('sex', 6)->comment = "Sex of Patient";
	    $table->text('billing_note')->nullable()->comment = "Billing Note";
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
        Schema::dropIfExists('patient_face_sheets');
    }
}
