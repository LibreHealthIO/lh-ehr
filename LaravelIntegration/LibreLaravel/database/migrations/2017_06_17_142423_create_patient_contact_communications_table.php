<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientContactCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     * Creates patient_contact_communications table.
     * This table makes which contact should be communicated by what communication means. It renames all the hippa_* fields initially.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_contact_communications', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary key Auto Increment";
	    $table->integer('pid')->unsigned()->comment = "creates foreign key to link patient_datas table.";
	    $table->integer('contactId')->unsigned()->comment = "creates foreign key to link patient_contacts table.";
	    $table->boolean('mailMode')->default(0)->comment = "Allow through email. Initially set to be 0. Previously hippa_mail.";
	    $table->boolean('voiceMode')->default(0)->comment = "Allow voice message. Initially set to be 0. Previously hippa_voice.";
	    $table->string('message')->comment = "What message to be sent. Previously hippa_message.";
	    $table->boolean('messageMode')->default(0)->comment = "Allow text sms. Initially set to be 0. Previously hippa_allowsms.";
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
	    $table->foreign('contactId')->references('id')->on('patient_contacts')->onDelete('cascade');
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
        Schema::dropIfExists('patient_contact_communications');
    }
}
