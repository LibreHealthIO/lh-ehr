<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchcomTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for batchcom
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('batchcom',fucntion(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->bigIncrement('id')->comment = "Primary key, auto increment";
			$table->integer('patient_id')->default(0);
			$table->bigInteger('sent_by')->default(0);
			$table->string('msg_type',60);
			$table->string('msg_subject',255);
			$table->mediumText('msg_text');
			$table->timestamp('msg_date_sent')->comment = "0000-00-00 00:00:00";
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
        Schema::drop('batchcom');
    }
}
