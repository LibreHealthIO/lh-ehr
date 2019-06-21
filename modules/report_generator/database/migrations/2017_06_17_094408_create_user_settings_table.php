<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     * Creates user_settings table.
     * Contain the settings of user.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary key. Autoincremement.";
	    $table->integer('userID', 0)->unsigned()->comment = "To create foreign key to users table";
	    $table->string('setting_label')->comment = "Setting Label";
	    $table->integer('setting_value', 0)->comment = "Setting Value";
   	    $table->foreign('userID')->references('id')->on('users')->onDelete('cascade'); //Foreign Key to Users table.
	    $table->index('setting_label'); //Create index on setting_label.
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
        Schema::dropIfExists('user_settings');
    }
}
