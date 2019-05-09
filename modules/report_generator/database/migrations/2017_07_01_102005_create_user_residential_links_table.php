<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserResidentialLinksTable extends Migration
{
    /**
     * Run the migrations.
     * This will create user_residential_links table.
     * This stores the address information of user. Many to many relationship with users and addresses table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('user_residential_links', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
	    $table->integer('addressID')->unsigned()->comment = "To create link with addresses table.";
  	    $table->integer('userID')->unsigned()->comment = "To create link with user_infos table.";
	    $table->integer('type', 0)->default(0)->comment = "What type of address is? 0 -> primary address | 1 -> alternate address.";
	    $table->foreign('addressID')->references('id')->on('addresses')->onDelete('cascade'); //Link with addresses table.
	    $table->foreign('userID')->references('id')->on('users')->onDelete('cascade'); //Link with users table. 
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
        Schema::dropIfExists('user_residential_links');
    }
}
