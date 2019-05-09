<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePnotesTable extends Migration
{
    /**
     * Run the migrations.
     * Cretes pnotes table.
     * From UI, Select Patient -> Notes.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('pnotes', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign key to patient_datas table.";
	    $table->integer('userID', 0)->unsigned()->comment = "Foreign key to users table";
	    $table->integer('assigned_to', 0)->unsigned()->comment = "Foreign key to users table. It is basically that user whome that note is assigned.";
	    $table->dateTime('date')->useCurrent()->comment = "Timestamp at which date is created.";
	    $table->text('body')->nullable()->comment = "Message sent by user.";
	    $table->boolean('activity')->default(0)->comment = "Is note active? 0 -> No | 1 -> Yes";
	    $table->boolean('authorized')->default(0)->comment = "Is note authorized? 0 -> No | 1 -> Yes";
	    $table->string('title')->comment = "Type";
	    $table->boolean('deleted')->default(0)->comment = "Indicates note is deleted. 0 -> No | 1 -> Yes";
	    $table->string('message_status')->default('New')->comment = "Status of message";
	    $table->string('portal_relation')->nullable()->comment = "Patient Portal Relation";
	    $table->boolean('is_msg_encrypted')->default(0)->comment = "WHether message is encrypted? 0 -> No | 1 -> Yes";
	    /*Establishing Relationships*/
	    $table->foreign('pid')->references('id')->on('patient_datas')->onDelete('cascade');
	    $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
	    $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade'); 
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
        Schema::dropIfExists('pnotes');
    }
}
