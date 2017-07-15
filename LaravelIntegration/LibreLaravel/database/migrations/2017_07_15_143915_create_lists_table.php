<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates lists table.
     * It stores medical_problem, surgery, Allergies, Medications details.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('lists', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign Key to patient_datas table.";
	    $table->integer('user', 0)->unsigned()->comment = "Foreign key to users table.";
	    $table->dateTime('date')->useCurrent()->comment = "Timestamp when list created.";
	    $table->string('type')->comment = "Type of list. Medications, Allergies, Surgery, Medical Problems, etc.";
	    $table->string('title')->comment = "Title of that particular type.";
	    $table->date('begdate')->comment = "Date of beginning of issue.";
	    $table->date('enddate')->nullable()->comment = "Date of end of this issue. Null if still active.";
	    $table->integer('occurrence', 0)->unsigned()->default(0)->comment = "Occurence of this issue. Recurrence, First, Early Recurrence, Late Recurrence, and Acute on Chronic.";
	    $table->string('referredby')->nullable()->comment = "Who referred this issue.";
	    $table->boolean('activity')->default(0)->comment = "Still Active. 0 -> No | 1 -> Yes";
	    $table->text('comments')->nullable()->comment = "Comment about that issue.";
	    $table->integer('outcome', 0)->unsigned()->default(0)->comment = "Outcome of issue.";
	    $table->string('destination')->nullable()->comment = "destination.";
	    $table->string('reaction')->comment = "Reaction of that issue.";
	    $table->integer('external_allergyid', 0)->unsigned()->nullable()->comment = "External ERX Id.";
	    $table->boolean('erx_source')->default(0)->comment = "0 -> LibreEHR | 1 -> External";
	    $table->boolean('erx_uploaded')->default(0)->comment = "0 -> Pending to NewCrop upload | 1 -> Uploaded to NewCrop";
	    $table->dateTime('modifydate')->useCurrent()->comment = "Timestamp when issue modified.";
	    $table->string('severity_al')->comment = "Severity Level.";
	    $table->integer('external_id', 0)->unsigned()->nullable()->comment = "To hold an ID number from some other system, such as another EHR, an assigned ID that exists on a proprietary medical device or the like.";
	    
	    /*Establishing Relationship*/
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
        Schema::dropIfExists('lists');
    }
}
