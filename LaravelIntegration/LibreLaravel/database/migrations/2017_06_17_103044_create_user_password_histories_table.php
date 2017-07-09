<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPasswordHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates user_password_histories.
     * Stores history of password for users. We can check for any number of passwords.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('user_password_histories', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('userID')->unsigned()->comment = "To create link with users table.";
	    $table->string('password')->comment = "Store password.";
	    $table->timestamp('last_update')->useCurrent()->comment = "Store timestamp of last updated password.";
	    $table->foreign('userID')->references('id')->on('users')->onDelete('cascade'); //Foreign key to users table.
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
        Schema::dropIfExists('user_password_histories');
    }
}
