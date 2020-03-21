<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcd10PcsOrderCodesTable extends Migration
{
    /**
     * Run the migrations.
     * This creates icd10_pcs_order_codes table. (Same as icd10_pcs_order_code table earlier.)
     * From UI, Administration -> Other -> External Data Load -> ICD10.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('icd10_pcs_order_codes', function (Blueprint $table) {
            $table->increments('pcs_id')->comment = "Primary Key. Autoincrement";
            $table->string('pcs_code', 7)->nullable()->comment = "ICD10 PCS code";
            $table->char('valid_for_coding', 1)->nullable()->comment = "Is it valid for coding?";
            $table->string('short_desc', 60)->nullable()->comment = "Short description of that code.";
            $table->string('long_desc')->nullable()->comment = "Long description of that code.";
            $table->boolean('active')->default(0)->comment = "Is code Active? 0 -> No | 1 -> Yes";
            $table->integer('revision', 0)->unsigned()->default(0)->comment = "Revision of code.";

            /*Creating Indexes*/
            $table->index('pcs_code');
            $table->index('active');

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
        Schema::dropIfExists('icd10_pcs_order_codes');
    }
}
