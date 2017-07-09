<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates forms table.
     * Used to store forms related to patient encounter.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->dateTime('date')->comment = "Date when a new form is registered with that particular user encounter.";
	    $table->integer('encounter', 0)->unsigned()->comment = "Foreign key to encounter table.";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign key to patients table.";
	    $table->integer('userID', 0)->unsigned()->comment = "Foreign key to users table. User who is registering form.";
	    $table->string('form_name')->comment = "Name of form, like bronchitis, ankel injury, etc.";
	    $table->integer('form_id', 0)->unsigned()->index()->comment = "Id of form which is related to that encounter. Basically this is like, an encounter for a particular patient can have many forms and a single form can be related to other patient as well.";
	    $table->boolean('authorized')->default(0)->comment = "Is form authorized by physician or doctor? 0 -> No, 1 -> yes";
	    $table->boolean('deleted')->default(0)->comment = "Is form deleted from patient encounter? 0 -> No, 1 -> yes";
	    $table->text('formdir')->nullable()->comment = "Directory of form.";
	    $table->foreign('encounter')->references('encounter')->on('form_encounters')->onDelete('cascade');
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
	    $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('forms');
    }
}
