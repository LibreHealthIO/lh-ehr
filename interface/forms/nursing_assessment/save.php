
<?php
//------------Forms generated from formsWiz

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
if (!$encounter) { // comes from globals.php
    die(xlt("Internal error: we do not seem to be in an encounter!"));
}
if ($_GET["mode"] == "new"){
$newid = formSubmit("form_nursing_assessment", $_POST, $_GET["id"], $userauthorized);
addForm($encounter, "Speech Dictation", $newid, "dictation", $pid, $userauthorized);
}elseif ($_GET["mode"] == "update") {
sqlInsert("update form_dictation set pid = ?,groupname=?,user=?,authorized=?,activity=1, date = NOW(), dictation=?, additional_notes=? where id=?",array($_SESSION["pid"],$_SESSION["authProvider"],$_SESSION["authUser"],$userauthorized,$_POST["dictation"],$_POST["additional_notes"],$_GET["id"]));
}
$_SESSION["encounter"] = $encounter;
formHeader("Redirecting....");
formJump();
formFooter();
?>


BG_level: 
BG_interventions: [select]
P_interactions: Content
BG_level: 
BG_level: 
ass_comments: 
repiratory_pattern: [select]
V_temperature: 
pulse: 
SpO2: 
SpO2: 
V_RespiratoryRate: 
V_source: [select]
V_deliveryMethod: [select]
V_flowRate: 
V_bp: 
V_bpMean: 
V_bpLocation: [select]
V_bpPosition: [select]
ADL_location: [select]
ADL_activity: [select]
ADL_position: [select]
ADL_care: [select]
ADL_bath: [select]
ADL_bed: [select]
ADL_assistance: [select]
ADL_mobilisation: [select]
ADL_scd: [select]
Pain_scale: [select]
Pain_gaol: [select]
Pain_rating: 0
Pain_location: 					    		
Pain_description: [select]
Pain_frequency: [select]
Pain_interventions: [select]
Pain_medSideEffects: [select]
PN_reason: 
PN_role: 
PN_method: [select]
NM_location: [select]
NM_limbMovnt: [select]
NM_strength: [select]
NM_sensation: [select]
GCS_eyeOpening: 
GCS_bestVerbal: 
GCS_bestMotor: 
GCS_total: 
GCS_remarks: Sedated
NE_Orientaion: [select]
NE_cry: [select]
NE_pr_bilat: [select]
NE_pr_left: [select]
NE_pr_right: [select]
NE_oculomotor: [select]
NE_fontanel: [select]
NE_reflexes: 
NE_monitoring: [select]
NE_collar: [select]
psy_Orientaion: [select]
psy_mood: [select]
psy_memory: [select]
psy_appearance: [select]
psy_behaviour: [select]
psy_eyeContact: [select]
psy_thought: [select]
pys_judgment: [select]
pys_interactions: [select]
psy_riskPrecaution: [select]
NE_collar: [select]
cardio_heartSounds: [select]
cardio_PMI: [select]
cardio_rhythm: [select]
cardio_arrythmia: [select]
cardio_arryfreq: [select]
cardio_interventions: [select]
cardio_crc: [select]
cardio_crp: [select]
cardio_crRUE: [select]
cardio_crLUE: [select]
cardio_crRLE: [select]
cardio_crLLE: [select]
cardio_centralColor: [select]
cardio_PeripheralColor: [select]
cardio_colorRUE: [select]
cardio_colorLUE: [select]
cardio_colorRLE: [select]
cardio_colorLLE: [select]
cardio_centralPulses: [select]
cardio_pheripheralPulses: [select]
cardio_pulseRUE: [select]
cardio_pulseLUE: [select]
cardio_pulseRUE: [select]
cardio_pulseLLE: [select]
resp_pattern: [select]
resp_retractions: [select]
cardio_rhythm: [select]
resp_appearance: [select]
resp_bsallLobes: [select]
resp_bsRUL: [select]
resp_bsRML: [select]
resp_bsRLL: [select]
resp_bsLUL: [select]
resp_bsLLL: [select]
resp_interventions: [select]
resp_suctiontype: [select]
resp_oralSec: [select]
resp_nasalSec: [select]
resp_airwaySec: [select]
GI_abdAsssess: [select]
GI_girth: 
GI_liverPos: Unable to assess
GI_symptoms: Arching
GI_interventions: [select]
GI_bsAllQuad: [select]
GI_bsRUG: [select]
GI_bsRLG: [select]
GI_bsLUG: [select]
GI_bsLLG: [select]
GI_epigastric: [select]
GU_symptoms: [select]
GU_interventions: [select]
GU_bladderScan: 
GU_interventions: [select]
GU_genitalia:  
msk_dlocation: 
msk_gcircum: 
msk_glocation: 
msk_muscle: [select]
msk_motion: [select]
msk_appearance: [select]
msk_support: [select]
msk_weight: [select]
msk_degree:  
msk_state: [select]
msk_traction: 
msk_cap: 
msk_assessment: [select]
msk_sensation: [select]
msk_sensation: [select]
msk_intervention: [select]
skin_assessment: [select]
skin_location: 					    		
skin_description: Cold
skin_rlocation: 					    		
skin_rdescription: [select]
skin_appearance: [select]
skin_lassessement:  
skin_NBlocation:  
skin_wlocation:  
skin_NBdescription: [select]
skin_TfirstNoticed:  
skin_DfirstNoticed:  
skin_DfirstNoticed:  
skin_intervention: [select]
skin_dressing: [select]
skin_drainage: [select]
skin_treatment: [select]
skin_comment: 					    		
thermoregulation: [select]