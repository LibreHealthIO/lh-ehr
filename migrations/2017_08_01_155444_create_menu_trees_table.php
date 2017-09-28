<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTreesTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for menu_trees
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Scehma::create('menu_trees', function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->string('menu_set',255);
			$table->string('entry_id',255);
			$table->string('icon',50);
			$table->string('helperText',50);
			$table->string('parent',255);
			$table->integer('seq');
			$table->string('label',255);
			$table->primsry('menu_set','entry_id','parent')->comment = "Primary key";
			
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Scehma::drop('menu_trees');
    }
}
