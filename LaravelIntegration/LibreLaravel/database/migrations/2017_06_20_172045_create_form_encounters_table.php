<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormEncountersTable extends Migration
{
    /**
     * Run the migrations.
     * Creates form_encounters table.
     * Stores all patient encounetr's in this table.
     * From UI, create/find patient->New Encounter.
     * 'pc_catid' can be foreign key to libreehr_calendar_categories table. Can be altered later on.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('form_encounters', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
	    $table->integer('facilityID', 0)->unsigned()->comment = "Foreign key to facilities table.";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign key to Patients table.";
	    $table->integer('provider_id', 0)->unsigned()->comment = "Foreign key to users table.";
	    $table->dateTime('date')->comment = "Date of service.";
	    $table->text('reason')->comment = "Description of this encounter.";
	    $table->string('facility')->comment = "Facility Name";
	    $table->integer('encounter', 0)->unsigned()->unique()->comment = "Encounter id. More relevent to reference.";
	    $table->dateTime('onset_date')->useCurrent()->comment = "The date that the patient reports current issue is linked to.";
	    $table->string('sensitivity', 30)->comment = "A flag that restrict access for VIPs or anyone who requests it.";
	    $table->text('billing_note')->nullable()->comment = "An accounting note that applies to just this encounter (but not the patient level, or the line-item level.)";
	    $table->integer('pc_catid', 0)->unsigned()->index()->comment = "The encounter category which is actually from the calendar categories for appointment type.";
	    $table->integer('last_level_billed', 0)->unsigned()->default(0)->comment = "Flag that tells you if billed to Primary, Secondary, Tertiary Insurance etc. This should be an incremental flag as payments get processed.";
	    $table->integer('last_level_closed', 0)->unsigned()->default(0)->comment = "Refer as above.";
	    $table->dateTime('last_stmt_date')->nullable()->comment = "Refer as above.";
	    $table->integer('stmt_count', 0)->unsigned()->comment = "How many statements have been sent out?";
	    $table->integer('supervisor_id', 0)->unsigned()->index()->comment = "Supervising provider. If any for this visit.";
	    $table->integer('ordering_physician', 0)->unsigned()->index()->comment = "Ordering provider. If any for this visit.";
	    $table->integer('referring_physician', 0)->unsigned()->index()->comment = "Referring provider, if any, for this visit.";
	    $table->integer('contract_physician', 0)->unsigned()->index()->comment = "Contract provider, if any, for this visit.";
	    $table->string('invoice_refno', 31)->comment = "Billing stuff.";
	    $table->integer('referal_source', 0)->unsigned()->comment = "Should be an ID from the users table. Can be from an address book entry.";
	    $table->integer('billing_facility', 0)->unsigned()->comment = "Facilities table billing entity.";
	    $table->integer('external_id', 0)->unsigned()->nullable()->comment = "External ID";
	    $table->integer('eft_number', 0)->unsigned()->nullable()->comment = "eft number.";
	    $table->string('claim_number')->nullable()->comment = "Claim Number. Related to insurance.";
	    $table->string('document_image')->nullable()->comment = "Document of patient.";
	    $table->string('seq_number')->nullable()->comment = "Sequence Number.";
	    $table->foreign('facilityID')->references('id')->on('facilities')->onDelete('cascade'); //Foreign key
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade'); //Foreign Key
	    $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade'); //Foreign Key
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
        Schema::dropIfExists('form_encounters');
    }
}
