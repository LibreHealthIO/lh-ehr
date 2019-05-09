<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLangCustomsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates lang_customs table.
     * This stores custom languages i.e, created by user.
     * From UI, Administration -> Languages.
     * @return void
     */
    public function up()
    {
        Schema::create('lang_customs', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary key. Autoincrement.";
	    $table->string('lang_code', 2)->comment = "2 Character Language Code.";
	    $table->string('lang_description')->comment = "Description of langauge.";
	    $table->integer('cons_id', 0)->unsigned()->nullable()->comment = "Foreign key to lang_constants table.";
	    $table->string('definition')->comment = "Definition of language.";

	    /*Establishing Foreign key*/
	    $table->foreign('cons_id')->references('id')->on('lang_constants')->onDelete('cascade');
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
        Schema::dropIfExists('lang_customs');
    }
}
