<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArSessionTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for ar_session
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('ar_session',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->increment('session_id')->comment = "Primary key, auto increment";
			$table->integer('payer_id')->comment = " 0 = pt else references insurance_companies.id";
			$table->integer('user_id')->comment = "References users.id for session owner";
			$table->boolean('closed')->comment = "0 = no, 1 = yes";
			$table->string('reference',255)->comment = "Check or EOB number";
			$table->date('check_date');
			$table->date('deposit_date');
			$table->decimal('pay_total',12,2);
			$table->timestamp('created_time');
			$table->dateTime('modified_time');
			$table->decimal('global_amount',12,2);
			$table->string('payment_type',50);
			$table->text('description');
			$table->string('adjustment_code',50);
			$table->date('post_to_date');
			$table->integer('patient_id');
			$table->string('payment_method',25);
			$table->primary('session_id');
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ar_session');
    }
}
