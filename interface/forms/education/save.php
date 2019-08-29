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
$topic = $_POST["topic"];
$remark = $_POST["remark"];
$Interventions = json_decode($_POST["interventions"]) -> intervention;
$learner = json_decode($_POST["learners"]) -> learner;
$readiness = json_decode($_POST["readiness"]) -> readiness;
$response = json_decode($_POST["response"]) -> response;
$method = json_decode($_POST["method"]) -> method;


if ($id && $id != 0) {
    sqlStatement("DELETE FROM `form_education` WHERE id=? AND pid = ? AND encounter = ?", array($id, $_SESSION["pid"], $_SESSION["encounter"]));
    $newid = $id;
} else {
    $res2 = sqlStatement("SELECT MAX(id) as largestId FROM `form_education`");
    $getMaxid = sqlFetchArray($res2);
    if ($getMaxid['largestId']) {
        $newid = $getMaxid['largestId'] + 1;
    } else {
        $newid = 1;
    }
   addForm($encounter, "Education Form", $newid, "education", $_SESSION["pid"], $userauthorized);
}

$count = array_filter($count);
if (!empty($count)) {
    $i = 0;
    foreach ($count as $key => $codeval):
      $topic_val = $topic[$key];
      $Interventions_val = $Interventions[$key] ? $Interventions[$key] : '';
      $learner_val = $learner[$key] ? $learner[$key] : '';
      $readiness_val = $readiness[$key] ? $readiness[$key] : '';
      $response_val = $response[$key] ? $response[$key] : '';
      $method_val = $method[$key] ? $method[$key] : '';
      $remark_val = $remark[$key];

      if( strcmp($topic_val, '') == 0 && strcmp($Interventions_val, '[""]') == 0 && strcmp($learner_val, '[""]') == 0 && strcmp($readiness_val, '[""]') == 0 && strcmp($response_val, '[""]') == 0 && strcmp($method_val, '[""]') == 0 && strcmp($remark_val, '') == 0) {
        continue;
      }

        $sets = "id    = ". add_escape_custom($newid) .",
            pid        = ". add_escape_custom($_SESSION["pid"]) .",
            groupname  = '" . add_escape_custom($_SESSION["authProvider"]) . "',
            user       = '" . add_escape_custom($_SESSION["authUser"]) . "',
            encounter  = '" . add_escape_custom($_SESSION["encounter"]) . "',
            authorized = ". add_escape_custom($userauthorized) .",
            activity   = 1,
            topic       = '" . add_escape_custom($topic_val) . "',
            interventions   = '" . add_escape_custom($Interventions_val) . "',
            response= '" . add_escape_custom($response_val) . "',
            readiness= '" . add_escape_custom($readiness_val) . "',
            method= '" . add_escape_custom($method_val) . "',
            learners= '" . add_escape_custom($learner_val) . "',
            remark= '" . add_escape_custom($remark_val) 
             . "'";
        sqlInsert("INSERT INTO form_education SET $sets");
    endforeach;
}
$_SESSION["encounter"] = $encounter;
formHeader("Redirecting....");
formJump();
formFooter();
?>

