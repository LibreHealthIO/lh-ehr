<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArActivityTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for ar_activity
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('ar_activity', function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->integer('pid');
			$table->integer('encounter');
			$table->increment('sequence_no')->comment = "Auto increment";
			$table->string('code_type',12);
			$table->string('code',20)->comment= "empty means claim level";
			$table->string('modifier',12);
			$table->integer('payer_type')->comment = "0 = pt, 1 = ins1, 2 = ins2, etc";
			$table->dateTime('post_time');
			$table->integer('post_user');
			$table->integer('session_id');
			$table->string('memo',255);
			$table->decimal('pay_amount',12,2)->default(0);
			$table->decimal('adj_amount',12,2)->default(0);
			$table->dateTime('modified_time');
			$table->char('follow_up',1);
			$table->text('follow_up_note');
			$table->string('account_code',15);
			$table->string('reason_code',255);
			$table->primary([sequence_no, pid, encounter])->comment = "Primary key";
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ar_activity');
    }
}
