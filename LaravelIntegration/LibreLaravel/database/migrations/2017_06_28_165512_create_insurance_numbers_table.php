<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuranceNumbersTable extends Migration
{
    /**
     * Run the migrations.
     * This creates insurance_numbers table.
     * From UI, Administration -> Practice -> Insurance Numbers.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_numbers', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('provider_id', 0)->unsigned()->comment = "Foreign key to users table.";
	    $table->integer('insurance_company_id', 0)->unsigned()->comment = "Foreign key to insurance_companies table.";
	    $table->string('provider_number')->default('NA')->comment = "Provider Number";
	    $table->string('rendering_provider_number')->default('NA')->comment = "Rendering Provider Number";
	    $table->string('provider_number_type')->default('NA')->comment = "Provider Number Type";
	    $table->string('rendering_provider_number_type')->default('NA')->comment = "Rendering Provider Number Type";
	    $table->string('group_number')->default('NA')->comment = "Group Number";
	
	    /*Establishing Relationship*/
	    $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade');
	    $table->foreign('insurance_company_id')->references('id')->on('insurance_companies')->onDelete('cascade');
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
        Schema::dropIfExists('insurance_numbers');
    }
}
