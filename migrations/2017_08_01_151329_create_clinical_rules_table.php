<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicalRulesTable extends Migration
{
    /**
     * Run the migrations.
     * Creates table for clinical_rules
     * @author Chandima Jayawickrama
     * @return void
     */
    public function up()
    {
        Schema::creat('clinical_rules',function(Blueprint $table){
			$table->engine = 'InnoDB';
			$table->string('id',31)->comment = "Unique and maps to list_options list clinical_rules";
			$table->bigInteger('pid')->comment = "0 is default for all patients, while>0 is id from patient_data table";
			$table->boolean('active_alert_flag')->comment = "Active Alert Widget Module flag - note not yet utilized";
			$table->boolean('passive_alert_flag')->comment = "Passive Alert Widget Module flag";
			$table->boolean('cqm_flag')->comment = "Clinical Quality Measure flag (unable to customize per patient)";
			$table->boolean('cqm_2011_flag')->comment = "2011 Clinical Quality Measure flag (unable to customize per patient)";
			$table->boolean('cqm_2014_flag')->comment = "2014 Clinical Quality Measure flag (unable to customize per patient)";
			$table->string('cqm_nqf_code',10)->comment = "Clinical Quality Measure NQF identifier";
			$table->string('cqm_pqri_code',10)->comment = "Clinical Quality Measure PQRI identifier";
			$table->boolean('amc_flag')->comment = "Automated Measure Calculation flag (unable to customize per patient)";
			$table->boolean('amc_2011_flag')->comment = "2011 Automated Measure Calculation flag for (unable to cusstomize per patient)";
			$table->boolean('amc_2014_flag')->comment = "2014 Automated Measure Calculation flag for (unable to cusstomize per patient)";
			$table->string('amc_code',10)->comment = "Automated Measure Calculation identifier (MU rule)";
			$table->string('amc_code_2014',30)->comment = "Automated Measure Calculation 2014 identifier (MU rule)";
			$table->boolean('amc_2014_stage1_flag')->comment = "2014 stage 1 - Automated Measure Calculation identifier (MU rule)";
			$table->boolean('amc_2014_stage2_flag')->comment = "2014 stage 2 - Automated Measure Calculation identifier (MU rule)";
			$table->boolean('patient_reminder_flag')->comment = "Clinical Reminder Module flag ";
			$table->string('developer',255)->comment = "Clinical Rule Developer";
			$table->string('funding_source',255)->comment ="Clinical Rule funcding Source" ;
			$table->string('release_version',255)->comment = "Clinical Rule Release Version";
			$table->string('web_reference',255)->comment = "Clinical Rule Web Reference";
			$table->string('access_control',255)->comment = "ACO link for access control";
			$table->primary(array('id','pid'));
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clinical_rules');
    }
}
