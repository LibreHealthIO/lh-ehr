<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcd10GemPcs910sTable extends Migration
{
    /**
     * Run the migrations.
     * This creates icd10_gem_pcs_9_10s table. (Same as icd10_gem_pcs_9_10 table earlier)
     * From UI, Administration -> Other -> External Data Load -> ICD10.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('icd10_gem_pcs_9_10s', function (Blueprint $table) {
	    $table->increments('map_id')->comment = "Primary Key. Autoincrement";
            $table->string('pcs_icd9_source', 7)->nullable()->comment = "ICD 9 Source.";
            $table->string('pcs_icd10_target', 5)->nullable()->comment = "ICD 10 target.";
            $table->string('flags', 5)->nullable()->comment = "Flags";
            $table->boolean('active')->default(0)->comment = "Is code active? 0 -> No  | 1 -> Yes";
            $table->integer('revision', 0)->unsigned()->default(0)->comment = "Revision of code.";
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
        Schema::dropIfExists('icd10_gem_pcs_9_10s');
    }
}
