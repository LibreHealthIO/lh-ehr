<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackgroundServicesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates background_services table.
     * Compared to earlier structure, require_once is dropped as it contains the path of file, so that can be used in modal.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('background_services', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->string('name')->unique()->comment = "Service Name";
	    $table->string('title')->comment = "Name for reports";
	    $table->boolean('active')->default(0)->comment = "Is service active or deleted.  0 -> False | 1 -> True";
	    $table->boolean('running')->default(0)->comment = "Is service running or stopped. 0 -> Stopped(False) | 1 -> Running(True)";
	    $table->timestamp('next_run')->useCurrent()->comment = "When next run is scheduled?";
	    $table->integer('execute_interval', 0)->unsigned()->default(0)->comment = "Minimum number of minutes between function calls, 0 = Manual Mode";
	    $table->string('function')->comment = "Name of background service function";
	    $table->integer('sort_order', 0)->unsigned()->comment = "If there are multiple services, then lower number will run first.";
	    /*$table->string('require_once')->nullable()->comment = "Include File."*/
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
        Schema::dropIfExists('background_services');
    }
}
