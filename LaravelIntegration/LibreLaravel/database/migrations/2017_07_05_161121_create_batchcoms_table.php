<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchcomsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates batchcoms table. Earlier (batchcom).
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('batchcoms', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign key to patient_datas table.";
	    $table->integer('sent_by', 0)->unsigned()->comment = "Foreign key to users table.";
	    $table->string('msg_type')->comment = "Message Type";
	    $table->string('msg_subject')->nullable()->comment = "Subject";
	    $table->string('msg_text')->nullable()->comment = "Message to be sent.";
	    $table->dateTime('msg_date_sent')->useCurrent()->comment = "Timestamp when message was sent.";
	    /*Linking Relationships*/
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
	    $table->foreign('sent_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('batchcoms');
    }
}
