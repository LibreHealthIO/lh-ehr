<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormReviewofsTable extends Migration
{
    /**
     * Run the migrations.
     * This creates form_reviewofs table.
     * From UI, Select Patient -> Encounter -> Clinical -> Review of System Checks. | Select Patient -> Encounter -> Patient/CLient -> Visit Forms -> Review of System Checks.
     * For Boolean Fields : 0 -> No | 1 -> Yes
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('form_reviewofs', function (Blueprint $table) {
            $table->increments('id')->comment = "Autoincrement. Primary Key";
	    $table->integer('encounter', 0)->unsigned()->comment = "Foreign key to form_encounters table.";
            $table->integer('pid', 0)->unsigned()->comment = "Foreign Key to patient_datas table.";
            $table->integer('userID', 0)->unsigned()->comment = "Foreign key to users table.";
            $table->integer('provider', 0)->unsigned()->index()->comment = "Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet";
            $table->dateTime('date')->comment = "Date when this form filled.";
            $table->boolean('authorized')->default(0)->comment = "Means a clinician (physician, etc...) has verified this form as part of the client record";
            $table->boolean('activity')->default(1)->comment = "A delete flag. 0 -> Yes | 1 -> No";
	    /*General*/
	    $table->boolean('fever')->default(0);
	    $table->boolean('chills')->default(0);
	    $table->boolean('night_sweats')->default(0);
	    $table->boolean('weight_loss')->default(0);
	    $table->boolean('poor_appetite')->default(0);
	    $table->boolean('insomnia')->default(0);
	    $table->boolean('fatigued')->default(0);
	    $table->boolean('depressed')->default(0);
	    $table->boolean('hyperactive')->default(0);
	    $table->boolean('exposure_to_foreign_countries')->default(0);

	    /*Heent*/
            $table->boolean('cataracts')->default(0);
	    $table->boolean('cataract_surgery');
	    $table->boolean('glaucoma')->default(0);
	    $table->boolean('double_vision')->default(0);
	    $table->boolean('blurred_vision')->default(0);
	    $table->boolean('poor_hearing')->default(0);
	    $table->boolean('headaches')->default(0);
	    $table->boolean('ringing_in_ears')->default(0);
	    $table->boolean('bloody_nose')->default(0);
	    $table->boolean('sinusitis')->default(0);
	    $table->boolean('sinus_surgery')->default(0);
	    $table->boolean('dry_mouth')->default(0);
	    $table->boolean('strep_throat')->default(0);
	    $table->boolean('tonsillectomy')->default(0);
	    $table->boolean('swollen_lymph_nodes')->default(0);
	    $table->boolean('throat_cancer')->default(0);
	    $table->boolean('throat_cancer_surgery')->default(0);

	   /*Cardiovascular*/
	    $table->boolean('heart_attack')->default(0);
	    $table->boolean('irregular_heart_beat')->default(0);
	    $table->boolean('chest_pains')->default(0);
	    $table->boolean('shortness_of_breath')->default(0);
	    $table->boolean('high_blood_pressure')->default(0);
	    $table->boolean('heart_failure')->default(0);
	    $table->boolean('poor_circulation')->default(0);
	    $table->boolean('vascular_surgery')->default(0);
	    $table->boolean('cardiac_catheterization')->default(0);
	    $table->boolean('coronary_artery_bypass')->default(0);
	    $table->boolean('heart_transplant')->default(0);
	    $table->boolean('stress_test')->default(0);

	    /*Pulmonary*/
	    $table->boolean('emphysema')->default(0);
	    $table->boolean('chronic_bronchitis')->default(0);
	    $table->boolean('interstitial_lung_disease')->default(0);
	    $table->boolean('shortness_of_breath_2')->default(0);
	    $table->boolean('lung_cancer')->default(0);
	    $table->boolean('lung_cancer_surgery')->default(0);
	    $table->boolean('pheumothorax')->default(0);

	    /*Gastrointestinal*/
	    $table->boolean('stomach_pains')->default(0);
	    $table->boolean('peptic_ulcer_disease')->default(0);
	    $table->boolean('gastritis')->default(0);
	    $table->boolean('endoscopy')->default(0);
	    $table->boolean('polyps')->default(0);
	    $table->boolean('colonoscopy')->default(0);
	    $table->boolean('colon_cancer')->default(0);
	    $table->boolean('colon_cancer_surgery')->default(0);
	    $table->boolean('ulcerative_colitis')->default(0);
	    $table->boolean('crohns_disease')->default(0);
	    $table->boolean('appendectomy')->default(0);
	    $table->boolean('divirticulitis')->default(0);
	    $table->boolean('divirticulitis_surgery')->default(0);
	    $table->boolean('gall_stones')->default(0);
	    $table->boolean('cholecystectomy')->default(0);
	    $table->boolean('hepatitis')->default(0);
	    $table->boolean('cirrhosis_of_the_liver')->default(0);
	    $table->boolean('splenectomy')->default(0);

	    /*Genitourinary*/
	    $table->boolean('kidney_failure')->default(0);
	    $table->boolean('kidney_stones')->default(0);
	    $table->boolean('kidney_cancer')->default(0);
	    $table->boolean('kidney_infections')->default(0);
	    $table->boolean('bladder_infections')->default(0);
	    $table->boolean('bladder_cancer')->default(0);
	    $table->boolean('prostate_problems')->default(0);
	    $table->boolean('prostate_cancer')->default(0);
	    $table->boolean('kidney_transplant')->default(0);
	    $table->boolean('sexually_transmitted_disease')->default(0);
	    $table->boolean('burning_with_urination')->default(0);
	    $table->boolean('discharge_from_urethra')->default(0);

	    /*Skin*/
	    $table->boolean('rashes')->default(0);
	    $table->boolean('infections')->default(0);
	    $table->boolean('ulcerations')->default(0);
	    $table->boolean('pemphigus')->default(0);
	    $table->boolean('herpes')->default(0);

	    /*Musculoskeletal*/
	    $table->boolean('osetoarthritis')->default(0);
	    $table->boolean('rheumotoid_arthritis')->default(0);
	    $table->boolean('lupus')->default(0);
	    $table->boolean('ankylosing_sondlilitis')->default(0);
	    $table->boolean('swollen_joints')->default(0);
	    $table->boolean('stiff_joints')->default(0);
	    $table->boolean('broken_bones')->default(0);
	    $table->boolean('neck_problems')->default(0);
	    $table->boolean('back_problems')->default(0);
	    $table->boolean('back_surgery')->default(0);
	    $table->boolean('scoliosis')->default(0);
	    $table->boolean('herniated_disc')->default(0);
	    $table->boolean('shoulder_problems')->default(0);
	    $table->boolean('elbow_problems')->default(0);
	    $table->boolean('wrist_problems')->default(0);
	    $table->boolean('hand_problems')->default(0);
	    $table->boolean('hip_problems')->default(0);
	    $table->boolean('knee_problems')->default(0);
	    $table->boolean('ankle_problems')->default(0);
	    $table->boolean('foot_problems')->default(0);	   

	    /*Endocrine*/
	    $table->boolean('insulin_dependent_diabetes')->default(0);
	    $table->boolean('noninsulin_dependent_diabetes')->default(0);
	    $table->boolean('hypothyroidism')->default(0);
	    $table->boolean('hyperthyroidism')->default(0);
	    $table->boolean('cushing_syndrom')->default(0);
	    $table->boolean('addison_syndrom')->default(0);
	    
	    /*Additional Notes*/
	    $table->text('additional_notes')->nullable()->comment = "Notes"; 

	    /*Establish Relationship*/
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
        Schema::dropIfExists('form_reviewofs');
    }
}
