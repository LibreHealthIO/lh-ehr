<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrayTable extends Migration
{
    /**
     * Run the migrations.
     * Creates table for array
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('array',function(Blueprint $table){
			$table->engine='InnoDB';
			$table->string('array_key',255)->nullable();
			$table->longText('array-value')->nullable();
			
			
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema:drop('array');
    }
}
