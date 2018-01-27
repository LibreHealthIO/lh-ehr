<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesSeqTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for categories_seq
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('categories_seq',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->integer('id')->default(0)->comment = "Primary key";
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
        Schema::drop('categories_seq');
    }
}
