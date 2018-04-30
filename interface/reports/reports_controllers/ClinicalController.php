<?php
/*
 * These functions are common functions used in Clinical report.
 * They have been pulled out and placed in this file. This is done to prepare
 * the for building a report generator.
 *
 * Copyright (C) 2018 Tigpezeghe Rodrige <tigrodrige@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Tigpezeghe Rodrige <tigrodrige@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/options.inc.php");
require_once("../drugs/drugs.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("../../custom/code_types.inc.php");
require_once("../../library/report_functions.php");

$comarr = array('allow_sms'=>xl('Allow SMS'),'allow_voice'=>xl('Allow Voice Message'),'allow_mail'=>xl('Allow Mail Message'),'allow_email'=>xl('Allow Email'));
$DateFormat = DateFormatRead(true);
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

/*Attribution: 2010-2017 LibreHealth EHR Support LLC */
function add_date($givendate, $day = 0, $mth = 0, $yr = 0)
{
    $DateFormat = DateFormatRead(true);
    $cd = strtotime($givendate);
    $newdate = date($DateFormat, mktime(date('H', $cd),
        date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
        date('d',$cd)+$day, date('Y',$cd)+$yr));
    return $newdate;
}

$type = $_POST["type"];
$facility = isset($_POST['facility']) ? $_POST['facility'] : '';
if($_POST['form_from_date'] != "")
    $from_date = $_POST['form_from_date'];
else
    $from_date = fixDate($_POST['form_from_date'], date($DateFormat));

if($_POST['form_to_date'] != "")
    $to_date = $_POST['form_to_date'];
else
    $to_date = fixDate($_POST['form_to_date']  , add_date(date('Y-m-d H:i:s')));

$patient_id = trim($_POST["patient_id"]);
$age_from = $_POST["age_from"];
$age_to = $_POST["age_to"];
$sql_gender = $_POST["gender"];
$sql_ethnicity = $_POST["ethnicity"];
$sql_race=$_POST["race"];
$form_drug_name = trim($_POST["form_drug_name"]);
$form_diagnosis = trim($_POST["form_diagnosis"]);
$form_lab_results = trim($_POST["form_lab_results"]);
$form_service_codes = trim($_POST["form_service_codes"]);
$form_immunization = trim($_POST["form_immunization"]);
$communication = trim($_POST["communication"]);

/* This function prepares the result for Clinical report
 * @params: None
 * @return: Array ($result, $row_id, $img_id, $k)
 * @author: Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * */
function prepareResults() {
  $sqlstmt = "select
                concat(pd.fname, ' ', pd.lname) AS patient_name,
                pd.pid AS patient_id,
                DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),pd.dob)), '%Y')+0 AS patient_age,
                pd.sex AS patient_sex,
                pd.race AS patient_race,pd.ethnicity AS patient_ethinic,
                concat(u.fname, ' ', u.lname)  AS users_provider,
                REPLACE(REPLACE(concat_ws(',',IF(pd.hipaa_allowemail = 'YES', 'Allow Email','NO'),IF(pd.hipaa_allowsms = 'YES', 'Allow SMS','NO') , IF(pd.hipaa_mail = 'YES', 'Allow Mail Message','NO') , IF(pd.hipaa_voice = 'YES', 'Allow Voice Message','NO') ), ',NO',''), 'NO,','') as communications";

  if(strlen($form_diagnosis) > 0 || $_POST['form_diagnosis_allergy'] == true
      || $_POST['form_diagnosis_medprb'] == true)  {
      $sqlstmt=$sqlstmt.",li.date AS lists_date,
                  li.diagnosis AS lists_diagnosis,
                  li.title AS lists_title";
  }

  if(strlen($form_drug_name) > 0 || $_POST['form_drug'] == true)  {
      $sqlstmt=$sqlstmt.",r.id as id, r.date_modified AS prescriptions_date_modified, r.dosage as dosage, r.route as route, r.interval as hinterval, r.refills as refills, r.drug as drug,
          r.form as hform, r.size as size, r.unit as hunit, d.name as name, d.ndc_number as ndc_number,r.quantity as quantity";
  }

  if(strlen($form_lab_results) > 0 || $_POST['lab_results'] == true) {
      $sqlstmt = $sqlstmt.",pr.date AS procedure_result_date,
                      pr.facility AS procedure_result_facility,
                      pr.units AS procedure_result_units,
                      pr.result AS procedure_result_result,
                      pr.range AS procedure_result_range,
                      pr.abnormal AS procedure_result_abnormal,
                      pr.comments AS procedure_result_comments,
                      pr.document_id AS procedure_result_document_id";
  }

  if ( $type == 'Procedure') {
      $sqlstmt = $sqlstmt.",po.date_ordered AS procedure_order_date_ordered,
                  pt.standard_code AS procedure_type_standard_code,
                  pc.procedure_name as procedure_name,
                  po.order_priority AS procedure_order_order_priority,
                  po.order_status AS procedure_order_order_status,
                  po.encounter_id AS procedure_order_encounter,
                  po.patient_instructions AS procedure_order_patient_instructions,
                  po.activity AS procedure_order_activity,
                  po.control_id AS procedure_order_control_id ";
  }

    if ( $type == 'Medical History') {
        $sqlstmt = $sqlstmt.",hd.date AS history_data_date,
                  hd.tobacco AS history_data_tobacco,
                  hd.alcohol AS history_data_alcohol,
                  hd.recreational_drugs AS history_data_recreational_drugs   ";
    }

    if($type == 'Service Codes') {
        $sqlstmt .= ", c.code as code,
                c.code_text as code_text,
                fe.encounter as encounter,
                b.date as date";
        $mh_stmt = $mh_stmt.",code,code_text,encounter,date";
    }

    if (strlen($form_immunization) > 0) {
        $sqlstmt .= ", immc.code_text as imm_code, immc.code_text_short as imm_code_short, immc.id as cvx_code, imm.administered_date as imm_date, imm.amount_administered, imm.amount_administered_unit,  imm.administration_site, imm.note as notes ";
    }

  //from
    $sqlstmt=$sqlstmt." from patient_data as pd left outer join users as u on u.id = pd.providerid
            left outer join facility as f on f.id = u.facility_id";

    if(strlen($form_diagnosis) > 0 || ($_POST['form_diagnosis_allergy'] == true
      && $_POST['form_diagnosis_medprb'] == true)){
        $sqlstmt = $sqlstmt." left outer join lists as li on (li.pid  = pd.pid AND (li.type='medical_problem' OR li.type='allergy')) ";
    }elseif($_POST['form_diagnosis_allergy'] == true){
        $sqlstmt = $sqlstmt." left outer join lists as li on (li.pid  = pd.pid AND (li.type='allergy')) ";
    }elseif($_POST['form_diagnosis_medprb'] == true){
        $sqlstmt = $sqlstmt." left outer join lists as li on (li.pid  = pd.pid AND (li.type='medical_problem')) ";
    }

  if ( $type == 'Procedure' ||( strlen($form_lab_results)!=0) || $_POST['lab_results'] == true) {
    $sqlstmt = $sqlstmt." left outer join procedure_order as po on po.patient_id = pd.pid
          left outer join procedure_order_code as pc on pc.procedure_order_id = po.procedure_order_id
          left outer join procedure_report as pp on pp.procedure_order_id   = po.procedure_order_id
          left outer join procedure_type as pt on pt.procedure_code = pc.procedure_code and pt.lab_id = po.lab_id ";
  }

  if (strlen($form_lab_results)!=0 || $_POST['lab_results'] == true) {
      $sqlstmt = $sqlstmt." left outer join procedure_result as pr on pr.procedure_report_id = pp.procedure_report_id ";
  }

  //Immunization added in clinical report
  if (strlen($form_immunization)!=0 ) {
      $sqlstmt = $sqlstmt." LEFT OUTER JOIN immunizations as imm ON imm.patient_id = pd.pid
            LEFT OUTER JOIN codes as immc ON imm.cvx_code = immc.id ";
  }

  if(strlen($form_drug_name)!=0 || $_POST['form_drug'] == true) {
      $sqlstmt = $sqlstmt." left outer join prescriptions AS r on r.patient_id=pd.pid
            LEFT OUTER JOIN drugs AS d ON d.drug_id = r.drug_id";
  }

    if ( $type == 'Medical History') {
        $sqlstmt = $sqlstmt." left outer join history_data as hd on hd.pid   =  pd.pid
                  and (isnull(hd.tobacco)  = 0
                  or isnull(hd.alcohol)  = 0
                  or isnull(hd.recreational_drugs)  = 0)";
    }

    if($type == 'Service Codes') {
        $sqlstmt = $sqlstmt." left outer join billing as b on b.pid = pd.pid
              left outer join form_encounter as fe on fe.encounter = b.encounter and b.code_type = 'CPT4'
              left outer join codes as c on c.code = b.code ";
    }

  //where
    $whr_stmt="where 1=1";
    if(strlen($form_diagnosis) > 0 || $_POST['form_diagnosis_allergy'] == true
        || $_POST['form_diagnosis_medprb'] == true) {
        $whr_stmt=$whr_stmt." AND li.date >= ? AND li.date < DATE_ADD(?,INTERVAL 1 DAY) AND DATE(li.date) <= ?";
        array_push($sqlBindArray, $from_date, $to_date, date("Y-m-d"));
    }

    if(strlen($form_lab_results)!=0 || $_POST['lab_results'] == true) {
        $whr_stmt=$whr_stmt." AND pr.date >= ? AND pr.date < DATE_ADD(?,INTERVAL 1 DAY) AND DATE(pr.date) <= ?";
        array_push($sqlBindArray, $from_date, $to_date, date("Y-m-d"));
    }

    if(strlen($form_drug_name)!=0 || $_POST['form_drug'] == true) {
        $whr_stmt=$whr_stmt." AND r.date_modified >= ? AND r.date_modified < DATE_ADD(?, INTERVAL 1 DAY) AND DATE(r.date_modified) <= ?";
        array_push($sqlBindArray, $from_date, $to_date, date("Y-m-d"));
    }

    if($type == 'Medical History') {
        $whr_stmt=$whr_stmt." AND hd.date >= ? AND hd.date < DATE_ADD(?,INTERVAL 1 DAY) AND DATE(hd.date) <= ?";
         array_push($sqlBindArray, $from_date, $to_date, date("Y-m-d"));
    }

    if($type == 'Procedure') {
        $whr_stmt=$whr_stmt." AND po.date_ordered >= ? AND po.date_ordered < DATE_ADD(?, INTERVAL 1 DAY) AND DATE(po.date_ordered) <= ?";
        array_push($sqlBindArray, substr($from_date,0,10), substr($to_date,0,10), date("Y-m-d"));
    }

  if($type == "Service Codes") {
      $whr_stmt=$whr_stmt." AND b.date >= ? AND b.date < DATE_ADD(?, INTERVAL 1 DAY) AND DATE(b.date) <= ?";
      array_push($sqlBindArray, $from_date, $to_date, date("Y-m-d"));
  }

    if(strlen($form_lab_results) != 0 || $_POST['lab_results'] == true) {
        $whr_stmt= $whr_stmt." AND (pr.result LIKE ?) ";
        if(empty($form_lab_results)) $form_lab_results ="%";
        array_push($sqlBindArray, $form_lab_results);
    }

    if(strlen($form_drug_name) > 0 || $_POST['form_drug'] == true) {
        $whr_stmt .= " AND (
                     d.name LIKE ?
                     OR r.drug LIKE ?
                      ) ";
        if(empty($form_drug_name)) $form_drug_name ="%";
        array_push($sqlBindArray, $form_drug_name, $form_drug_name);
    }

    if($type == 'Service Codes') {
        if(strlen($form_service_codes) != 0) {
              $whr_stmt = $whr_stmt." AND (b.code = ?) ";
            $service_code = explode(":",$form_service_codes);
              array_push($sqlBindArray, $service_code[1]);
        }
    }

    if(strlen($patient_id) != 0) {
        $whr_stmt = $whr_stmt."   and pd.pid = ?";
        array_push($sqlBindArray, $patient_id);
    }

    if(strlen($age_from) != 0) {
        $whr_stmt = $whr_stmt."   and DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),pd.dob)), '%Y')+0 >= ?";
        array_push($sqlBindArray, $age_from);
    }

    if(strlen($age_to) != 0) {
        $whr_stmt = $whr_stmt."   and DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),pd.dob)), '%Y')+0 <= ?";
        array_push($sqlBindArray, $age_to);
    }

    if(strlen($sql_gender) != 0) {
        $whr_stmt = $whr_stmt."   and pd.sex = ?";
        array_push($sqlBindArray, $sql_gender);
    }

    if(strlen($sql_ethnicity) != 0) {
        $whr_stmt = $whr_stmt."   and pd.ethnicity = ?";
        array_push($sqlBindArray, $sql_ethnicity);
    }

    if(strlen($sql_race) != 0) {
        $whr_stmt = $whr_stmt."   and pd.race = ?";
        array_push($sqlBindArray, $sql_race);
    }

  if($facility != '') {
      $whr_stmt = $whr_stmt."   and f.id = ? ";
      array_push($sqlBindArray, $facility);
    }

    if(strlen($form_diagnosis) > 0) {
        $whr_stmt = $whr_stmt." AND (li.diagnosis LIKE ? or li.diagnosis LIKE ? or li.diagnosis LIKE ? or li.diagnosis = ?) ";
        array_push($sqlBindArray, $form_diagnosis."%", '%'.$form_diagnosis.'%', '%'.$form_diagnosis, $form_diagnosis);
    }

    //communication preferences added in clinical report
  if(strlen($communication) > 0 || $_POST['communication_check'] == true){
      if($communication == "allow_sms")  $whr_stmt .= " AND pd.hipaa_allowsms = 'YES' ";
      else if($communication == "allow_voice")  $whr_stmt .= " AND pd.hipaa_voice = 'YES' ";
      else if($communication == "allow_mail")  $whr_stmt .= " AND pd.hipaa_mail  = 'YES' ";
      else if($communication == "allow_email")  $whr_stmt .= " AND pd.hipaa_allowemail  = 'YES' ";
      else if($communication == "" && $_POST['communication_check'] == true){
          $whr_stmt .= " AND (pd.hipaa_allowsms = 'YES' OR pd.hipaa_voice = 'YES' OR pd.hipaa_mail  = 'YES' OR pd.hipaa_allowemail  = 'YES') ";
      }
  }

  //Immunization where condition for full text or short text
  if(strlen($form_immunization) > 0) {
      $whr_stmt .= " AND (
                  immc.code_text LIKE ?
                  OR immc.code_text_short LIKE ?
                  ) ";
      array_push($sqlBindArray, '%'.$form_immunization.'%', '%'.$form_immunization.'%');
  }

  // order by
    if ($_POST['form_pt_name'] == true){
      $odrstmt=$odrstmt.",patient_name";
    }

    if ($_POST['form_pt_age'] == true) {
        $odrstmt=$odrstmt.",patient_age";
    }

    if((strlen($form_diagnosis) > 0)){
        $odrstmt=$odrstmt.",lists_diagnosis";
    }elseif (($_POST['form_diagnosis_allergy'] == true) || ($_POST['form_diagnosis_medprb'] == true)){
        $odrstmt=$odrstmt.",lists_title";
    }

    if (($_POST['form_drug'] == true) || (strlen($form_drug_name) > 0)){
        $odrstmt=$odrstmt.",r.drug";
    }

    if (($_POST['ndc_no'] == true) && (strlen($form_drug_name) > 0)) {
        $odrstmt=$odrstmt.",d.ndc_number";
    }

  if (($_POST['lab_results'] == true) || (strlen($form_lab_results) > 0)) {
      $odrstmt=$odrstmt.",procedure_result_result";
  }

    if (strlen($communication) > 0 || $_POST['communication_check'] == true) {
      $odrstmt=$odrstmt.",ROUND((LENGTH(communications) - LENGTH(REPLACE(communications, ',', '')))/LENGTH(',')) , communications";
    }

    if($odrstmt == '') {
      $odrstmt = " ORDER BY patient_id";
    } else {
      $odrstmt = " ORDER BY ".ltrim($odrstmt,",");
    }

    if($type == 'Medical History') {
        $sqlstmt="select * from (".$sqlstmt." ".$whr_stmt." ".$odrstmt.",history_data_date desc) a group by patient_id";
    } else {
      $sqlstmt=$sqlstmt." ".$whr_stmt." ".$odrstmt;
    }

  $result = sqlStatement($sqlstmt,$sqlBindArray);

  $row_id = 1.1;//given to each row to identify and toggle
  $img_id = 1.2;
  $k=1.3;

  $answer = array('result' => $result, 'row_id' => $row_id, 'img_id' => $img_id, 'k' => $k );

  return $answer;
}

?>
