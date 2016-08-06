<?php
//by Art Eaton
include_once("../../globals.php");
include_once($GLOBALS["srcdir"]."/api.inc");
function button_ros_report( $pid, $encounter, $cols, $id) {
$count = 0;
$tick =0;
$obj = formFetch("form_button_ros", $id);
?>

<span class="title"><center><b>Review of Systems</b></center></span>
<br></br>

<?php
$tempquery = "SELECT sex FROM patient_data WHERE pid = $pid";
 $res1 = sqlStatement($tempquery);
$result1 = SqlFetchArray($res1); 
$gender=$result1['sex'];


$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"GEN1"} == "on" ) {$patient_reports .= "; Fatigue";} else {$patient_denies .= "; Fatigue";} 
if ($obj{"GEN2"} == "on" ) {$patient_reports .= "; Fever";} else {$patient_denies .= "; Fever";} 
if ($obj{"GEN3"} == "on" ) {$patient_reports .= "; Chills";} else {$patient_denies .= "; Chills";} 
if ($obj{"GEN4"} == "on" ) {$patient_reports .= "; Night Sweats";} else {$patient_denies .= "; Night Sweats";} 
if ($obj{"GEN5"} == "on" ) {$patient_reports .= "; Recent Weight Gain";} else {$patient_denies .= "; Recent Weight Gain";}
if ($obj{"GEN6"} == "on" ) {$patient_reports .= "; Recent Weight Loss";} else {$patient_denies .= "; Recent Weight Loss";}
echo "<br><br>GEN <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"GEN_notes"} ){echo "<br>   Notes:  ".$obj{"GEN_Notes"};} 

$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"EYES1"} == "on" ) {$patient_reports .= "; Vision Changes";} else {$patient_denies .= "; Vision Changes";}
if ($obj{"EYES2"} == "on" ) {$patient_reports .= "; Floaters";} else {$patient_denies .= "; Floaters";} 
if ($obj{"EYES3"} == "on" ) {$patient_reports .= "; Diplopia";} else {$patient_denies .= "; Diplopia";} 
if ($obj{"EYES4"} == "on" ) {$patient_reports .= "; Blurry Vision";} else {$patient_denies .= "; Blurry Vision";} 
if ($obj{"EYES5"} == "on" ) {$patient_reports .= "; Eye Pain";} else {$patient_denies .= "; Eye Pain";} 
if ($obj{"EYES6"} == "on" ) {$patient_reports .= "; Photophobia";} else {$patient_denies .= "; Photophobia";} 
if ($obj{"EYES7"} == "on" ) {$patient_reports .= "; Red Eyes";} else {$patient_denies .= "; Red Eyes";} 
echo "<br><br>EYES <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"EYES_notes"} ){echo "<br>   Notes:  ".$obj{"EYES_notes"};} 

$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"ENT1"} == "on" ) {$patient_reports .= "; Hearing Loss";} else {$patient_denies .= "; Hearing Loss";} 
if ($obj{"ENT2"} == "on" ) {$patient_reports .= "; Ear Ache";} else {$patient_denies .= "; Ear Ache";} 
if ($obj{"ENT3"} == "on" ) {$patient_reports .= "; Otorrhea";} else {$patient_denies .= "; Otorrhea";} 
if ($obj{"ENT4"} == "on" ) {$patient_reports .= "; Tinnitis";} else {$patient_denies .= "; Tinnitis";} 
if ($obj{"ENT5"} == "on" ) {$patient_reports .= "; Epistaxis";} else {$patient_denies .= "; Epistaxis";} 
if ($obj{"ENT6"} == "on" ) {$patient_reports .= "; Sneezing";} else {$patient_denies .= "; Sneezing";} 
if ($obj{"ENT7"} == "on" ) {$patient_reports .= "; Nasal Drip";} else {$patient_denies .= "; Nasal Drip";} 
if ($obj{"ENT8"} == "on" ) {$patient_reports .= "; Sore Throat";} else {$patient_denies .= "; Sore Throat";} 
if ($obj{"ENT9"} == "on" ) {$patient_reports .= "; Mouth Dryness";} else {$patient_denies .= "; Mouth Dryness";} 
if ($obj{"ENT10"} == "on" ) {$patient_reports .= "; Problem Swallowing";} else {$patient_denies .= "; Problem Swallowing";}
if ($obj{"ENT11"} == "on" ) {$patient_reports .= "; Dental Problems";} else {$patient_denies .= "; Dental Problems";} 
echo "<br><br>ENT <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"ENT_notes"}){echo "<br>   Notes:  ".$obj{"ENT_notes"};} 

$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"RESP1"} == "on" ) {$patient_reports .= "; Speech Problem";} else {$patient_denies .= "; Speech Problem";} 
if ($obj{"RESP2"} == "on" ) {$patient_reports .= "; Shortness of Breath";} else {$patient_denies .= "; Shortness of Breath";} 
if ($obj{"RESP3"} == "on" ) {$patient_reports .= "; Orthopnea";} else {$patient_denies .= "; Orthopnea";} 
if ($obj{"RESP4"} == "on" ) {$patient_reports .= "; Cough";} else {$patient_denies .= "; Cough";} 
if ($obj{"RESP5"} == "on" ) {$patient_reports .= "; Cough w/Sputum";} else {$patient_denies .= "; Cough w/Sputum";} 
if ($obj{"RESP6"} == "on" ) {$patient_reports .= "; Hemoptysis";} else {$patient_denies .= "; Hemoptysis";} 
if ($obj{"RESP7"} == "on" ) {$patient_reports .= "; Wheezing";} else {$patient_denies .= "; Wheezing";} 
echo "<br><br>RESP <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"RESP_notes"}){echo "<br>   Notes:  ".$obj{"RESP_notes"};} 

$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"CVS1"} == "on" ) {$patient_reports .= "; Chest Pain";} else {$patient_denies .= "; Chest Pain";} 
if ($obj{"CVS2"} == "on" ) {$patient_reports .= "; Palpitations";} else {$patient_denies .= "; Palpitations";} 
if ($obj{"CVS3"} == "on" ) {$patient_reports .= "; Leg Pain w/Exercise";} else {$patient_denies .= "; Leg Pain w/Exercise";}
echo "<br><br>CVS <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"CVS_notes"}){echo "<br>   Notes:  ".$obj{"CVS_notes"};} 

$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"GI1"} == "on" ) {$patient_reports .= "; Appetite Decrease";} else {$patient_denies .= "; Appetite Decrease";} 
if ($obj{"GI2"} == "on" ) {$patient_reports .= "; Anorexia";} else {$patient_denies .= "; Anorexia";} 
if ($obj{"GI3"} == "on" ) {$patient_reports .= "; Dysphagia";} else {$patient_denies .= "; Dysphagia";} 
if ($obj{"GI4"} == "on" ) {$patient_reports .= "; Heartburn";} else {$patient_denies .= "; Heartburn";} 
if ($obj{"GI5"} == "on" ) {$patient_reports .= "; Nausea";} else {$patient_denies .= "; Nausea";} 
if ($obj{"GI6"} == "on" ) {$patient_reports .= "; Vomiting";} else {$patient_denies .= "; Vomiting";} 
if ($obj{"GI7"} == "on" ) {$patient_reports .= "; Hematemesis";} else {$patient_denies .= "; Hematemesis";} 
if ($obj{"GI8"} == "on" ) {$patient_reports .= "; Abdominal Pain";} else {$patient_denies .= "; Abdominal Pain";} 
if ($obj{"GI9"} == "on" ) {$patient_reports .= "; Jaundice";} else {$patient_denies .= "; Jaundice";} 
if ($obj{"GI10"} == "on" ) {$patient_reports .= "; Diarrhea";} else {$patient_denies .= "; Diarrhea";} 
if ($obj{"GI11"} == "on" ) {$patient_reports .= "; Constipation";} else {$patient_denies .= "; Constipation";} 
echo "<br><br>GI <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"GI_notes"}){echo "<br>   Notes:  ".$obj{"GI_notes"};} 

$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"GU1"} == "on" ) {$patient_reports .= "; Hematura";} else {$patient_denies .= "; Hematura";} 
if ($obj{"GU2"} == "on" ) {$patient_reports .= "; Change in Frequency of Urination";} else {$patient_denies .= "; Change in Frequency of Urination";}
if ($obj{"GU3"} == "on" ) {$patient_reports .= "; Urinary Urgency";} else {$patient_denies .= "; Urinary Urgency";} 
if ($obj{"GU4"} == "on" ) {$patient_reports .= "; Incontinence";} else {$patient_denies .= "; Incontinence";} 
if ($obj{"GU5"} == "on" ) {$patient_reports .= "; Slow Urination";} else {$patient_denies .= "; Slow Urination";} 
if ($gender=="Female"){
if ($obj{"GU6"} == "on" ) {$patient_reports .= "; Irregular Periods";} else {$patient_denies .= "; Irregular Periods";} 
if ($obj{"GU7"} == "on" ) {$patient_reports .= "; Hot Flashes";} else {$patient_denies .= "; Hot Flashes";} 
if ($obj{"GU8"} == "on" ) {$patient_reports .= "; Post Menopausal Bleeding";} else {$patient_denies .= "; Post Menopausal Bleeding";}}
echo "<br><br>GU <br>".$patient_reports."<br>".$patient_denies; 
if ($obj{"GU_notes"}){echo "<br>   Notes:  ".$obj{"GU_notes"};} 

$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"MSK1"} == "on" ) {$patient_reports .= "; Back Pain";} else {$patient_denies .= "; Back Pain";} 
if ($obj{"MSK2"} == "on" ) {$patient_reports .= "; Muscle Aches";} else {$patient_denies .= "; Muscle Aches";} 
if ($obj{"MSK3"} == "on" ) {$patient_reports .= "; Arthralgias";} else {$patient_denies .= "; Arthralgias";} 
if ($obj{"MSK4"} == "on" ) {$patient_reports .= "; Muscle Cramps";} else {$patient_denies .= "; Muscle Cramps";} 
if ($obj{"MSK5"} == "on" ) {$patient_reports .= "; Localized Joint Pain";} else {$patient_denies .= "; Localized Joint Pain";} 
if ($obj{"MSK6"} == "on" ) {$patient_reports .= "; Localized Joint Swelling";} else {$patient_denies .= "; Localized Joint Swelling";} 
if ($obj{"MSK7"} == "on" ) {$patient_reports .= "; Localized Joint Stiffness";} else {$patient_denies .= "; Localized Joint Stiffness";}
echo "<br><br>MSK <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"MSK_notes"}){echo "<br>   Notes:  ".$obj{"MSK_notes"};} 

$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"NEURO1"} == "on" ) {$patient_reports .= "; Dizziness";} else {$patient_denies .= "; Dizziness";} 
if ($obj{"NEURO2"} == "on" ) {$patient_reports .= "; Vertigo";} else {$patient_denies .= "; Vertigo";} 
if ($obj{"NEURO3"} == "on" ) {$patient_reports .= "; Syncope";} else {$patient_denies .= "; Syncope";} 
if ($obj{"NEURO4"} == "on" ) {$patient_reports .= "; Confusion";} else {$patient_denies .= "; Confusion";} 
if ($obj{"NEURO5"} == "on" ) {$patient_reports .= "; Memory Loss";} else {$patient_denies .= "; Memory Loss";} 
if ($obj{"NEURO6"} == "on" ) {$patient_reports .= "; Speech Disturbance";} else {$patient_denies .= "; Speech Disturbance";} 
if ($obj{"NEURO7"} == "on" ) {$patient_reports .= "; Limb Weakness";} else {$patient_denies .= "; Limb Weakness";} 
if ($obj{"NEURO8"} == "on" ) {$patient_reports .= "; Paralysis";} else {$patient_denies .= "; Paralysis";} 
if ($obj{"NEURO9"} == "on" ) {$patient_reports .= "; Involuntary Movements";} else {$patient_denies .= "; Involuntary Movements";} 
if ($obj{"NEURO10"} == "on" ) {$patient_reports .= "; Balance Issues";} else {$patient_denies .= "; Balance Issues";} 
if ($obj{"NEURO11"} == "on" ) {$patient_reports .= "; Tingling";} else {$patient_denies .= "; Tingling";} 
if ($obj{"NEURO12"} == "on" ) {$patient_reports .= "; Numbness";} else {$patient_denies .= "; Numbness";} 
echo "<br><br>NEURO <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"NEURO_notes"} ){echo "<br>   Notes:  ".$obj{"NEURO_notes"};} 

$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"HEME1"} == "on" ) {$patient_reports .= "; Bleeding";} else {$patient_denies .= "; Bleeding";} 
if ($obj{"HEME2"} == "on" ) {$patient_reports .= "; Bruising";} else {$patient_denies .= "; Bruising";} 
if ($obj{"HEME3"} == "on" ) {$patient_reports .= "; Anemia";} else {$patient_denies .= "; Anemia";} 
if ($obj{"HEME4"} == "on" ) {$patient_reports .= "; Sickle Cell Disease";} else {$patient_denies .= "; Sickle Cell Disease";} 
if ($obj{"HEME5"} == "on" ) {$patient_reports .= "; Hx Blood Transfusion";} else {$patient_denies .= "; Hx Blood Transfusion";}
if ($obj{"HEME6"} == "on" ) {$patient_reports .= "; HIV+";} else {$patient_denies .= "; HIV+";} 
echo "<br><br>HEME <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"HEME_notes"} ){echo "<br>   Notes:  ".$obj{"HEME_notes"};} 

$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"ENDO1"} == "on" ) {$patient_reports .= "; Polydipsia";} else {$patient_denies .= "; Polydipsia";} 
if ($obj{"ENDO2"} == "on" ) {$patient_reports .= "; Heat Intolerance";} else {$patient_denies .= "; Heat Intolerance";} 
if ($obj{"ENDO3"} == "on" ) {$patient_reports .= "; Cold Intolerance";} else {$patient_denies .= "; Cold Intolerance";} 
if ($obj{"ENDO4"} == "on" ) {$patient_reports .= "; Excessive Sweating";} else {$patient_denies .= "; Excessive Sweating";} 
if ($obj{"ENDO5"} == "on" ) {$patient_reports .= "; Feelings of Weakness";} else {$patient_denies .= "; Feelings of Weakness";}
echo "<br><br>ENDO <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"ENDO_notes"} ){echo "<br>   Notes:  ".$obj{"ENDO_notes"};} 

$patient_reports = "Patient Reports";
$patient_denies = "Patient Denies";
if ($obj{"DERM1"} == "on" ) {$patient_reports .= "; Dry Skin";} else {$patient_denies .= "; Dry Skin";} 
if ($obj{"DERM2"} == "on" ) {$patient_reports .= "; Itching";} else {$patient_denies .= "; Itching";} 
if ($obj{"DERM3"} == "on" ) {$patient_reports .= "; Peeling Skin";} else {$patient_denies .= "; Peeling Skin";} 
if ($obj{"DERM4"} == "on" ) {$patient_reports .= "; Skin Scalding";} else {$patient_denies .= "; Skin Scalding";} 
if ($obj{"DERM5"} == "on" ) {$patient_reports .= "; Localized Skin Discoloration";} else {$patient_denies .= "; Localized Skin Discoloration";}
if ($obj{"DERM6"} == "on" ) {$patient_reports .= "; Rash";} else {$patient_denies .= "; Rash";}
echo "<br><br>DERM <br>".$patient_reports."<br>".$patient_denies; 
if ($obj{"DERM_notes"}){echo "<br>   Notes:  ".$obj{"DERM_notes"};}


if ($obj{"finalize"} == "on" ) {$patient_reports .= "; This form is finalized";}

}/*END form section*/?>