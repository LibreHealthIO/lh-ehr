<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateX12PartnersTable extends Migration
{
    /**
     * Run the migrations.
     * This creates x12_partners table.
     * From UI, Administration -> Practice -> x12 Partners. 
     * Related to insurance companies.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('x12_partners', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement";
	    $table->string('name')->comment = "x12 Partner Name";
	    $table->string('id_number')->nullable()->comment = "ID Number (ETIN)";
	    $table->string('x12_sender_id')->nullable()->comment = "X12 Sender ID. ISA 06";
	    $table->string('x12_receiver_id')->nullable()->comment = "X12 Receiver ID. ISA 08";
	    $table->string('x12_version')->comment = "Version";
	    $table->enum('processing_format', ['standard', 'medi-cal', 'cms', 'proxymed'])->comment = "Processing Format";
	    $table->string('x12_isa_01', 2)->default('00')->comment = "User Logon Required Indicator";
	    $table->string('x12_isa_02')->nullable()->comment = "User Logon. If 03 in x12_isa_01.";
	    $table->string('x12_isa_03', 2)->default('00')->comment = "User Password required indicator.";
	    $table->string('x12_isa_04')->nullable()->comment = "User Password. If 01 in x12_isa_03.";
	    $table->string('x12_isa_05', 2)->default('ZZ')->comment = "Sender Id Qualifier.";
	    $table->string('x12_isa_07', 2)->default('ZZ')->comment = "Receiver Id Qualifier";
	    $table->boolean('x12_isa_14')->default(0)->comment = "Acknowledgement Requested. 0 -> No | 1 -> Yes";
	    $table->string('x12_isa_15', 1)->default('P')->comment = "Usage Indicator. viz Production(P) and Testing(T)";
	    $table->string('x12_gs_02')->comment = "Application Sender Code";
	    $table->string('x12_per_06')->comment = "Submitted EDI Access No.";
	    $table->string('x12_gs_03')->comment = "Application Receiver Code";
	    /*These indexes can be altered later on while during query execution.*/
	    $table->index('x12_sender_id');
	    $table->index('x12_receiver_id');
	    $table->index('id_number');
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
        Schema::dropIfExists('x12_partners');
    }
}
