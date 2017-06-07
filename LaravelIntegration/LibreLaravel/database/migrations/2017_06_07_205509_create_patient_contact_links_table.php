<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientContactLinksTable extends Migration
{
    /**
     * Run the migrations.
     * This table implements many to many relationship with contacts table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_contact_links', function (Blueprint $table) {
            $table->increments('id');
	    $table->integer('patientId')->unsigned(); //This will be foreign key to link patient_datas table.
  	    $table->integer('contactsId')->unsigned(); //This will be foreign key to link patient_contacts table.
	    $table->foreign('patientId')->references('id')->on('patient_datas')->onDelete('cascade');
	    $table->foreign('contactsId')->references('id')->on('patient_contacts')->onDelete('cascade');
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
        Schema::dropIfExists('patient_contact_links');
    }
}
