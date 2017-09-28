<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for categories
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->integer('id')->default(0)->comment = "Primary key";
			$table->string('name',255);
			$table->string('value',255);
			$table->integer('parent')->default(0);
			$table->integer('lft')->default(0);
			$table->integer('rght')->default(0);
			$table->primary('id');
			$table->primary(array('lft', 'rght'));
			
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('categories');
    }
}
