<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrugsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates drugs table.
     * Enable inventory from Globals -> Features -> Drugs and Products.
     * From Inventory -> Management.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->string('related_code')->comment = "Codes";
	    $table->string('name')->comment = "Name of Drug.";
	    $table->string('ndc_number')->comment = "NDC Number";
	    $table->integer('on_order', 0)->unsigned()->comment = "On Order.";
	    $table->float('reorder_point',8 ,2)->default(0.00)->comment = "Min Global (In Form)";
	    $table->float('max_level', 8, 2)->default(0.00)->comment = "Max Global (In Form)";
	    $table->text('reactions')->nullable()->comment = "Reaction of drug."; /*This can be dropped.*/
	    $table->float('cyp_factor', 8, 2)->comment = "Quantity representing a years supply";
	    $table->boolean('active')->default(0)->comment = "Is Drug active? 0 -> No | 1 -> Yes";
	    $table->boolean('allow_combining')->default(0)->comment = "Allow filling an order from multiple lots? 0 -> No | 1 -> Yes";
	    $table->boolean('allow_multiple')->default(0)->comment = "Allow multiple lots at one warehouse? 0 -> No | 1 -> Yes";
	    $table->string('drug_code')->nullable()->comment = "Drug Code";
	    
	    /*Creating Index*/
	    $table->index('drug_code');
	    $table->index('related_code');
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
        Schema::dropIfExists('drugs');
    }
}
