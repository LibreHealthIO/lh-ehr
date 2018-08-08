<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuranceCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates insurance_companies table.
     * From UI, Adminstration -> Practice -> Insurance Companies.
     * If one insurance company have many x12_partners then create one table insurance_partner_link. Remember It.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_companies', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->integer('addressID', 0)->unsigned()->comment = "Foreign Key to addresses table.";
	    $table->integer('x12_default_partner_id', 0)->unsigned()->comment = "Foreign key to x12_partners. The real clearinghouse partner key, and is related to cms_id";
	    $table->string('attn')->nullable()->comment = "Attn. Eg : Billing Department";
	    $table->string('cms_id')->comment = "Insurance company identifier supplied by x12_default_partner published list.  NOT a UUID like an NPI or tax number.  Refer to Clearinghouse Payer List for value.";
	    $table->integer('ins_type_code', 0)->unsigned()->nullable()->comment = "Payer Type ID";

	    /*Linking with addresses and x12_partners.*/
	    $table->foreign('addressID')->references('id')->on('addresses')->onDelete('cascade');
	    $table->foreign('x12_default_partner_id')->references('id')->on('x12_partners')->onDelete('cascade');
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
        Schema::dropIfExists('insurance_companies');
    }
}
