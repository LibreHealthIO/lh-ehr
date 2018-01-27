<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuEntriesTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table menu_entries 
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('menu_entries',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->string('id',255)->comment = "Primary key, auto increment";
			$table->string('label',255);
			$table->string('icon',50);
			$table->string('class',50);
			$table->string('helperText',50);
			$table->string('target',45);
			$table->string('url',255);
			$table->integer('requirement');
			$table->string('acl_reqs',255);
			$table->string('global_reqs',255);
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
        Schema::drop('menu_entries');
    }
}
