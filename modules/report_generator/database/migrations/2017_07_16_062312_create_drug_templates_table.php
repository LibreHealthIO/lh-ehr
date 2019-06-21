<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrugTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates drug_templates table.
     * From UI, Inventory -> Management.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('drug_templates', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('drug_id', 0)->unsigned()->comment = "Foreign Key to drugs table.";
	    $table->string('selector')->unique()->comment = "Selector. Name of Template.";
	    $table->string('dosage')->comment = "Schedule.";
	    $table->integer('period', 0)->default(0)->unsigned()->comment = "Interval";
	    $table->integer('quantity', 0)->default(0)->unsigned()->comment = "Quantity";
	    $table->integer('refills', 0)->default(0)->unsigned()->comment = "Refills.";
	    $table->string('taxrates')->nullable()->comment = "Tax Rate."; /*This field can be dropped.*/
	   
	    /*Establishing Relationships & Creating Index*/
	    $table->foreign('drug_id')->references('id')->on('drugs')->onDelete('cascade');
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
        Schema::dropIfExists('drug_templates');
    }
}
