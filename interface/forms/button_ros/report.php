<?php
//by Art Eaton
include_once("../../globals.php");
include_once($GLOBALS["srcdir"]."/api.inc");
function button_ros_report( $pid, $encounter, $cols, $id) {
$count = 0;
$tick =0;
$obj = formFetch("form_button_ros", $id);
?>

<span class="title"><center><b><?php echo xlt('Review of Systems');?></b></center></span>
<br></br>

<?php
$tempquery = "SELECT sex FROM patient_data WHERE pid = $pid";
 $res1 = sqlStatement($tempquery);
$result1 = SqlFetchArray($res1); 
$gender=$result1['sex'];


$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"GEN1"} == "on" ) {$patient_reports .= xlt('; Fatigue');} else {$patient_denies .= xlt('; Fatigue'); 
if ($obj{"GEN2"} == "on" ) {$patient_reports .= xlt('; Fever');} else {$patient_denies .= xlt('; Fever'); 
if ($obj{"GEN3"} == "on" ) {$patient_reports .= xlt('; Chills');} else {$patient_denies .= xlt('; Chills'); 
if ($obj{"GEN4"} == "on" ) {$patient_reports .= xlt('; Night Sweats');} else {$patient_denies .= xlt('; Night Sweats'); 
if ($obj{"GEN5"} == "on" ) {$patient_reports .= xlt('; Recent Weight Gain');} else {$patient_denies .= xlt('; Recent Weight Gain');
if ($obj{"GEN6"} == "on" ) {$patient_reports .= xlt('; Recent Weight Loss');} else {$patient_denies .= xlt('; Recent Weight Loss');
echo "<br><br>GEN <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"GEN_notes"} ){echo "<br>   Notes:  ".$obj{"GEN_Notes"};} 

$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"EYES1"} == "on" ) {$patient_reports .= xlt('; Vision Changes');} else {$patient_denies .= xlt('; Vision Changes');
if ($obj{"EYES2"} == "on" ) {$patient_reports .= xlt('; Floaters');} else {$patient_denies .= xlt('; Floaters'); 
if ($obj{"EYES3"} == "on" ) {$patient_reports .= xlt('; Diplopia');} else {$patient_denies .= xlt('; Diplopia'); 
if ($obj{"EYES4"} == "on" ) {$patient_reports .= xlt('; Blurry Vision');} else {$patient_denies .= xlt('; Blurry Vision'); 
if ($obj{"EYES5"} == "on" ) {$patient_reports .= xlt('; Eye Pain');} else {$patient_denies .= xlt('; Eye Pain'); 
if ($obj{"EYES6"} == "on" ) {$patient_reports .= xlt('; Photophobia');} else {$patient_denies .= xlt('; Photophobia'); 
if ($obj{"EYES7"} == "on" ) {$patient_reports .= xlt('; Red Eyes');} else {$patient_denies .= xlt('; Red Eyes'); 
echo "<br><br>EYES <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"EYES_notes"} ){echo "<br>   Notes:  ".$obj{"EYES_notes"};} 

$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"ENT1"} == "on" ) {$patient_reports .= xlt('; Hearing Loss');} else {$patient_denies .= xlt('; Hearing Loss'); 
if ($obj{"ENT2"} == "on" ) {$patient_reports .= xlt('; Ear Ache');} else {$patient_denies .= xlt('; Ear Ache'); 
if ($obj{"ENT3"} == "on" ) {$patient_reports .= xlt('; Otorrhea');} else {$patient_denies .= xlt('; Otorrhea'); 
if ($obj{"ENT4"} == "on" ) {$patient_reports .= xlt('; Tinnitis');} else {$patient_denies .= xlt('; Tinnitis'); 
if ($obj{"ENT5"} == "on" ) {$patient_reports .= xlt('; Epistaxis');} else {$patient_denies .= xlt('; Epistaxis'); 
if ($obj{"ENT6"} == "on" ) {$patient_reports .= xlt('; Sneezing');} else {$patient_denies .= xlt('; Sneezing'); 
if ($obj{"ENT7"} == "on" ) {$patient_reports .= xlt('; Nasal Drip');} else {$patient_denies .= xlt('; Nasal Drip'); 
if ($obj{"ENT8"} == "on" ) {$patient_reports .= xlt('; Sore Throat');} else {$patient_denies .= xlt('; Sore Throat'); 
if ($obj{"ENT9"} == "on" ) {$patient_reports .= xlt('; Mouth Dryness');} else {$patient_denies .= xlt('; Mouth Dryness'); 
if ($obj{"ENT10"} == "on" ) {$patient_reports .= xlt('; Problem Swallowing');} else {$patient_denies .= xlt('; Problem Swallowing');
if ($obj{"ENT11"} == "on" ) {$patient_reports .= xlt('; Dental Problems');} else {$patient_denies .= xlt('; Dental Problems'); 
echo "<br><br>ENT <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"ENT_notes"}){echo "<br>   Notes:  ".$obj{"ENT_notes"};} 

$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"RESP1"} == "on" ) {$patient_reports .= xlt('; Speech Problem');} else {$patient_denies .= xlt('; Speech Problem'); 
if ($obj{"RESP2"} == "on" ) {$patient_reports .= xlt('; Shortness of Breath');} else {$patient_denies .= xlt('; Shortness of Breath'); 
if ($obj{"RESP3"} == "on" ) {$patient_reports .= xlt('; Orthopnea');} else {$patient_denies .= xlt('; Orthopnea'); 
if ($obj{"RESP4"} == "on" ) {$patient_reports .= xlt('; Cough');} else {$patient_denies .= xlt('; Cough'); 
if ($obj{"RESP5"} == "on" ) {$patient_reports .= xlt('; Cough w/Sputum');} else {$patient_denies .= xlt('; Cough w/Sputum'); 
if ($obj{"RESP6"} == "on" ) {$patient_reports .= xlt('; Hemoptysis');} else {$patient_denies .= xlt('; Hemoptysis'); 
if ($obj{"RESP7"} == "on" ) {$patient_reports .= xlt('; Wheezing');} else {$patient_denies .= xlt('; Wheezing'); 
echo "<br><br>RESP <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"RESP_notes"}){echo "<br>   Notes:  ".$obj{"RESP_notes"};} 

$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"CVS1"} == "on" ) {$patient_reports .= xlt('; Chest Pain');} else {$patient_denies .= xlt('; Chest Pain'); 
if ($obj{"CVS2"} == "on" ) {$patient_reports .= xlt('; Palpitations');} else {$patient_denies .= xlt('; Palpitations'); 
if ($obj{"CVS3"} == "on" ) {$patient_reports .= xlt('; Leg Pain w/Exercise');} else {$patient_denies .= xlt('; Leg Pain w/Exercise');
echo "<br><br>CVS <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"CVS_notes"}){echo "<br>   Notes:  ".$obj{"CVS_notes"};} 

$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"GI1"} == "on" ) {$patient_reports .= xlt('; Appetite Decrease');} else {$patient_denies .= xlt('; Appetite Decrease'); 
if ($obj{"GI2"} == "on" ) {$patient_reports .= xlt('; Anorexia');} else {$patient_denies .= xlt('; Anorexia'); 
if ($obj{"GI3"} == "on" ) {$patient_reports .= xlt('; Dysphagia');} else {$patient_denies .= xlt('; Dysphagia'); 
if ($obj{"GI4"} == "on" ) {$patient_reports .= xlt('; Heartburn');} else {$patient_denies .= xlt('; Heartburn'); 
if ($obj{"GI5"} == "on" ) {$patient_reports .= xlt('; Nausea');} else {$patient_denies .= xlt('; Nausea'); 
if ($obj{"GI6"} == "on" ) {$patient_reports .= xlt('; Vomiting');} else {$patient_denies .= xlt('; Vomiting'); 
if ($obj{"GI7"} == "on" ) {$patient_reports .= xlt('; Hematemesis');} else {$patient_denies .= xlt('; Hematemesis'); 
if ($obj{"GI8"} == "on" ) {$patient_reports .= xlt('; Abdominal Pain');} else {$patient_denies .= xlt('; Abdominal Pain'); 
if ($obj{"GI9"} == "on" ) {$patient_reports .= xlt('; Jaundice');} else {$patient_denies .= xlt('; Jaundice'); 
if ($obj{"GI10"} == "on" ) {$patient_reports .= xlt('; Diarrhea');} else {$patient_denies .= xlt('; Diarrhea'); 
if ($obj{"GI11"} == "on" ) {$patient_reports .= xlt('; Constipation');} else {$patient_denies .= xlt('; Constipation'); 
echo "<br><br>GI <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"GI_notes"}){echo "<br>   Notes:  ".$obj{"GI_notes"};} 

$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"GU1"} == "on" ) {$patient_reports .= xlt('; Hematura');} else {$patient_denies .= xlt('; Hematura'); 
if ($obj{"GU2"} == "on" ) {$patient_reports .= xlt('; Change in Frequency of Urination');} else {$patient_denies .= xlt('; Change in Frequency of Urination');
if ($obj{"GU3"} == "on" ) {$patient_reports .= xlt('; Urinary Urgency');} else {$patient_denies .= xlt('; Urinary Urgency'); 
if ($obj{"GU4"} == "on" ) {$patient_reports .= xlt('; Incontinence');} else {$patient_denies .= xlt('; Incontinence'); 
if ($obj{"GU5"} == "on" ) {$patient_reports .= xlt('; Slow Urination');} else {$patient_denies .= xlt('; Slow Urination'); 
if ($gender=="Female"){
if ($obj{"GU6"} == "on" ) {$patient_reports .= xlt('; Irregular Periods');} else {$patient_denies .= xlt('; Irregular Periods'); 
if ($obj{"GU7"} == "on" ) {$patient_reports .= xlt('; Hot Flashes');} else {$patient_denies .= xlt('; Hot Flashes'); 
if ($obj{"GU8"} == "on" ) {$patient_reports .= xlt('; Post Menopausal Bleeding');} else {$patient_denies .= xlt('; Post Menopausal Bleeding');}
echo "<br><br>GU <br>".$patient_reports."<br>".$patient_denies; 
if ($obj{"GU_notes"}){echo "<br>   Notes:  ".$obj{"GU_notes"};} 

$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"MSK1"} == "on" ) {$patient_reports .= xlt('; Back Pain');} else {$patient_denies .= xlt('; Back Pain'); 
if ($obj{"MSK2"} == "on" ) {$patient_reports .= xlt('; Muscle Aches');} else {$patient_denies .= xlt('; Muscle Aches'); 
if ($obj{"MSK3"} == "on" ) {$patient_reports .= xlt('; Arthralgias');} else {$patient_denies .= xlt('; Arthralgias'); 
if ($obj{"MSK4"} == "on" ) {$patient_reports .= xlt('; Muscle Cramps');} else {$patient_denies .= xlt('; Muscle Cramps'); 
if ($obj{"MSK5"} == "on" ) {$patient_reports .= xlt('; Localized Joint Pain');} else {$patient_denies .= xlt('; Localized Joint Pain'); 
if ($obj{"MSK6"} == "on" ) {$patient_reports .= xlt('; Localized Joint Swelling');} else {$patient_denies .= xlt('; Localized Joint Swelling'); 
if ($obj{"MSK7"} == "on" ) {$patient_reports .= xlt('; Localized Joint Stiffness');} else {$patient_denies .= xlt('; Localized Joint Stiffness');
echo "<br><br>MSK <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"MSK_notes"}){echo "<br>   Notes:  ".$obj{"MSK_notes"};} 

$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"NEURO1"} == "on" ) {$patient_reports .= xlt('; Dizziness');} else {$patient_denies .= xlt('; Dizziness'); 
if ($obj{"NEURO2"} == "on" ) {$patient_reports .= xlt('; Vertigo');} else {$patient_denies .= xlt('; Vertigo'); 
if ($obj{"NEURO3"} == "on" ) {$patient_reports .= xlt('; Syncope');} else {$patient_denies .= xlt('; Syncope'); 
if ($obj{"NEURO4"} == "on" ) {$patient_reports .= xlt('; Confusion');} else {$patient_denies .= xlt('; Confusion'); 
if ($obj{"NEURO5"} == "on" ) {$patient_reports .= xlt('; Memory Loss');} else {$patient_denies .= xlt('; Memory Loss'); 
if ($obj{"NEURO6"} == "on" ) {$patient_reports .= xlt('; Speech Disturbance');} else {$patient_denies .= xlt('; Speech Disturbance'); 
if ($obj{"NEURO7"} == "on" ) {$patient_reports .= xlt('; Limb Weakness');} else {$patient_denies .= xlt('; Limb Weakness'); 
if ($obj{"NEURO8"} == "on" ) {$patient_reports .= xlt('; Paralysis');} else {$patient_denies .= xlt('; Paralysis'); 
if ($obj{"NEURO9"} == "on" ) {$patient_reports .= xlt('; Involuntary Movements');} else {$patient_denies .= xlt('; Involuntary Movements'); 
if ($obj{"NEURO10"} == "on" ) {$patient_reports .= xlt('; Balance Issues');} else {$patient_denies .= xlt('; Balance Issues'); 
if ($obj{"NEURO11"} == "on" ) {$patient_reports .= xlt('; Tingling');} else {$patient_denies .= xlt('; Tingling'); 
if ($obj{"NEURO12"} == "on" ) {$patient_reports .= xlt('; Numbness');} else {$patient_denies .= xlt('; Numbness'); 
echo "<br><br>NEURO <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"NEURO_notes"} ){echo "<br>   Notes:  ".$obj{"NEURO_notes"};} 

$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"HEME1"} == "on" ) {$patient_reports .= xlt('; Bleeding');} else {$patient_denies .= xlt('; Bleeding'); 
if ($obj{"HEME2"} == "on" ) {$patient_reports .= xlt('; Bruising');} else {$patient_denies .= xlt('; Bruising'); 
if ($obj{"HEME3"} == "on" ) {$patient_reports .= xlt('; Anemia');} else {$patient_denies .= xlt('; Anemia'); 
if ($obj{"HEME4"} == "on" ) {$patient_reports .= xlt('; Sickle Cell Disease');} else {$patient_denies .= xlt('; Sickle Cell Disease'); 
if ($obj{"HEME5"} == "on" ) {$patient_reports .= xlt('; Hx Blood Transfusion');} else {$patient_denies .= xlt('; Hx Blood Transfusion');
if ($obj{"HEME6"} == "on" ) {$patient_reports .= xlt('; HIV+');} else {$patient_denies .= xlt('; HIV+'); 
echo "<br><br>HEME <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"HEME_notes"} ){echo "<br>   Notes:  ".$obj{"HEME_notes"};} 

$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"ENDO1"} == "on" ) {$patient_reports .= xlt('; Polydipsia');} else {$patient_denies .= xlt('; Polydipsia'); 
if ($obj{"ENDO2"} == "on" ) {$patient_reports .= xlt('; Heat Intolerance');} else {$patient_denies .= xlt('; Heat Intolerance'); 
if ($obj{"ENDO3"} == "on" ) {$patient_reports .= xlt('; Cold Intolerance');} else {$patient_denies .= xlt('; Cold Intolerance'); 
if ($obj{"ENDO4"} == "on" ) {$patient_reports .= xlt('; Excessive Sweating');} else {$patient_denies .= xlt('; Excessive Sweating'); 
if ($obj{"ENDO5"} == "on" ) {$patient_reports .= xlt('; Feelings of Weakness');} else {$patient_denies .= xlt('; Feelings of Weakness');
echo "<br><br>ENDO <br>".$patient_reports."<br>".$patient_denies;
if ($obj{"ENDO_notes"} ){echo "<br>   Notes:  ".$obj{"ENDO_notes"};} 

$patient_reports = xlt('Patient Reports');
$patient_denies = xlt('Patient Denies');
if ($obj{"DERM1"} == "on" ) {$patient_reports .= xlt('; Dry Skin');} else {$patient_denies .= xlt('; Dry Skin'); 
if ($obj{"DERM2"} == "on" ) {$patient_reports .= xlt('; Itching');} else {$patient_denies .= xlt('; Itching'); 
if ($obj{"DERM3"} == "on" ) {$patient_reports .= xlt('; Peeling Skin');} else {$patient_denies .= xlt('; Peeling Skin'); 
if ($obj{"DERM4"} == "on" ) {$patient_reports .= xlt('; Skin Scalding');} else {$patient_denies .= xlt('; Skin Scalding'); 
if ($obj{"DERM5"} == "on" ) {$patient_reports .= xlt('; Localized Skin Discoloration');} else {$patient_denies .= xlt('; Localized Skin Discoloration');
if ($obj{"DERM6"} == "on" ) {$patient_reports .= xlt('; Rash');} else {$patient_denies .= xlt('; Rash');
echo "<br><br>DERM <br>".$patient_reports."<br>".$patient_denies; 
if ($obj{"DERM_notes"}){echo "<br>   Notes:  ".$obj{"DERM_notes"};}


if ($obj{"finalize"} == "on" ) {echo xlt('This form is finalized');}else{echo xlt('This form NOT finalized');}

}/*END form section*/?>