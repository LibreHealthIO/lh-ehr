<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmendmentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates amendment_histories table.
     * store the history of amendments.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('amendment_histories', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('amendmentID', 0)->unsigned()->comment = "Foreign key to amendments table.";
	    $table->integer('created_by', 0)->unsigned()->comment = "Foreign key to users table.";
	    $table->integer('amendment_status', 0)->unsigned()->comment = "Amendment Status";
	    $table->text('amendment_note')->nullable()->comment = "Amendment Note";
	    $table->foreign('amendmentID')->references('id')->on('amendments')->onDelete('cascade');
	    $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('amendment_histories');
    }
}
