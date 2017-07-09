<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormRosTable extends Migration
{
    /**
     * Run the migrations.
     * This creates form_ros table.
     * From UI, Select Patient -> Encounter -> Clinical -> Review of Systems. | Select Patient -> Encounter -> Patient/CLient -> Visit Forms -> Review of Systems.
     * For Integer fields : 0 -> N/A | 1 -> Yes | 2 -> No
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('form_ros', function (Blueprint $table) {
            $table->increments('id')->comment = "Autoincrement Primary Key";
	    $table->integer('encounter', 0)->unsigned()->comment = "Foreign key to form_encounters table.";
            $table->integer('pid', 0)->unsigned()->comment = "Foreign Key to patient_datas table.";
            $table->integer('userID', 0)->unsigned()->comment = "Foreign key to users table.";
            $table->integer('provider', 0)->unsigned()->index()->comment = "Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet";
            $table->dateTime('date')->comment = "Date when this form filled.";
            $table->boolean('authorized')->default(0)->comment = "Means a clinician (physician, etc...) has verified this form as part of the client record";
            $table->boolean('activity')->default(1)->comment = "A delete flag. 0 -> Yes | 1 -> No";
	    
	    /*Constitutional*/
	    $table->integer('weight_change', 0)->unsigned()->default(0);
	    $table->integer('weakness', 0)->unsigned()->default(0);
	    $table->integer('fatigue', 0)->unsigned()->default(0);
	    $table->integer('anorexia', 0)->unsigned()->default(0);
	    $table->integer('fever', 0)->unsigned()->default(0);
	    $table->integer('chills', 0)->unsigned()->default(0);
	    $table->integer('night_sweats', 0)->unsigned()->default(0);
	    $table->integer('insomnia', 0)->unsigned()->default(0);
	    $table->integer('irritability', 0)->unsigned()->default(0);
	    $table->integer('heat_or_cold', 0)->unsigned()->default(0);
	    $table->integer('intolerance', 0)->unsigned()->default(0);

	    /*Eyes*/
	    $table->integer('change_in_vision', 0)->unsigned()->default(0);
	    $table->integer('glaucoma_history', 0)->unsigned()->default(0);
	    $table->integer('eye_pain', 0)->unsigned()->default(0);
	    $table->integer('irritation', 0)->unsigned()->default(0);
	    $table->integer('redness', 0)->unsigned()->default(0);
	    $table->integer('excessive_tearing', 0)->unsigned()->default(0);
	    $table->integer('double_vision', 0)->unsigned()->default(0);
	    $table->integer('blind_spots', 0)->unsigned()->default(0);
	    $table->integer('photophobia', 0)->unsigned()->default(0);

	    /*Ears, Nose, Mouth, Throat*/
	    $table->integer('hearing_loss', 0)->unsigned()->default(0);
	    $table->integer('discharge', 0)->unsigned()->default(0);
	    $table->integer('pain', 0)->unsigned()->default(0);
	    $table->integer('vertigo', 0)->unsigned()->default(0);
	    $table->integer('tinnitus', 0)->unsigned()->default(0);
	    $table->integer('frequent_colds', 0)->unsigned()->default(0);
	    $table->integer('sore_throat', 0)->unsigned()->default(0);
	    $table->integer('sinus_problems', 0)->unsigned()->default(0);
	    $table->integer('post_nasal_drip', 0)->unsigned()->default(0);
	    $table->integer('nosebleed', 0)->unsigned()->default(0);
	    $table->integer('snoring', 0)->unsigned()->default(0);
	    $table->integer('apnea', 0)->unsigned()->default(0);

	    /*Breast*/
	    $table->integer('breast_mass', 0)->unsigned()->default(0);
	    $table->integer('breast_discharge', 0)->unsigned()->default(0);
	    $table->integer('biopsy', 0)->unsigned()->default(0);
	    $table->integer('abnormal_mammogram', 0)->unsigned()->default(0);

	    /*Respiratory*/
	    $table->integer('cough', 0)->unsigned()->default(0);
	    $table->integer('sputum', 0)->unsigned()->default(0);
	    $table->integer('shortness_of_breath', 0)->unsigned()->default(0);
	    $table->integer('wheezing', 0)->unsigned()->default(0);
	    $table->integer('hemoptsyis', 0)->unsigned()->default(0);
	    $table->integer('asthma', 0)->unsigned()->default(0);
	    $table->integer('copd', 0)->unsigned()->default(0);

	    /*Cardiovascular*/
	    $table->integer('chest_pain', 0)->unsigned()->default(0);
	    $table->integer('palpitation', 0)->unsigned()->default(0);
	    $table->integer('syncope', 0)->unsigned()->default(0);
	    $table->integer('pnd', 0)->unsigned()->default(0);
	    $table->integer('doe', 0)->unsigned()->default(0);
	    $table->integer('orthopnea', 0)->unsigned()->default(0);
	    $table->integer('peripheal', 0)->unsigned()->default(0);
	    $table->integer('edema', 0)->unsigned()->default(0);
	    $table->integer('legpain_cramping', 0)->unsigned()->default(0);
	    $table->integer('history_murmur', 0)->unsigned()->default(0);
	    $table->integer('arrythmia', 0)->unsigned()->default(0);
	    $table->integer('heart_problem', 0)->unsigned()->default(0);

	    /*Gastrointestinal*/
	    $table->integer('dysphagia', 0)->unsigned()->default(0);
	    $table->integer('heartburn', 0)->unsigned()->default(0);
	    $table->integer('bloating', 0)->unsigned()->default(0);
	    $table->integer('belching', 0)->unsigned()->default(0);
	    $table->integer('flatulence', 0)->unsigned()->default(0);
	    $table->integer('nausea', 0)->unsigned()->default(0);
	    $table->integer('vomiting', 0)->unsigned()->default(0);
	    $table->integer('hematemesis', 0)->unsigned()->default(0);
	    $table->integer('gastro_pain', 0)->unsigned()->default(0);
	    $table->integer('food_intolerance', 0)->unsigned()->default(0);
	    $table->integer('hepatitis', 0)->unsigned()->default(0);
	    $table->integer('jaundice', 0)->unsigned()->default(0);
	    $table->integer('hematochezia', 0)->unsigned()->default(0);
	    $table->integer('changed_bowel', 0)->unsigned()->default(0);
	    $table->integer('diarrhea', 0)->unsigned()->default(0);
	    $table->integer('constipation', 0)->unsigned()->default(0);

	    /*Genitourinary General*/
	    $table->integer('polyuria', 0)->unsigned()->default(0);
	    $table->integer('polydypsia', 0)->unsigned()->default(0);
	    $table->integer('dysuria', 0)->unsigned()->default(0);
	    $table->integer('hematuria', 0)->unsigned()->default(0);
	    $table->integer('frequency', 0)->unsigned()->default(0);
	    $table->integer('urgency', 0)->unsigned()->default(0);
	    $table->integer('incontinence', 0)->unsigned()->default(0);
	    $table->integer('renal_stones', 0)->unsigned()->default(0);
	    $table->integer('utis', 0)->unsigned()->default(0);

	    /*Genitourinary Male*/
	    $table->integer('hesitancy', 0)->unsigned()->default(0);
	    $table->integer('dribbling', 0)->unsigned()->default(0);
	    $table->integer('stream', 0)->unsigned()->default(0);
	    $table->integer('nocturia', 0)->unsigned()->default(0);
	    $table->integer('erections', 0)->unsigned()->default(0);
	    $table->integer('ejaculations', 0)->unsigned()->default(0);

	    /*Genitourinary Female*/
	    $table->integer('g', 0)->unsigned()->default(0);
	    $table->integer('p', 0)->unsigned()->default(0);
	    $table->integer('ap', 0)->unsigned()->default(0);
	    $table->integer('lc', 0)->unsigned()->default(0);
	    $table->integer('mearche', 0)->unsigned()->default(0);
	    $table->integer('menopause', 0)->unsigned()->default(0);
	    $table->integer('lmp', 0)->unsigned()->default(0);
	    $table->integer('f_frequency', 0)->unsigned()->default(0);
	    $table->integer('f_flow', 0)->unsigned()->default(0);
	    $table->integer('f_symptoms', 0)->unsigned()->default(0);
	    $table->integer('abnormal_hair_growth', 0)->unsigned()->default(0);
	    $table->integer('f_hirsutism', 0)->unsigned()->default(0);

	    /*Musculoskeletal*/
	    $table->integer('joint_pain', 0)->unsigned()->default(0);
	    $table->integer('swelling', 0)->unsigned()->default(0);
	    $table->integer('m_redness', 0)->unsigned()->default(0);
	    $table->integer('m_warm', 0)->unsigned()->default(0);
	    $table->integer('m_stiffness', 0)->unsigned()->default(0);
	    $table->integer('muscle', 0)->unsigned()->default(0);
	    $table->integer('m_aches', 0)->unsigned()->default(0);
	    $table->integer('fms', 0)->unsigned()->default(0);
	    $table->integer('arthritis', 0)->unsigned()->default(0);

	    /*Neurologic*/
	    $table->integer('loc', 0)->unsigned()->default(0);
	    $table->integer('seizures', 0)->unsigned()->default(0);
	    $table->integer('stroke', 0)->unsigned()->default(0);
	    $table->integer('tia', 0)->unsigned()->default(0);
	    $table->integer('n_numbness', 0)->unsigned()->default(0);
	    $table->integer('n_weakness', 0)->unsigned()->default(0);
	    $table->integer('paralysis', 0)->unsigned()->default(0);
	    $table->integer('intellectual_decline', 0)->unsigned()->default(0);
	    $table->integer('memory_problems', 0)->unsigned()->default(0);
	    $table->integer('dementia', 0)->unsigned()->default(0);
	    $table->integer('n_headache', 0)->unsigned()->default(0);

	    /*Skin*/
	    $table->integer('s_cancer', 0)->unsigned()->default(0);
	    $table->integer('psoriasis', 0)->unsigned()->default(0);
	    $table->integer('s_acne', 0)->unsigned()->default(0);
	    $table->integer('s_other', 0)->unsigned()->default(0);
	    $table->integer('s_disease', 0)->unsigned()->default(0);

	    /*Psychiatric*/
	    $table->integer('p_diagnosis', 0)->unsigned()->default(0);
	    $table->integer('p_medication', 0)->unsigned()->default(0);
	    $table->integer('depression', 0)->unsigned()->default(0);
	    $table->integer('anxiety', 0)->unsigned()->default(0);
	    $table->integer('social_difficulties', 0)->unsigned()->default(0);

	    /*Endocrine*/
	    $table->integer('thyroid_problems', 0)->unsigned()->default(0);
	    $table->integer('diabetes', 0)->unsigned()->default(0);
	    $table->integer('abnormal_blood', 0)->unsigned()->default(0);

	    /*Hematologic/Allergic/Immunologic*/
	    $table->integer('anemia', 0)->unsigned()->default(0);
	    $table->integer('fh_blood_problems', 0)->unsigned()->default(0);
	    $table->integer('bleeding_problems', 0)->unsigned()->default(0);
	    $table->integer('allergies', 0)->unsigned()->default(0);
	    $table->integer('frequent_illness', 0)->unsigned()->default(0);
	    $table->integer('hiv', 0)->unsigned()->default(0);
	    $table->integer('hai_status', 0)->unsigned()->default(0);

	    /*Establishing relationship*/
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
        Schema::dropIfExists('form_ros');
    }
}
