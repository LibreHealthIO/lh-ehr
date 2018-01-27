<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditMasterTable extends Migration
{
    /**
     * Run the migrations.
     * Creates the database table for audit_master
     * @author Chandima Jayawickrama 
     * @return void
     */
    public function up()
    {
        Schema::create('audit_master',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->bigInteger('pid');
			$table->bigInteger('user_id')->comment = "The ID of the user who approves or denies";
			$table->tinyInteger('approval_status')->comment = "1-Pending,2-Approved,3-Denied,4-Appointment directly updated to calendar table, 5-Cancelled appoinment";
			$table->text('comments');
			$table->timestamp('created_time');
			$table->timestamp('modified_time');
			$table->string('ip_address',200);
			$table->typeInteger('type')->comment = "1-new patient, 2-existing patient, 3-change is only in the documnet, 4-Patient upload, 5-random key, 10-Appointment";
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
        Schema::drop('audit_master');
    }
}
