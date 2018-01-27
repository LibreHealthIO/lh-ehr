<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingTable extends Migration
{
    /**
     * Run the migrations.
     * Creates databse tabel for billing
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('billing',fucntion(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->increment('id')->comment = "Primary key,auto increment";
			$table->dateTime('date')->comment = "Date";
			$table->string('code_type',15);
			$table->string('code',20);
			$table->integer('pid');
			$table->integer('provider_id');
			$table->integer('user');
			$table->string('groupname',255);
			$table->boolean('authorized');
			$table->integer('encounter');
			$table->text('code_text');
			$table->boolean('billed');
			$table->boolean('activity');
			$table->integer('payer_id');
			$table->tinyInteger('bill_process')->default(0);
			$table->timestamp('bill_date');
			$table->timestamp('process_date');
			$table->string('process_file',255);
			$table->string('modifier',12);
			$table->integer('units');
			$table->decimal('fee',12,2);
			$table->string('justify',255);
			$table->string('target',30);
			$table->integer('x12_partner_id');
			$table->string('ndc_info',255);
			$table->string('notecodes',80)->default('');
			$table->tinyInteger('exclude_from_insurance_billing')->default(0);
			$table->string('external_id',20);
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
        Schema::drop('billing');
    }
}
