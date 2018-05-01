<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddrBooksTable extends Migration
{
    /**
     * Run the migrations.
     * This creates user_addr_books table.
     * It stores the information of Administration->Addr Book-><users>
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('user_addr_books', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Auto Increment.";
	    $table->integer('userID')->unsigned()->comment = "Foreign key to linke users table.";
	    $table->string('title', 10)->comment = "Title";
	    $table->string('email')->unique()->comment = "Email Id of user. Earlier we had two fields email and email_direct. Taking one email id.";
	    $table->string('url')->comment = "User website url.";
	    $table->string('assistant')->comment = "Assistant";
	    $table->string('organization')->comment = "User Organization";
	    $table->string('valedictory')->comment = "Field that should be converted to something to store credentials, like M.D., so that you do not get user and provider last names like Gupta M.D.";
	    $table->string('speciality')->comment = "User Speciality like physician, etc.";
	    $table->text('notes')->nullable()->comment = "To store user notes.";
	    $table->string('abook_type')->comment = "Address Book Type";
	    $table->foreign('userID')->references('id')->on('users')->onDelete('cascade'); //Link to users table.
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
        Schema::dropIfExists('user_addr_books');
    }
}
