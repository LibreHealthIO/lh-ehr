<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbtDataTable extends Migration
{
    /**
     * Run the migrations.
     * Create database table for lbt_data
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('lbt_data', function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->bigInteger('form_id');
			$table->string('field_id',31);
			$table->text('field_value');
			$table->primary(['form_id','field_id'])->comment = "Primary key";
			
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lbt_data');
    }
}
