<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientContactsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates patient_contacts which is in many-to-many relationship with patient_data.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_contacts', function (Blueprint $table) {
            $table->increments('id');//Primary Key
	    $table->integer('providerId'); //Provider Id
	    $table->integer('refProviderId'); //Reference Provider Id
	    $table->string('home_phone', 10); //Contact's home phone
	    $table->string('work_phone', 10); //Contact's business phone
	    $table->string('contact_phone', 10); //Personal contact phone number.
	    $table->string('contact_relationship', 100); //what relationship does contact have with patient
	    $table->string('patient_email', 100)->unique(); //Additional field added. Contact's email id.
	    $table->string('county', 10); //county
	    $table->string('country_code', 10); //country code of patient.
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
        Schema::dropIfExists('patient_contacts');
    }
}
