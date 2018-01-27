<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicalPlansTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table clinical_plans
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('clinical_plans',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->string('id',31)->comment = "Unique and maps to list clinical_plans";
			$table->bigInteger('pid')->default(0)->comment = "0 is default for all patients, while > 0 is id from patient_data table";
			$table->boolean('normal_flag')->comment = "Normal Activation Flag";
			$table->boolean('cqm_flag')->comment = "Clinical Quality Measure flag (unable to customize per patient)";
			$table->boolean('cqm_2011_flag')->comment = "2011 Clinical Quality Measure flag (unable to customize per patient)";
			$table->boolean('cqm_2014_flag')->comment = "2014 Clinical Quality Measure flag (unable to customize per patient)";
			$table->string('cqm_measure_group',10)->comment = "Clinical Quality Measure Group Identifier";
			$table->primary(array('id','pid'));
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clinical_plans');
    }
}
