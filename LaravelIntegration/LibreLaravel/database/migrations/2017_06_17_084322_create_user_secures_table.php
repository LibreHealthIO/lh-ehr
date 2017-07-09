<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSecuresTable extends Migration
{
    /**
     * Run the migrations.
     * This will create user_secures. 
     * It will contain the confidential information of user like password, isActive, isAuthorized, etc.
     * It is to be noticed that, it do not contain salt field.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('user_secures', function (Blueprint $table) {
            $table->increments('id')->comment = "Auto increment primary key of table.";
	    $table->integer('userID')->unsigned()->comment = "To be used as foreign key to users.";
            $table->string('username')->unique()->comment = "username of user.";
            $table->string('password')->comment = "Password of user.";
	    $table->boolean('active')->default(0)->comment = "Is user active? 0 -> No, 1 -> Yes.";
	    $table->boolean('authorized')->default(0)->comment = "Is user authorised? 0 -> No, 1 -> Yes.";
	    $table->timestamp('pwd_expiration_date')->useCurrent()->comment = "Set password expiration date. Initially it is set from globals.";
	    $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->rememberToken()->comment = "Remember me token. Can be dropped if not used.";
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
        Schema::dropIfExists('user_secures');
    }
}
