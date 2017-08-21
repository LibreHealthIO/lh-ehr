<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates facilities table. 
     * This stores all the facility related stuffs for patient, user and others.
     * Address is stored in addresses table and that is linked with this table.
     * From UI Administration->Facilities->Add.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('addressID', 0)->unsigned()->comment = "Foreign key to addresses table.";
	    $table->string('name')->comment = "Facility Name";
	    $table->string('federal_ein')->comment = "Tax Identifier for the business.";
	    $table->string('phone', 10)->comment = "Phone Number";
	    $table->string('fax', 10)->comment = "Fax Number";
	    $table->string('website')->comment = "Web Site";
	    $table->string('email')->comment = "Email Id";
	    $table->boolean('service_location')->default(0)->comment = "Type of facility that shows up in the encounter form as a Service Location.";
	    $table->boolean('billing_location')->default(0)->comment = "Shows up in the form_encounter in the dropdown for Billing Facility.";
	    $table->boolean('accept_assignment')->default(0)->comment = "Flag to control payments processing.";
	    $table->integer('pos_code', 0)->unsigned()->comment = "Vital code that is pulled from the Service Facility in an encounter that indicates what type of place of service it is, such as Office, Inpatient Hospital, Home, Mental Health Facility, etc.";
	    $table->string('attn')->comment = "Field of value like Claims Department, or John the Billing Desk Guy";
	    $table->string('domain_identifier')->comment = "";
	    $table->string('facility_npi')->comment = "Defines Group National Provider Identifier, or a kind of UUID.";
	    $table->string('tax_id_type', 2)->comment = "Indicates that if it is a Employer ID Number or Personal Tax Number.";
	    $table->string('color', 7)->comment = "To mark the physical location of a appointment is so the user can visually sort them.";
	    $table->boolean('primary_business_entity')->default(0)->comment = "Identifies if this facility is a listing for the actual business running everything. 0 -> False | 1 -> True";
	    $table->foreign('addressID')->references('id')->on('addresses')->onDelete('cascade');
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
        Schema::dropIfExists('facilities');
    }
}
