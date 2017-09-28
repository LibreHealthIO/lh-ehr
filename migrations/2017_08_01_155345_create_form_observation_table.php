<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormObservationTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for form_observation
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('form_observation',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->bigInteger('id');
			$table->date('date');
			$table->bigInteger('pid');
			$table->string('encounter',255);
			$table->string('user',255);
			$table->string('groupname',255);
			$table->tinyInteger('authorized');
			$table->tinyInteger('activity');
			$table->string('code',255);
			$table->string('observation',255);
			$table->string('ob_value',255);
			$table->string('ob_unit',255);
			$table->string('description',255);
			$table->string('code_type',255);
			$table->string('table_code',255);
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_observation');
    }
}
