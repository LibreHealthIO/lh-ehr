<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates user_communications table.
     * Stores the information of users communication media like home phone, work phone, fax, etc.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('user_communications', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary key. Autoincrement";
	    $table->integer('userID')->unsigned()->comment = "Used to create link with users table.";
	    $table->string('contact_number')->comment = "Contact number.";
	    $table->integer('type', 0)->comment = "Contact Number Type. 0 -> home_phone | 1 -> work_phone | 2 -> mobile | 3 -> fax | 4 -> alternate_work_phone";
	    $table->foreign('userID')->references('id')->on('users')->onDelete('cascade'); //link with users table.
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
        Schema::dropIfExists('user_communications');
    }
}
