<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates transactions table.
     * Stores all transactions except referral related.
     * From UI, Select Patient -> Transactions.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign key to patient_datas table";
            $table->integer('user', 0)->unsigned()->comment = "Foreign key to users table.";
            $table->boolean('authorized')->default(0)->comment = "0 -> False | 1 -> True";
	    $table->json('data')->comment = "Data of other transactions.";
	    /**
	     * Eg : 
	     * [
	     *	{
	     *		type : patient_request,
	     *		data : <body content>,
	     *		<user_created_field> : <value>
	     *	}
	     * ]
	     */
	    
	    /*Establishing Relationships*/
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('transactions');
    }
}
