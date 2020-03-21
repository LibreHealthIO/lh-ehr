<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLangConstantsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates lang_constants table.
     * From UI, Administration -> Language -> Add Constant.
     * This stores constant for languages.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('lang_constants', function (Blueprint $table) {
            $table->increments('id')->comment = "Auto increment. Primary Key.";
	    $table->string('constant_name')->comment = "Name of Constant";
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
        Schema::dropIfExists('lang_constants');
    }
}
