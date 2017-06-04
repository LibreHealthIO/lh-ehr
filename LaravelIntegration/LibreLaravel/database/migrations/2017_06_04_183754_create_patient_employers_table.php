<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientEmployersTable extends Migration
{
    /**
     * Run the migrations.
     * Create patient_employers table. This will have 1...N relationship with address tables and patients table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_employers', function (Blueprint $table) {
            $table->increments('id');
	    $table->integer('addressId')->unsigned();
	    $table->string('name', 100);
	    $table->foreign('addressId')->references('id')->on('addresses')->onDelete('cascade');
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
        Schema::dropIfExists('patient_employers');
    }
}
