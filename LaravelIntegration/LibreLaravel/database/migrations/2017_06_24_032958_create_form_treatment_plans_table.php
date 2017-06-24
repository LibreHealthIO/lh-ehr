<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormTreatmentPlansTable extends Migration
{
    /**
     * Run the migrations.
     * This creates form_treatment_plans table.
     * From UI, Select Patient -> Encounter -> Miscellaneous -> Treatment Plan. | Select Patient -> Encounter -> Patient/CLient -> Visit Forms -> Treatment Plan.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('form_treatment_plans', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
	    $table->integer('encounter', 0)->unsigned()->comment = "Foreign key to form_encounters table.";
            $table->integer('pid', 0)->unsigned()->comment = "Foreign Key to patient_datas table.";
            $table->integer('userID', 0)->unsigned()->comment = "Foreign key to users table.";
            $table->integer('provider', 0)->unsigned()->index()->comment = "Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet";
            $table->dateTime('date')->comment = "Date when this form filled.";
            $table->boolean('authorized')->default(0)->comment = "Means a clinician (physician, etc...) has verified this form as part of the client record";
            $table->boolean('activity')->default(1)->comment = "A delete flag. 0 -> Yes | 1 -> No";
	    $table->string('client_name')->comment = "Patient Name";
	    $table->date('admit_date')->comment = "When Patient was Admitted.";
	    $table->text('presenting_issues')->nullable()->comment = "Issues";
	    $table->text('patient_history')->nullable()->comment = "Patient History";
	    $table->text('medications')->nullable()->comment = "Medication";
	    $table->text('anyother_relevent_information')->nullable()->comment = "Extra Information";
	    $table->text('diagnosis')->nullable()->comment = "Diagnosis Report.";
	    $table->text('treatment_received')->nullable()->comment = "Treatment given to Patient.";
	    $table->text('recomendation_for_follow_up')->nullable()->comment = "Follow up recomendations.";
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
        Schema::dropIfExists('form_treatment_plans');
    }
}
