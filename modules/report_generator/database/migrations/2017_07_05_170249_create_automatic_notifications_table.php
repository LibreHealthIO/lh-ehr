<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutomaticNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates automatic_notifications table.
     * 'email_sender' field need to be cleared. It can be a link to users table referencing id of that user.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('automatic_notifications', function (Blueprint $table) {
            $table->increments('id')->comment = "Autoincrement. Primary Key";
	    $table->string('sms_gateway_type')->comment = "Type of sms gateway.";
	    $table->dateTime('next_app_schedule')->comment = "When next notification is scheduled?";
	    $table->string('provider_name');
	    $table->text('message')->nullable()->comment = "Message to be sent.";
	    $table->string('email_sender')->comment = "Who sent email? It can be any user or any clinic.";
	    $table->string('email_subject')->comment = "Subject of notification";
	    $table->enum('type', ['SMS', 'Email'])->default('SMS')->comment = "Type of notification.";
	    $table->dateTime('notification_sent_date')->useCurrent()->comment = "When notification was sent.";
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
        Schema::dropIfExists('automatic_notifications');
    }
}
