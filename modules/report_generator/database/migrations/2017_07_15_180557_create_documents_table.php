<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates documents table.
     * From UI, Select Patient -> Documents.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id')->comment = "Auto increment. Primary Key.";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign key to patient_datas table. Earlier foreign_id.";
	    $table->integer('owner', 0)->unsigned()->comment = "Foreign key to users table.";
	    $table->integer('list_id', 0)->unsigned()->nullable()->comment = "Foreign key to lists table.";
	    $table->integer('encounter_id', 0)->unsigned()->nullable()->comment = "Foreign key to form_encounters table.";
	    $table->integer('audit_master_id', 0)->unsigned()->nullable()->comment = "Foreign key to audit_masters table.";
	    $table->string('url')->comment = "Path of uploaded file.";
	    $table->string('mimetype')->comment = "Type of uploaded file. Image or text.";
	    $table->dateTime('revision')->useCurrent()->comment = "Timestamp when document was revised.";
	    $table->date('docdate')->comment = "When document was uploaded.";
	    $table->string('hash')->comment = "40 character SHA-1 hash of document.";
	    $table->boolean('imported')->default(0)->comment = "Parsing status for CCR/CCD/CCDA importing. 0 -> False | 1 -> True";
	    $table->boolean('encounter_check')->default(0)->comment = "If encounter is created while tagging. 0 -> No | 1 -> Yes";
	    $table->text('notes')->nullable()->comment = "Notes related to docuements."; /*This will remove the need of notes table.*/
	    $table->tinyInteger('audit_master_approval_status')->default(1)->comment = "approval_status from audit_master table.";

	    /*Establishing Relationships.*/
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
	    $table->foreign('owner')->references('id')->on('users')->onDelete('cascade');
	    $table->foreign('list_id')->references('id')->on('lists')->onDelete('cascade');
	    $table->foreign('encounter_id')->references('id')->on('form_encounters')->onDelete('cascade');
	    $table->foreign('audit_master_id')->references('id')->on('audit_masters')->onDelete('cascade');
 
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
        Schema::dropIfExists('documents');
    }
}
