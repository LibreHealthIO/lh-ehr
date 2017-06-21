<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormAftercarePlansTable extends Migration
{
    /**
     * Run the migrations.
     * This creates form_aftercare_plans table.
     * From UI, Select Patient -> Encounter -> Miscellaneous -> Aftercare Plan. | Select Patient -> Encounter -> Patient/CLient -> Visit Forms -> Aftercare Plan.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('form_aftercare_plans', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
	    $table->integer('encounter', 0)->unsigned()->comment = "Foreign key to form_encounters table.";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign Key to patient_datas table.";
	    $table->integer('userID', 0)->unsigned()->comment = "Foreign key to users table.";
	    $table->integer('provider', 0)->unsigned()->index()->comment = "Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet";
	    $table->dateTime('date')->comment = "Date when this form filled.";
	    $table->boolean('authorized')->default(0)->comment = "Means a clinician (physician, etc...) has verified this form as part of the client record";
	    $table->boolean('activity')->default(1)->comment = "A delete flag. 0 -> Yes | 1 -> No";
	    $table->string('client_name')->comment = "Name of Patient.";
	    $table->date('admit_date')->comment = "Date when patient is admitted";
	    $table->date('discharged')->nullable()->comment = "Date when patient is dischanrged.";
	    $table->text('goal_a_acute_intoxication')->nullable()->comment = "Look at form UI.";
	    $table->text('goal_a_acute_intoxication_I')->nullable()->comment = "Look at form UI.";
            $table->text('goal_a_acute_intoxication_II')->nullable()->comment = "Look at form UI.";
            $table->text('goal_b_emotional_behavioal_conditions')->nullable()->comment = "Look at form UI.";
            $table->text('goal_b_emotional_behavioal_conditions_I')->nullable()->comment = "Look at form UI.";
            $table->text('goal_c_relapse_potential')->nullable()->comment = "Look at form UI.";
            $table->text('goal_c_relapse_potential_I')->nullable()->comment = "Look at form UI.";
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
        Schema::dropIfExists('form_aftercare_plans');
    }
}
