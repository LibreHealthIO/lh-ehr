<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrugInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates drug_inventories table.
     * From UI, Inventory -> Management.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('drug_inventories', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
	    $table->integer('drug_id', 0)->unsigned()->comment = "Foreign key to drugs table.";
	    $table->integer('warehouse_id', 0)->unsigned()->comment = "Foreign key to product_warehouses table.";
	    $table->string('lot_number')->nullable()->unique()->comment = "LOT Number. Unique Number for drugs.";
	    $table->date('expiration')->nullable()->comment = "Expiry Date of Drug.";
	    $table->string('manufacturer')->nullable()->comment = "Manufacturer of Drug.";
	    $table->integer('on_hand', 0)->unsigned()->default(0)->comment = "Count of drug already in inventory.";
	    $table->integer('vendor_id', 0)->unsigned()->default(0)->comment = "Vendor.";
	    $table->date('destroy_date')->nullable()->comment = "Date when drug is destroyed.";
	    $table->string('destroy_method')->nullable()->comment = "Method used to destroy drug.";
	    $table->string('destroy_witness')->nullable()->comment = "Witness at the time of destruction.";
	    $table->text('destroy_notes')->nullable()->comment = "Extra information.";

	    /*Establishing Relationship*/
	    $table->foreign('drug_id')->references('id')->on('drugs')->onDelete('cascade');
	    $table->foreign('warehouse_id')->references('id')->on('product_warehouses')->onDelete('cascade');
	    $table->index('vendor_id');
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
        Schema::dropIfExists('drug_inventories');
    }
}
