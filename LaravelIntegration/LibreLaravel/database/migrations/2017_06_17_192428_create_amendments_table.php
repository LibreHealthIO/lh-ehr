<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmendmentsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates amendments table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('amendments', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign key to patient_datas table";
	    $table->integer('created_by', 0)->unsigned()->comment = "Foreign key to users table. This is the user who created amendment";
	    $table->integer('modified_by', 0)->unsigned()->comment = "Foreign key to users table. This is the user who modified amendment";
	    $table->date('amendment_date')->comment = "Amendment Date";
	    $table->integer('amendment_status', 0)->unsigned()->comment = "Ammendment Status. 0->rejected, 1->accepted, 2->null";
	    $table->string('amendment_by')->comment = "Amendment requested from";
	    $table->text('amendment_desc')->nullable()->comment = "Amendment Description";
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
	    $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('amendments');
    }
}
