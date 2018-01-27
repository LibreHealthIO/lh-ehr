<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackgroundServicesTable extends Migration
{
    /**
     * Run the migrations.
     * creates database table for background_services
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('background_services',function(Blueprint $table){
			$table->=engine = 'InnoDB';
			$table->string('name',31);
			$table->string('title',127)->comment = "Name for the reports";
			$table->boolean('active')->default(0);
			$table->tinyInteger('running')->default(-1);
			$table->timstamp('next_run');
			$table->integer('execute_interval')->default(0)->comment = "Minimum number of minutes between function calls, 0 = manual mode";
			$table->string('function',127)->comment = "Name of the background service";
			$table->string('require_once',255)->comment = "Include file if necessary";
			$table->integer('sort_order')->comment = "Lower numbers will be run fast";
			$table->primary('name');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('background_services');
    }
}
