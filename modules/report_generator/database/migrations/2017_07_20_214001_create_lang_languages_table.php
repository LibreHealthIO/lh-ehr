<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLangLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates lang_languages table.
     * This stores description of languages.
     * From UI, Administration -> Language -> Add Language.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('lang_languages', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->string('lang_code', 2)->comment = "Language code. 2 character code.";
	    $table->string('lang_description')->comment = "Language Description.";
	    $table->boolean('lang_is_rtl')->default(0)->comment = "Is language Right to left formatted.";
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
        Schema::dropIfExists('lang_languages');
    }
}
