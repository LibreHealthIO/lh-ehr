<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientFaceSheetsTable extends Migration
{
    /**
     * Run the migrations.
     * Create patient_face_sheets table. This table contains the basic information of patient.
     * One-to-one relationship with patient as each patinet will have its own information.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */

    public function up()
    {
        Schema::create('patient_face_sheets', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('f_name', 100);
	    $table->string('m_name', 100)->nullable();
	    $table->string('l_name', 100);
	    $table->date('DOB');
	    $table->string('marietal_status', 10);
	    $table->string('license_id')->unique();
	    $table->string('email')->unique();
	    $table->string('sex', 6);
	    $table->text('billing_note')->nullable;
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
