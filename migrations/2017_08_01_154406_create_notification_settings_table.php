<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for notification_settings
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('notification_settings', function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->increment('SettingsId')->comment = "Primary key, auto increment";
			$table->integer('Send_SMS_Before_Hours');
			$table->integer('Send_Email_Before_Hours');
			$table->string('SMS_gateway_username',100);
			$table->string('SMS_gateway_password',100);
			$table->string('SMS_gateway_apikey',100);
			$table->string('type',50);
			$table->primary('SettingsId');
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notification_settings'); 
		}
}
