<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormBronchitisTable extends Migration
{
    /**
     * Run the migrations.
     * This creates form_bronchitis table.
     * From UI, Select Patient -> Encounter -> Miscellaneous -> Bronchitis Form. | Select Patient -> Encounter -> Patient/CLient -> Visit Forms -> Bronchitis Form.
     * @author Priyanshu Sinha <pksinha217@gmail.com> 
     * @return void
     */
    public function up()
    {
        Schema::create('form_bronchitis', function (Blueprint $table) {
            $table->increments('id')->comment = "Autoincrement. Primary Key.";
	    $table->integer('encounter', 0)->unsigned()->comment = "Foreign key to form_encounters table.";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign Key to patient_datas table.";
            $table->integer('userID', 0)->unsigned()->comment = "Foreign key to users table.";
            $table->integer('provider', 0)->unsigned()->index()->comment = "Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet";
            $table->dateTime('date')->comment = "Date when this form filled.";
            $table->boolean('authorized')->default(0)->comment = "Means a clinician (physician, etc...) has verified this form as part of the client record";
            $table->boolean('activity')->default(1)->comment = "A delete flag. 0 -> Yes | 1 -> No";
	    $table->date('bronchitis_date_of_illness')->comment = "Bronchitis date of illness.";
	    $table->text('bronchitis_hpi')->nullable()->comment = "HPI";
	    $table->boolean('bronchitis_ops_fever')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_ops_cough')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_ops_dizziness')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_ops_chest_pain')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_ops_dyspnea')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_ops_sweating')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_ops_wheezing')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_ops_malaise')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_ops_sputum')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->text('bronchitis_ops_appearance')->nullable(0)->comment = "Appearance";
	    $table->boolean('bronchitis_ops_all_reviewed')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_review_of_pmh')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_review_of_allergies')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_review_of_sh')->default(0)->comment = "0 -> No | 1 -> Yes | Social History";
	    $table->boolean('bronchitis_review_of_fh')->default(0)->comment = "0 -> No | 1 -> Yes | Family History";
	    $table->boolean('bronchitis_tms_normal_right')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_tms_normal_left')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_nares_normal_right')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_nares_normal_left')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_tms_thickened_right')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_tms_thickened_left')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_tms_af_level_right')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_tms_af_level_left')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_nares_swelling_right')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_nares_swelling_left')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_nares_discharge_right')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_nares_discharge_left')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_tms_bulging_right')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_tms_bulging_left')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_tms_perforated_right')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_tms_perforated_left')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_tms_nares_not_examined')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_no_sinus_tenderness')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_oropharynx_normal')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_sinus_tenderness_frontal_right')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_sinus_tenderness_frontal_left')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_oropharynx_erythema')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_oropharynx_exudate')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_oropharynx_abcess')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_oropharynx_ulcers')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_sinus_tenderness_maxillary_right')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_sinus_tenderness_maxillary_left')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->text('bronchitis_oropharynx_appearance')->nullable()->comment = "Oropharnyx Appearance";
	    $table->boolean('bronchitis_sinus_tenderness_not_examined')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_oropharynx_not_examined')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_heart_pmi')->default(0)->comment = "0 -> No | 1 -> Yes";	    
	    $table->boolean('bronchitis_heart_s3')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_heart_s4')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_heart_click')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_heart_rub')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->text('bronchitis_heart_murmur')->nullable()->comment = "Murmer";
	    $table->text('bronchitis_heart_grade')->nullable()->comment = "Grade";
	    $table->text('bronchitis_heart_location')->nullable()->comment = "Location";
	    $table->boolean('bronchitis_heart_normal')->default(0)->comment = "0 -> No | 1 -> Yes | Normal Cardiac Exam";
	    $table->boolean('bronchitis_heart_not_examined')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_bs_normal')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_bs_reduced')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_bs_increased')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_crackles_LLL')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_crackles_RLL')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_crackles_BLL')->default(0)->comment = "0 -> No | 1 -> Yes | Bilateral";
	    $table->boolean('bronchitis_lungs_rubs_LLL')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_rubs_RLL')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_rubs_BLL')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_wheezes_LLL')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_wheezes_RLL')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_wheezes_BLL')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->boolean('bronchitis_lungs_wheezes_DLL')->default(0)->comment = "0 -> No | 1 -> Yes";
	    $table->text('bronchitis_diagnostic_tests')->nullable()->comment = "0 -> No | 1 -> Yes";
	    $table->string('diagnosis1_bronchitis_form')->nullable()->comment = "Diagnosis_1";
	    $table->string('diagnosis2_bronchitis_form')->nullable()->comment = "Diagnosis_2";
	    $table->string('diagnosis3_bronchitis_form')->nullable()->comment = "Diagnosis_3";
	    $table->string('diagnosis4_bronchitis_form')->nullable()->comment = "Diagnosis_4";
	    $table->string('bronchitis_additional_diagnosis')->nullable()->comment = "Additional Diagnosis";
	    $table->string('bronchitis_treatment')->nullable()->comment = "Treatment";
	    $table->foreign('encounter')->references('encounter')->on('form_encounters')->onDelete('cascade');
            $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('form_bronchitis');
    }
}
