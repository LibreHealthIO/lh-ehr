<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     * Create addresses table.
     * It will store the addresse.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id')->comment = "Autoincrement Primary Key.";
	    $table->string('line1', 100)->comment = "Line 1 of address.";
	    $table->string('line2', 100)->nullable()->comment = "Line 2 of address. Optional";
	    $table->string('city', 100)->comment = "city";
	    $table->string('state', 100)->comment = "state";
	    $table->string('zip', 10)->comment = "zip code";
	    $table->string('plus_four', 4)->comment = "plus four code. US specific thing";
	    $table->string('country', 50)->comment = "Country";
	    $table->string('country_code')->comment = "Country Code";
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
