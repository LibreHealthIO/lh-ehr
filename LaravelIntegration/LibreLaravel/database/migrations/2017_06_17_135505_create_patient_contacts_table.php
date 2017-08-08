<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientContactsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates patient_contacts table.
     * Its purpose is to store the contact's information of a patient.
     * Many to many relationship with patient_data.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_contacts', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key, Autoincrement";
	    $table->integer('providerId')->comment = "Provider Id";
	    $table->integer('refProviderId')->comment = "Reference Provider Id";
	    $table->string('home_phone', 20)->comment = "Contact home phone";
	    $table->string('work_phone', 20)->comment = "Contact business phone";
	    $table->string('contact_phone', 20)->comment = "Personal contact phone number.";
	    $table->string('contact_relationship', 100)->comment = "what relationship does contact have with patient.";
	    $table->string('patient_email', 100)->unique()->comment = "Additional field added. Contact email id.";
	    $table->string('county', 10)->comment = "county";
	    $table->string('country_code', 10)->comment = "country code of patient.";
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
