<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryDatasTable extends Migration
{
    /**
     * Run the migrations.
     * This creates history_datas table.
     * Stores medical history of patients.
     * From UI, Select Patient -> History.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function up()
    {
        Schema::create('history_datas', function (Blueprint $table) {
            $table->increments('id')->comment = "Primary Key. Autoincrement.";
	    $table->integer('pid', 0)->unsigned()->comment = "Foreign key to patient_datas table.";
	    
	    /*Risk Factors*/
	    $table->json('risk_factors')->nullable()->comment = "Stores risk factors data in json format. The field name is key and boolean is value."; /*eg : {"Varicose Veins" : true }, {"Hypertension" : false},  ... */

	    /*Exams*/
	    $table->json('exams')->nullable()->comment = "Stores exams/test data in json format."; /*eg : {"Breast Exam : 'Nor', "Notes" : 'Sample', "Date" : '2017-12-12'"}*/
	
	    /*Family History*/
	    $table->json('history_mother')->nullable()->comment = "Stores history of mother with diagnosis code in json format.";
	    $table->json('history_father')->nullable()->comment = "Stores history of father with diagnosis code in json format.";
	    $table->json('history_siblings')->nullable()->comment = "Stores history of siblings with diagnosis code in json format.";
	    $table->json('history_offspring')->nullable()->comment = "Stores history of childeren with diagnosis code in json format.";
	    $table->json('history_spouse')->nullable()->comment = "Stores history of wife with diagnosis code in json format.";

	    /*Relatives History*/
	    $table->string('relatives_cancer')->nullable()->comment = "Cancer information of relatives.";
	    $table->string('relatives_diabetes')->nullable()->comment = "Diabetes information of relatives";
	    $table->string('relatives_tuberculosis')->nullable()->comment = "Tuberculosis information of relatives";
	    $table->string('relatives_high_blood_pressure')->nullable()->comment = "Blood Pressure information of relatives";
	    $table->string('relatives_heart_problems')->nullable()->comment = "Heart Related information of relatives";
	    $table->string('relatives_stroke')->nullable()->comment = "Stroke information of relatives.";
	    $table->string('relatives_epilepsy')->nullable()->comment = "Epilepsy information of relatives.";
	    $table->string('relatives_mental_illness')->nullable()->comment = "Mental illeness information of relatives.";

	    /*Lifestyle History*/
	    $table->json('coffee')->nullable()->comment = "Coffe data in json format.";
	    $table->json('tobacco')->nullable()->comment = "Tobacco data.";
	    $table->json('alcohol')->nullbale()->comment = "Alcohol data.";
	    $table->string('sleep_patterns')->nullable()->comment = "Sleep Pattern.";
	    $table->json('exercise_patterns')->nullable()->comment = "Exercise Pattern.";
	    $table->string('seatbelt_use')->nullable()->comment = "Seatbelt Use.";
	    $table->json('counseling')->nullable()->comment = "Counselling";
	    $table->json('hazardous_activities')->nullable()->comment = "Hazardous Activities";
	    $table->json('recreational_drugs')->nullable()->comment = "Recreational Drugs.";

	    /*Others*/
	    $table->json('name_value')->nullable()->comment = "Name-Value Pair";
	    $table->text('additional_history')->nullable()->comment = "Additional History.";

	    /*Establishing relationships*/
	    $table->foreign('pid')->references('pid')->on('patient_datas')->onDelete('cascade');  
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
        Schema::dropIfExists('history_datas');
    }
}
