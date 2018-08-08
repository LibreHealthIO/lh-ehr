<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientContactLinksTable extends Migration
{
    /**
     * Run the migrations.
     * Implements many to many relationship with patient and conatcts.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_contact_links', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
	    $table->integer('contactsId')->unsigned()->comment = "This will be foreign key to link patient_contacts table.";
            $table->integer('pid')->unsigned()->comment = "Foreign key to patient_datas table.";
	    $table->foreign('contactsId')->references('id')->on('patient_contacts')->onDelete('cascade');
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
        Schema::dropIfExists('patient_contact_links');
    }
}
