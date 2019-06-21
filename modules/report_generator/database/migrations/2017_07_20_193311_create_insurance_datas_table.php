<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuranceDatasTable extends Migration
{
    /**
     * Run the migrations.
     * This creates insurance_datas table.
     * Stores insurance information of patients.
     * From UI, Add/Edit Patient (Demographics) -> Insurance.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_datas', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
      	    $table->integer('type', 0)->unsigned()->default(0)->comment = "Type of insurance data. 0 -> Primary | 1 -> Secomdary | 2 -> Tertiary";
	    $table->integer('provider', 0)->unsigned()->comment = "Foreign key to insurance_companies table.";
	    $table->integer('subscriber_addr_id', 0)->unsigned()->nullable()->comment = "Address of Subscriber. Foreign key to addresses table.";
	    $table->integer('employer_addr_id', 0)->unsigned()->nullable()->comment = "Address of employer. Foreign key to addresses table.";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign Key to patient_datas table.";
	    $table->string('plan_name')->comment = "Insurance Plan.";
	    $table->string('policy_number')->comment = "Policy Number.";
	    $table->string('group_number')->comment = "Group Number";
	    $table->string('subscriber_lname')->comment = "Last name of subscriber.";
	    $table->string('subscriber_mname')->nullable()->comment = "Middle name of subscriber.";
	    $table->string('subscriber_fname')->comment = "First Name of Subscriber.";
	    $table->string('subscriber_relationship')->comment = "Relationship of subscriber with patient.";
	    $table->date('subscriber_DOB')->comment = "Date of birth of subscriber.";
	    $table->string('subscriber_sex')->comment = "Sex of subscriber.";
	    $table->string('subscriber_phone')->nullable()->comment = "Phone number of subscriber.";
	    $table->string('subscriber_employer')->nullable()->comment = "Employer of subscriber.";
	    $table->string('copay')->nullable()->comment = "Co Pay";
	    $table->date('sDate')->comment = "Start date of insurance.";
	    $table->date('eDate')->comment = "End date of insurance";
	    $table->boolean('accept_assignment')->default(0)->comment = "0 -> False | 1 -> True";
	    $table->string('policy_type')->comment = "Policy Type";
	    $table->boolean('inactive')->default(0)->comment = "Is insurance active? 0 -> False | 1 -> True";
	    $table->dateTime('inactive_time')->useCurrent()->comment = "Time since inactive.";

	    /*Establishing Relationship*/
	    $table->foreign('provider')->references('id')->on('insurance_companies')->onDelete('cascade');
	    $table->foreign('subscriber_addr_id')->references('id')->on('addresses')->onDelete('cascade');
	    $table->foreign('employer_addr_id')->references('id')->on('addresses')->onDelete('cascade');
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
	    $table->index('policy_number');
	    $table->index('sDate');
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
        Schema::dropIfExists('insurance_datas');
    }
}
