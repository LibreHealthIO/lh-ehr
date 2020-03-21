<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates globals table.
     * This stores all the global settings for application.
     * From UI, Administration -> Globals.
     * @author Priyanshu Sinha <pksinha217@gmail.com> 
     * @return void
     */
    public function up()
    {
        Schema::create('globals', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->json('settings')->comment = "Store global settings in json format.";
	    /* Eg:
	     * [
             *	{
             *   	"tab_name" : 'Appearance',
             *   	"Values" : {
             *                   "default_first_tab" : 'Dynamic Finder',
             *                   "navigation_area_width" : 150,
             *                   .............
             *                   .............
             *           }
             *	},
             *	{
             *  	"tab_name" : 'Locale',
             *  	"Values" : {
             *                  "translate_layouts" : true,
             *                  "default_language" : 'English(standard)',
             *                  ...............
             *                  ...............
             *          }
             *	},
             *	............
             *	............
	     * ]
	     *
	     */
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
        Schema::dropIfExists('globals');
    }
}
