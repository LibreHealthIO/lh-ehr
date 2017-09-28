<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormClinicalInstructionsTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for form_clinical_instructions
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('form_clinical_instructions',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->increment('id')->comment = "Primary key";
			$table->bigInteger('pid');
			$table->string('encounter',255);
			$table->string('user',255);
			$table->text('instruction');
			$table->timestamp('date');
			$table->boolean('activity');
			$table->primary('id');
		
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_clinical_instructions');
    }
}
