<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientDatasTable extends Migration
{
    /**
     * Run the migrations.
     * Create patient_data table. It has link with address table, patient_face_sheets table, patient_social_statistics and patient_employer_data.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_datas', function (Blueprint $table) {
            $table->increments('id');
	    $table->integer('pid')->unique(); //pid is patient id. More reasonable in case of medical information.
	    $table->string('title', 5); //title viz Mr., Ms., Mrs., etc.
	    $table->string('occupation'); //occupation of patient.
	    $table->string('industry'); //industry in which patient work.
	    $table->integer('addressId')->unsigned(); //This will be used as foreign key for address linking to patient.
	    $table->integer('faceSheetId')->unsigned(); //This will be used as foreign key for facesheet linking.
	    $table->integer('patientEmployerId')->unsigned(); //This will be used as foreign key for employer linking.
	    $table->integer('patientSocialId')->unsigned(); //This will be used as foreign key for patient's social stats linking.
	    $table->foreign('addressId')->references('id')->on('addresses')->onDelete('cascade');
	    $table->foreign('faceSheetId')->references('id')->on('patient_face_sheets')->onDelete('cascade');
	    $table->foreign('patientEmployerId')->references('id')->on('patient_employers')->onDelete('cascade');
	    $table->foreign('patientSocialId')->references('id')->on('patient_social_statistics')->onDelete('cascade');
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
        Schema::dropIfExists('patient_datas');
    }
}
