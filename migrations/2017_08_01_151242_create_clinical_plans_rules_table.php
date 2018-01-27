<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicalPlansRulesTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for clinical_plans_rules
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('clinical_plans_rules',fucntion(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->string('plan_id',31)->comment = "Unique and maps to list_options list clinical_plans";
			$table->string('rule_id',31)->comment = "Unique and maps to list_options list clinical_plans";
			$table->primary(array('plan_id','rule_id'))->comment = "Primary key";
			
			
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clinical_plans_rules');
    }
}
