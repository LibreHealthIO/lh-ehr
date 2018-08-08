<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLangDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates lang_definitions table.
     * This stores definitons of languages.
     * From UI, Administration -> Language -> Edit Definitions.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('lang_definitions', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('lang_id', 0)->unsigned()->comment = "Foreign key to lang_languages table.";
	    $table->integer('cons_id', 0)->unsigned()->comment = "Foreign key to lang_constants table.";
	    $table->string('definition')->comment = "Definition of language.";
	    
	    /*Establishing Relationship*/
	    $table->foreign('lang_id')->references('id')->on('lang_languages')->onDelete('cascade');
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
        Schema::dropIfExists('lang_definitions');
    }
}
