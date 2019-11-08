<?php

//SANITIZE ALL ESCAPES
$sanitize_all_escapes = $_POST['true'];

//STOP FAKE REGISTER GLOBALS
$fake_register_globals = $_POST['false'];

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/formatting.inc.php");

if (!$encounter) { // comes from globals.php
    die(xlt("Internal error: we do not seem to be in an encounter!"));
}

$id = 0 + (isset($_GET['id']) ? $_GET['id'] : '');

$count = $_POST["count"];
$status = $_POST["status"];
$issue = $_POST["issue"];
$Interventions = json_decode($Interventions) -> intervention;
$Outcome = json_decode($Outcome) -> outcome;
$Goal = json_decode($Goal) -> goal;
$Progress = json_decode($Progress) -> progress;
if ($id && $id != 0) {
    sqlStatement("DELETE FROM `form_patient_care_plan` WHERE id=? AND pid = ? AND encounter = ?", array($id, $_SESSION["pid"], $_SESSION["encounter"]));
    $newid = $id;
} else {
    $res2 = sqlStatement("SELECT MAX(id) as largestId FROM `form_patient_care_plan`");
    $getMaxid = sqlFetchArray($res2);
    if ($getMaxid['largestId']) {
        $newid = $getMaxid['largestId'] + 1;
    } else {
        $newid = 1;
    }
    addForm($encounter, "Patient Care Plan Form", $newid, "patient_care_plan", $_SESSION["pid"], $userauthorized);
}

$count = array_filter($count);
if (!empty($count)) {
    $i = 0;
    foreach ($count as $key => $codeval):
      $issue_num = $i++;
      $issue_val = $issue[$key];
      $Interventions_val = $Interventions[$key] ? $Interventions[$key] : ' ';
      $Outcome_val = $Outcome[$key] ? $Outcome[$key] : ' ';
      $Goal_val = $Goal[$key] ? $Goal[$key] : ' ';
      $Progress_val = $Progress[$key] ? $Progress[$key] : ' ';
      $status_val = $status[$key];

       if( strcmp($issue_val, '') == 0 && strcmp($Interventions_val, '[""]') == 0 && strcmp($Outcome_val, '[""]') == 0 && strcmp($Goal_val, '[""]') == 0 && strcmp($Progress_val, '[""]') == 0) {
        continue;
      }


        $sets = "id    = ". add_escape_custom($newid) .",
            pid        = ". add_escape_custom($_SESSION["pid"]) .",
            groupname  = '" . add_escape_custom($_SESSION["authProvider"]) . "',
            user       = '" . add_escape_custom($_SESSION["authUser"]) . "',
            encounter  = '" . add_escape_custom($_SESSION["encounter"]) . "',
            authorized = ". add_escape_custom($userauthorized) .",
            activity   = 1,
            Key_issue       = '" . add_escape_custom($issue_val) . "',
            issue       = '" . add_escape_custom($issue_num) . "',
            Interventions   = '" . add_escape_custom($Interventions_val) . "',
            Outcome= '" . add_escape_custom($Outcome_val) . "',
            Goal= '" . add_escape_custom($Goal_val) . "',
            status= '" . add_escape_custom($status_val) . "',
            Progress= '" . add_escape_custom($Progress_val) 
             . "'";
        sqlInsert("INSERT INTO form_patient_care_plan SET $sets");
    endforeach;
}
$_SESSION["encounter"] = $encounter;
formHeader("Redirecting....");
formJump();
formFooter();
?>

