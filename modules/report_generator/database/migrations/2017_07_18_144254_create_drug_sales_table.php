<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrugSalesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates drug_sales table. 
     * This table is related to prescriptions, dugs, drug_inventories, patient_datas, form_encounters and users table.
     * From UI, Inventory -> Management.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('drug_sales', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('drug_id', 0)->unsigned()->comment = "Foreign key to drugs table.";
	    $table->integer('inventory_id', 0)->unsigned()->comment = "Foreign key to drug_inventories table.";
	    $table->integer('prescription_id', 0)->unsigned()->nullable()->comment = "Foreign key to prescriptions table.";
	    $table->integer('pid', 0)->unsigned()->nullable()->comment = "Foreign key to patient_datas table.";
	    $table->integer('encounter', 0)->unsigned()->nullable()->comment = "Foreign key to form_encounters table.";
	    $table->integer('user', 0)->unsigned()->comment = "Foreign key to users table.";
	    $table->integer('distributor_id', 0)->unsigned()->nullable()->comment = "Distributor of drug. Foreign key to users table.";
	    $table->date('sale_date')->comment = "Date when drug is sold.";
	    $table->integer('quantity', 0)->unsigned()->commment = "Quantity of drug sold.";
	    $table->decimal('fee', 12, 2)->default(0.00)->comment = "Fees of Drugs.";
	    $table->boolean('billed')->default(0)->comment = "If the sale is posted to accounting? 0 -> No | 1 -> Yes";
	    $table->text('notes')->nullable()->comment = "Notes";

	    /*Establishing Relationships*/
	    $table->foreign('drug_id')->references('id')->on('drugs')->onDelete('cascade');
	    $table->foreign('inventory_id')->references('id')->on('drug_inventories')->onDelete('cascade');
	    $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
	    $table->foreign('encounter')->references('id')->on('form_encounters')->onDelete('cascade');
	    $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
	    $table->foreign('distributor_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('drug_sales');
    }
}
