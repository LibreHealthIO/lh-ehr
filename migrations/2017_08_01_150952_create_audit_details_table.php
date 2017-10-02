<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditDetailsTable extends Migration
{
    /**
     * Run the migrations.
     * Creates database table for the audit_details
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::create('audit_details',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->bigIncrement('id')->comment = "Libreehr table's field name";
			$table->string('table_name',100)->commnet = "Libreehr table's field value";
			$table->string('field_name',100);
			$table->text('field_value');
			$table->bigInteger('audit_master_id');
			$table->string('entry_identification',255)->comment = "Used when multiple entry occurs from te same table. 1 means no multiple entry";
			$table->primary('id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('audit_details');
    }
}
