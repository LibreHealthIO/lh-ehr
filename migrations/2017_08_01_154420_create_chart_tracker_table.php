<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartTrackerTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for chart_tracker
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('chart_tracker',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->integer('ct_pid');
			$table->dateTime('ct_when');
			$table->bigInteger('ct_userid')->default(0);
			$table->string('ct_location',31);
			$table->primary(['ct_pid', 'ct_when'])->comment = "Primary key";
			
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('chart_tracker');
    }
}
