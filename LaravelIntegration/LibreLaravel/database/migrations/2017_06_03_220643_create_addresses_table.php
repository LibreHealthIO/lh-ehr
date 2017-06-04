<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     * table addresses.
     * This table will store the address of patient, employer and insurance companies. And later on will be linked to suitable tables.
     * @return void
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('line1', 100);
	    $table->string('line2', 100)->nullable();
	    $table->string('city', 100);
	    $table->string('state', 100);
	    $table->string('zip', 10);
	    $table->string('plus_four', 4);
	    $table->string('country', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
