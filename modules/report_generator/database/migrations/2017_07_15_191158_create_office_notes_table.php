<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficeNotesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates office_notes table. (Earlier onotes)
     * From UI, Miscellaneous -> Ofc Notes.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('office_notes', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('user', 0)->unsigned()->comment = "Foreign Key to users table.";
	    $table->dateTime('date')->useCurrent()->comment = "Timestamp when note is created.";
	    $table->text('body')->nullable()->comment = "Note content";
	    $table->boolean('activity')->default(0)->comment = "Is note active? 0 -> No | 1 -> Yes";

	    /*Establishing Relationships*/
	    $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('office_notes');
    }
}
