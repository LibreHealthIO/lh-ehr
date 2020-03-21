<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtendedLogsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates extended_logs table.
     * It stores diclosures of patients.
     * From UI, Select Patient -> Disclosures (edit).
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('extended_logs', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign Key to patient_datas table.";
	    $table->integer('user', 0)->unsigned()->comment = "Foreign Key to users table.";
	    $table->dateTime('date')->useCurrent()->comment = "Date when disclosure recorded.";
	    $table->string('event')->comment = "Type of Disclosure";
	    $table->string('recipient')->nullable()->comment = "Recipient of disclosure.";
	    $table->text('description')->nullable()->comment = "Description of Disclosure.";
	    
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
        Schema::dropIfExists('extended_logs');
    }
}
