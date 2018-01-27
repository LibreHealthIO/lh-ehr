<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersFacilityTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for users_facility
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('users_facility', function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->string('tablename',64);
			$table->integer('table_id');
			$table->integer('facility_id');
			$table->primary('tablename','table_id','facility_id')->comment = "Primary key";
			
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_facility');
    }
}
