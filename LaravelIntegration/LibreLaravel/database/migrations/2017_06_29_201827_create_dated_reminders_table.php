<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatedRemindersTable extends Migration
{
    /**
     * Run the migrations.
     * This creates dated_reminders table.
     * From UI, Messages -> Show Reminders -> Send A Dated Reminder.
     * @return void
     */
    public function up()
    {
        Schema::create('dated_reminders', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('dr_from_ID', 0)->unsigned()->comment = "Who created dated reminder? Refers to users table.";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign key to patient_datas table.";
	    $table->text('dr_message_text')->comment = "Message.";
	    $table->dateTime('dr_message_sent_date')->comment = "When message is sent.";
	    $table->date('dr_message_due_date')->comment = "Due Date";
	    $table->integer('message_priority', 0)->unsigned()->comment = "Priority of Message. 1 -> High | 2 -> Medium | 3 -> Low";
	    $table->boolean('message_processed')->default(0)->comment = "Is message processed? 0 -> No | 1 -> Yes";
	    $table->dateTime('processed_date')->comment = "When message is processed by respective user? Not keeping it null, becuase it can be system generated datetime, whenever message is processed.";
	    $table->integer('dr_processed_by', 0)->unsigned()->nullable()->comment = "User who processed the message. It can be multiple users or a single user. References users table."; //Making it null because, it will be updated when user process the message.
	    
	    /*Establishing Relationship*/
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
	    $table->foreign('dr_from_ID')->references('id')->on('users')->onDelete('cascade');
	    $table->foreign('dr_processed_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('dated_reminders');
    }
}
