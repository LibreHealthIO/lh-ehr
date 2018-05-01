<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatedReminderLinksTable extends Migration
{
    /**
     * Run the migrations.
     * This creates dated_reminder_links table.
     * It stores the link of dated reminder and user, i.e., to which user that reminder is sent?
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('dated_reminder_links', function (Blueprint $table) {
            $table->increments('id')->comment = "Autoincrement. Primary Key.";
	    $table->integer('dr_id', 0)->unsigned()->comment = "Link to dated_reminders table.";
	    $table->integer('to_id', 0)->unsigned()->comment = "Link to users table";
	 
	    /*Establishing Relationship*/
	    $table->foreign('dr_id')->references('id')->on('dated_reminders')->onDelete('cascade');
	    $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('dated_reminder_links');
    }
}
