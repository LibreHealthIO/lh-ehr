<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates product_warehouses table. 
     * It stores the warehouse imformation of drugs.
     * From UI, Inventory -> Management.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('product_warehouses', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('drug_id', 0)->unsigned()->comment = "Foreign key to Drugs table.";
	    $table->string('pw_warehouse')->comment = "Warehouse";
	    $table->decimal('pw_min_level', 5, 2)->default(0.00)->comment = "Min Level";
	    $table->decimal('pw_max_level', 5, 2)->default(0.00)->comment = "Max Level";
	    
	    /*Establishing Relationship*/
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
        Schema::dropIfExists('product_warehouses');
    }
}
