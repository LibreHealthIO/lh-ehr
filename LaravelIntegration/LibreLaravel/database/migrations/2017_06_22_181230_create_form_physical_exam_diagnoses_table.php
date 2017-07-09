<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormPhysicalExamDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates form_physical_exam_diagnoses table.
     * From UI Select Physical Exam -> Diagnosis Dropdown -> Edit. 
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('form_physical_exam_diagnoses', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->string('line_id')->comment = "Foreign Key. References form_physical_exams table";
	    $table->integer('ordering', 0)->unsigned()->comment = "Diagnosis Order";
	    $table->string('diagnosis')->comment = "Diagnosis information";
	    $table->foreign('line_id')->references('line_id')->on('form_physical_exams')->onDelete('cascade');
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
        Schema::dropIfExists('form_physical_exam_diagnoses');
    }
}
