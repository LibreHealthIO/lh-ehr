<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmcMiscDataTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for amc_misc_data
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('amc_misc_data',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->string('amc_id',31)->comment = "Unique and maps to list_options clinical_rules ";
			$table->bigInteger('pid');
			$table->string('map_category',255)->comment = "Maps to an object category (such as prescriptions etc) ";
			$table->bigInteger('map_id')->comment = "Maps to an object id (such as prescripions id etc)";
			$table->dateTime('date_created');
			$table->dateTime('date_completed');
			$table->primary('amc_id','pid','map_id')->comment = "Primary key";
			
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('amc_misc_data');
    }
}
