<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePharmaciesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates pharmacies table.
     * It stores pharmacy related records.
     * From UI, Administration -> Practice -> Pharmacies.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('address_id', 0)->unsigned()->nullable()->comment = "Foreign key to addresses table.";
	    $table->string('name', 100)->comment = "Name of pharmacy.";
	    $table->string('email')->nullable()->unique()->comment = "Email ID of pharmacy.";
	    $table->string('phone_number', 10)->nullable()->comment = "Phone Number of pharmacy.";
	    $table->string('fax_number', 10)->nullable()->comment = "Fax number of pharmacy.";
	    $table->integer('transit_method', 0)->default(1)->comment = "Method of Transit. 1 -> Print | 2 -> Email | 3 -> Fax";

	    /*Establishing Relationships*/
	    $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
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
        Schema::dropIfExists('pharmacies');
    }
}
