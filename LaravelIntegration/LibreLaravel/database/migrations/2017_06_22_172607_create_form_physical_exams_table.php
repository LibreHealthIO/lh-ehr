<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormPhysicalExamsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates form_physical_exams table.
     * From UI, Select Patient -> Encounter -> Miscellaneous -> Physical Exam. | Select Patient -> Encounter -> Patient/CLient -> Visit Forms -> Physical Exam.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('form_physical_exams', function (Blueprint $table) {
            $table->increments('id')->comment = "Autoincrement. Primary Key";
	    $table->integer('encounter', 0)->unsigned()->comment = "Foreign key to form_encounters table.";
            $table->integer('pid', 0)->unsigned()->comment = "Foreign Key to patient_datas table.";
            $table->integer('userID', 0)->unsigned()->comment = "Foreign key to users table.";
            $table->integer('provider', 0)->unsigned()->index()->comment = "Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet";
            $table->dateTime('date')->comment = "Date when this form filled.";
            $table->boolean('authorized')->default(0)->comment = "Means a clinician (physician, etc...) has verified this form as part of the client record";
	    $table->string('line_id')->unique()->comment = "A kind of key to be used to refer this form.";
	    $table->boolean('wnl')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('abn')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->string('diagnosis')->nullable()->comment = "Diagnosis related to this line";
	    $table->string('comments')->nullable()->comment = "Comment";
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
        Schema::dropIfExists('form_physical_exams');
    }
}
