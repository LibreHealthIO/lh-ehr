<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates prices table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('pr_id', 0)->unsigned()->comment = "Foreign key to drugs table.";
	    $table->string('pr_selector')->nullable()->comment = "Template selector for drugs, empty for codes";
	    $table->string('pr_level')->comment = "Price Level";
	    $table->decimal('pr_price', 10, 2)->comment = "Price of that drug in local currency";
	    
	    /*Creating Relationships*/
	    $table->foreign('pr_id')->references('id')->on('drugs')->onDelete('cascade');
	    $table->foreign('pr_selector')->references('selector')->on('drug_templates')->onDelete('cascade');
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
        Schema::dropIfExists('prices');
    }
}
