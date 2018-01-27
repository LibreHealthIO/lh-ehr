<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for claims
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('claims',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->integer('patient_id');
			$table->integer('encounter_id');
			$table->integer('version')->unsigned()->comment = "Claim version, incremented in code";
			$table->integer('payer_id')->default(0);
			$table->tinyInteger('status')->default(0);
			$table->tinyInteger('payer_type')->default(0);
			$table->tinyInteger('bill_process')->default(0);
			$table->timestamp('bill_time');
			$table->timestamp('process_time');
			$table->string('process_file',255);
			$table->string('target',30);
			$table->integer('x12_partner_id')->default(0);
			$table->primary(array(`patient_id`,`encounter_id`,`version`));
			
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('claims');
    }
}
