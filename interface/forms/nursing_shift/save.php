
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
$humpty_dumpty = array(
    "Age"=> array(
        "4" => "4 less than 3 y/o",
        "3" => "3 3 to less than 7 y/o",
        "2" => "2 7 to less than 13 y/o",
        "1" => "1 13 years and older"
    ),
    "Gender"=> array(
        "2" => "2 male",
        "1" => "1 female"
    ),
    "Diagnosis"=> array(
        "4" => "4 neurological diagnosis",
        "3" => "3 alterations in oxygenation - respiratory issue",
        "2" => "2 psych/behavioral disorders",
        "1" => "1 other diagnosis"
    ), 
    "Cognitive_impairments"=> array(
        "3" => "3 not aware of own limitations",
        "2" => "2 forgets limitations",
        "1" => "1 oriented to own ability"
    ),
    "Environmental_factors"=> array(
        "4" => "4 hx of falls",
        "3" => "3 patient uses assistive devices",
        "2" => "2 patient placed in bed",
        "1" => "1 patient in outpatient area"
    ),
    "Response_to_surgery__sedation__anesthesia"=> array(
        "3" => "3 within 24 hours",
        "2" => "2 within 48 hours",
        "1" => "1 more than 48 hrs/none"
    ),
    "Medication_usage"=> array(
        "3" => "3 multiple usage of sedative medications",
        "2" => "2 use of one sedative medication",
        "1" => "1 other medications/none"
    ),
);
$braden_q_scale = array(
    "Mobility" => array(
        "1" => "1 completely immobile",
        "2" => "2 very limited",
        "3" => "3 slightly limited",
        "4" => "4 no limitations"
    ),
    "pt_Activity" => array(
        "1" => "1  confined to bed",
        "2" => "2  chairfast",
        "3" => "3  walks occassionally",
        "4" => "4  walks frequently"
    ),
    "Sensory_Perception" => array(
        "1" => "1  completely limited",
        "2" => "2  very limited",
        "3" => "3  slightly limited",
        "4" => "4  no impairment"
    ),
    "Moisture" => array(
        "1" => "1  constantly mois",
        "2" => "2  very moist",
        "3" => "3  occassionally moist",
        "4" => "4  rarely moist"
    ),
    "Friction__Shear" => array(
        "1" => "1  significant problem",
        "2" => "2  problem",
        "3" => "3  potential problem",
        "4" => "4  no apparent problem"
    ),
    "Nutrition" => array(
        "1" => "1  very poor",
        "2" => "2  inadequate",
        "3" => "3  adequate",
        "4" => "4  excellent"
    ),
    "Tissue__Perfusion__Oxygenation" => array(
        "1" => "1  extremely compromised",
        "2" => "2  compromised",
        "3" => "3  adequate",
        "4" => "4  excellent"
    )
);
foreach($_POST as $key => $value) {
    if($humpty_dumpty[$key] != NULL && $humpty_dumpty[$key][$value] != NULL) {
        $_POST[$key] = $humpty_dumpty[$key][$value];
    } else if($braden_q_scale[$key] != NULL && $braden_q_scale[$key][$value] != NULL) {
        $_POST[$key] = $braden_q_scale[$key][$value];
    }
}

if ($_GET["mode"] == "new"){
$newid = formSubmit("form_nursing_shift", $_POST, $_GET["id"], $userauthorized);
addForm($encounter, "Nursing Shift", $newid, "nursing_shift", $pid, $userauthorized);
}elseif ($_GET["mode"] == "update") {
	formUpdate("form_nursing_shift", $_POST, $_GET["id"], $userauthorized);
}
$_SESSION["encounter"] = $encounter;
formHeader("Redirecting....");
formJump();
formFooter();
?>
