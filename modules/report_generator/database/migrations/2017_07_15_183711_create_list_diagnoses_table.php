<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates list_diagnoses table.
     * It stores diagnosis related to lists.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('list_diagnoses', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary key. Autoincrement.";
	    $table->integer('list_id', 0)->unsigned()->comment = "Foreign key to lists table.";
	    $table->string('diagnosis')->comment = "Diagnosis.";
	    
	    /*Establishing Relationships.*/
	    $table->foreign('list_id')->references('id')->on('lists')->onDelete('cascade');
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
        Schema::dropIfExists('list_diagnoses');
    }
}
