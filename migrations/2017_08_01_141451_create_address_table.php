<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for address
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
      
            Schema::create('address', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->integer('id')->default(0);
			$table->string('line1',255)->nullable();
			$table->string('line2',255)->nullable();
			$table->string('city',35)->nullable();
			$table->string('state',35)->nullable();
			$table->string('zip',10)->nullable();
			$table->string('plus_four',4)->nullable();
			$table->string('country',255)->nullable();
			$table->integer('foreign_id')->nullable();
			$table->primary('id');
			$table->index('foreign_id');
            		$table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('address');
    }
}
