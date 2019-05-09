<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientDatasTable extends Migration
{
    /**
     * Run the migrations.
     * Create patient_datas table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('patient_datas', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key Autoincrement.";
	    $table->integer('pid',0)->unsigned()->unique()->comment = "pid is patient id. More reasonable in case of medical information.";
	    $table->string('title', 5)->comment = "title viz Mr., Ms., Mrs., etc.";
	    $table->string('occupation')->comment = "occupation of patient.";
	    $table->string('industry')->comment = "industry in which patient work.";
	    $table->integer('addressId')->unsigned()->comment = "This will be used as foreign key for address linking to patient.";
	    $table->foreign('addressId')->references('id')->on('addresses')->onDelete('cascade'); //Foreign Key.
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
        Schema::dropIfExists('patient_datas');
    }
}
