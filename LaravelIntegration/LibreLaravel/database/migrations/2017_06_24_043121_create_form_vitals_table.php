<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormVitalsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates form_vitals table.
     * From UI, Select Patient -> Encounter -> Clinical -> Vitals. | Select Patient -> Encounter -> Patient/CLient -> Visit Forms -> Vitals.
     * @author Priyanhsu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('form_vitals', function (Blueprint $table) {
            $table->increments('id')->comment = "Autoincrement. Primary Key";
	    $table->integer('encounter', 0)->unsigned()->comment = "Foreign key to form_encounters table.";
            $table->integer('pid', 0)->unsigned()->comment = "Foreign Key to patient_datas table.";
            $table->integer('userID', 0)->unsigned()->comment = "Foreign key to users table.";
            $table->integer('provider', 0)->unsigned()->index()->comment = "Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet";
            $table->dateTime('date')->comment = "Date when this form filled.";
            $table->boolean('authorized')->default(0)->comment = "Means a clinician (physician, etc...) has verified this form as part of the client record";
            $table->boolean('activity')->default(1)->comment = "A delete flag. 0 -> Yes | 1 -> No";
	    $table->decimal('bps', 5, 2)->default(0.00)->comment = "BP Systolic";
	    $table->decimal('bpd', 5, 2)->default(0.00)->comment = "BP Diastolic";
	    $table->decimal('weight', 5, 2)->default(0.00)->comment = "Weight of Patient.";
	    $table->decimal('height', 5, 2)->default(0.00)->comment = "Height of Person.";
	    $table->decimal('temperature', 5, 2)->default(0.00)->comment = "Temperature";
	    $table->string('temp_method')->nullable()->comment = "Temp Location";
	    $table->decimal('pulse', 5, 2)->default(0.00)->comment = "Pulse Rate";
	    $table->decimal('respiration', 5, 2)->default(0.00)->comment = "Respiration";
	    $table->decimal('BMI', 5, 2)->default(0.00)->comment = "Body Mass Index";
	    $table->decimal('waist_circ', 5, 2)->default(0.00)->comment = "Waist Circumference";
	    $table->decimal('head_circ', 5, 2)->default(0.00)->comment = "Head Circumference";
	    $table->decimal('oxygen_saturation')->default(0.00)->comment = "Percentage Oxygen Saturation";
	    $table->integer('external_id', 0)->unsigned()->nullable()->comment = "To hold an ID number from some other system, such as another EHR, an assigned ID that exists on a proprietary medical device or the like.";
	    $table->text('note')->nullable()->comment = "Note";
	    $table->text('BMI_status')->nullable()->comment = "BMI status.";
	    $table->foreign('encounter')->references('encounter')->on('form_encounters')->onDelete('cascade');
            $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('form_vitals');
    }
}
