<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcd10ReimbrPcs910sTable extends Migration
{
    /**
     * Run the migrations.
     * This structure can be modified or dropped later on.
     * This creates icd10_reimbr_pcs_9_10s table. (Same as icd10_reimbr_pcs_9_10 table.)
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('icd10_reimbr_pcs_9_10s', function (Blueprint $table) {
            $table->increments('map_id')->comment = "Primary Key. Autoincrement";
            $table->string('code', 8)->nullable()->comment = "Code.";
            $table->tinyInteger('code_cnt')->nullable()->comment = "";
            $table->string('ICD9_01', 5)->nullable()->comment = "";
            $table->string('ICD9_02', 5)->nullable()->comment = "";
            $table->string('ICD9_03', 5)->nullable()->comment = "";
            $table->string('ICD9_04', 5)->nullable()->comment = "";
            $table->string('ICD9_05', 5)->nullable()->comment = "";
            $table->string('ICD9_06', 5)->nullable()->comment = "";
            $table->boolean('active')->default(0)->comment = "Is code active? 0 -> No | 1 -> Yes";
            $table->integer('revision', 0)->unsigned()->default(0)->comment = "Code Revision";
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
        Schema::dropIfExists('icd10_reimbr_pcs_9_10s');
    }
}
