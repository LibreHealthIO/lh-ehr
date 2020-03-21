<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImmunizationsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates immunizations table.
     * It stores immunizations related information of patients.
     * From UI, Select Patient -> Immunizations.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('immunizations', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign key to patient_datas table.";
	    $table->integer('administered_by', 0)->unsigned()->comment = "Foreign key to users table.";
	    $table->integer('created_by', 0)->unsigned()->comment = "User who created record. Foreign key to users table.";
	    $table->integer('updated_by', 0)->unsigned()->comment = "User who updated record. Foreign key to users table.";
	    $table->dateTime('administered_date')->useCurrent()->comment = "Date when administered.";
	    //$table->integer('immunization_id', 0)->unsigned()->comment = "Immunization ID"; /* Uncomment this to use.*/
	    $table->integer('cvx_code', 0)->unsigned()->comment = "CVX Code Number";
	    $table->string('manufacturer', 100)->nullable()->comment = "Manufacturer";
	    $table->string('lot_number', 50)->nullable()->unique()->comment = "LOT Number of Vaccine.";
	    $table->date('education_date')->nullable()->comment = "Date when Immunization Information Statements Given.";
	    $table->date('vis_date')->nullable()->comment = "Date of VIS statement.";
	    $table->text('note')->nullable()->comment = "Note about immunization.";
	    $table->decimal('amount_administered', 5, 2)->default(0.00)->comment = "Amount of vaccine administered.";
	    $table->string('amount_administered_unit', 50)->default('mg')->comment = "Unit in which vaccine administered.";
	    $table->date('expiration_date')->nullable()->comment = "Date when vaccine expires.";
	    $table->string('route', 100)->nullable()->comment = "Route for administration.";
	    $table->string('administration_site', 100)->nullable()->comment = "Adminstration Site";
	    $table->boolean('added_erroneously')->default(0)->comment = "0 -> False | 1 -> True";
	    $table->integer('external_id', 0)->unsigned()->nullable()->comment = "To hold an ID number from some other system, such as another EHR, an assigned ID that exists on a proprietary medical device or the like.";
	    $table->integer('status', 0)->unsigned()->default(0)->comment = "0 -> None | 1 -> Completed | 2 -> Refused | 3 -> Not Administered | 4 -> Partially Administered";
	    $table->string('information_source', 31)->nullable()->comment = "Information Source"; //Future use
	    $table->string('refusal_reason', 31)->nullable()->comment = "Refusal Reason"; //Future Use
	    $table->integer('ordering_provider', 0)->unsigned()->nullable()->comment = "Ordering Provider"; //future use. It can be foreign key to users.

	    /*Esatblishing relationships*/
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
	    $table->foreign('administered_by')->references('id')->on('users')->onDelete('cascade');
	    $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
	    $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('immunizations');
    }
}
