<?php
/*
 * These functions are common functions used in Patient List creation report.
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
     require_once("$srcdir/payment_jav.inc.php");
     require_once("$srcdir/headers.inc.php");
     require_once("../../library/report_functions.php");
     /** Current format of date  */

     $DateFormat=DateFormatRead();
     $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);
     $search_options = [
     "Demographics"  => xl("Demographics"),
     "Problems"      => xl("Problems"),
     "Medications"   => xl("Medications"),
     "Allergies"     => xl("Allergies"),
     "Lab results"   => xl("Lab Results"),
     "Communication" => xl("Communication")
 ];
 $comarr = [
     "allow_sms"   => xl("Allow SMS"),
     "allow_voice" => xl("Allow Voice Message"),
     "allow_mail"  => xl("Allow Mail Message"),
     "allow_email" => xl("Allow Email")
 ];
     $_POST['form_details'] = true;
 function add_date($givendate, $day = 0, $mth = 0, $yr = 0)
 {
     $DateFormat = DateFormatRead();
         $cd = strtotime($givendate);
         $newdate = date($DateFormat . ' H:i:s', mktime(date('h', $cd),
         date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
         date('d',$cd)+$day, date('Y',$cd)+$yr));
         return $newdate;
         }
 if ($_POST['form_from_date'] != "") {
         $from_date = $_POST['form_from_date'];
 }
 if ($_POST['form_to_date'] != "") {
         $to_date = $_POST['form_to_date'];
 }

     //echo "<pre>";print_r($_POST);
     $patient_id = trim($_POST["patient_id"]);
     $age_from = $_POST["age_from"];
     $age_to = $_POST["age_to"];
     $sql_gender = $_POST["gender"];
     $sql_ethnicity = $_POST["cpms_ethnicity"];
     $sql_race=$_POST["race"];
     $form_drug_name = trim($_POST["form_drug_name"]);
     $form_diagnosis = trim($_POST["form_diagnosis"]);
     $form_lab_results = trim($_POST["form_lab_results"]);
     $form_service_codes = trim($_POST["form_service_codes"]);
     $form_immunization = trim($_POST["form_immunization"]);
     $communication = trim($_POST["communication"]);

/* This function prepares the results to be displayed
 * @parameters: void
 * @return: void
 * */
function prepareAndShowResults() {
  $from_date = prepareDateBeforeSave($from_date);
  $to_date   = prepareDateBeforeSave($to_date);

      // SQL scripts for the various searches
      $sqlBindArray = array();
      if ($_POST['form_refresh']){

          $sqlstmt = "select
                      pd.date as patient_date,
                      concat(pd.lname, ', ', pd.fname) AS patient_name,
                      pd.pid AS patient_id,
                      DATE_FORMAT(FROM_DAYS(DATEDIFF('".date('Y-m-d H:i:s')."',pd.dob)), '%Y')+0 AS patient_age,
                      pd.sex AS patient_sex,
                      pd.race AS patient_race,pd.ethnicity AS patient_ethinic,
                      concat(u.lname, ', ', u.fname)  AS users_provider";

          $srch_option = $_POST['srch_option'];
          switch ($srch_option) {
              case "Medications":
              case "Allergies":
              case "Problems":
                  $sqlstmt=$sqlstmt.",li.date AS lists_date,
                         li.diagnosis AS lists_diagnosis,
                              li.title AS lists_title";
                  break;
              case "Lab results":
                  $sqlstmt = $sqlstmt.",pr.date AS procedure_result_date,
                          pr.facility AS procedure_result_facility,
                          pr.units AS procedure_result_units,
                          pr.result AS procedure_result_result,
                          pr.range AS procedure_result_range,
                          pr.abnormal AS procedure_result_abnormal,
                          pr.comments AS procedure_result_comments,
                          pr.document_id AS procedure_result_document_id";
                  break;
              case "Communication":
                  $sqlstmt = $sqlstmt.",REPLACE(REPLACE(concat_ws(',',IF(pd.hipaa_allowemail = 'YES', 'Allow Email','NO'),IF(pd.hipaa_allowsms = 'YES', 'Allow SMS','NO') , IF(pd.hipaa_mail = 'YES', 'Allow Mail Message','NO') , IF(pd.hipaa_voice = 'YES', 'Allow Voice Message','NO') ), ',NO',''), 'NO,','') as communications";
                  break;
          }

          //from
          $sqlstmt=$sqlstmt." from patient_data as pd left outer join users as u on u.id = pd.providerid";
          //JOINS
          switch ($srch_option) {
              case "Problems":
                  $sqlstmt = $sqlstmt." left outer join lists as li on (li.pid  = pd.pid AND li.type='medical_problem')";
                  break;
              case "Medications":
                  $sqlstmt = $sqlstmt." left outer join lists as li on (li.pid  = pd.pid AND (li.type='medication')) ";
                  break;
              case "Allergies":
                  $sqlstmt = $sqlstmt." left outer join lists as li on (li.pid  = pd.pid AND (li.type='allergy')) ";
                  break;
              case "Lab results":

                  $sqlstmt = $sqlstmt." left outer join procedure_order as po on po.patient_id = pd.pid
                          left outer join procedure_order_code as pc on pc.procedure_order_id = po.procedure_order_id
                          left outer join procedure_report as pp on pp.procedure_order_id = po.procedure_order_id
                          left outer join procedure_type as pt on pt.procedure_code = pc.procedure_code and pt.lab_id = po.lab_id
                          left outer join procedure_result as pr on pr.procedure_report_id = pp.procedure_report_id";
                  break;
          }

          //WHERE Conditions started
          $whr_stmt="where 1=1";
          switch ($srch_option) {
              case "Medications":
              case "Allergies":
                  $whr_stmt=$whr_stmt." AND li.date >= ? AND li.date < DATE_ADD(?, INTERVAL 1 DAY) AND li.date <= ?";
                  array_push($sqlBindArray, $from_date, $to_date, date("Y-m-d H:i:s"));
                  break;
              case "Problems":
                  $whr_stmt = $whr_stmt." AND li.title != '' ";
                  $whr_stmt=$whr_stmt." AND li.date >= ? AND li.date < DATE_ADD(?, INTERVAL 1 DAY) AND li.date <= ?";
                  array_push($sqlBindArray, $from_date, $to_date, date("Y-m-d H:i:s"));
                  break;
              case "Lab results":
                  $whr_stmt=$whr_stmt." AND pr.date >= ? AND pr.date < DATE_ADD(?, INTERVAL 1 DAY) AND pr.date <= ?";
                  $whr_stmt= $whr_stmt." AND (pr.result != '') ";
                  array_push($sqlBindArray, $from_date, $to_date, date("Y-m-d H:i:s"));
                  break;
              case "Communication":
                  $whr_stmt .= " AND (pd.hipaa_allowsms = 'YES' OR pd.hipaa_voice = 'YES' OR pd.hipaa_mail  = 'YES' OR pd.hipaa_allowemail  = 'YES') ";
                  break;
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

          if($srch_option == "Communication" && strlen($communication) > 0){
          if ($communication == "allow_sms") {
              $whr_stmt .= " AND pd.hipaa_allowsms = 'YES' ";
          } else {
              if ($communication == "allow_voice") {
                  $whr_stmt .= " AND pd.hipaa_voice = 'YES' ";
              } else {
                  if ($communication == "allow_mail") {
                      $whr_stmt .= " AND pd.hipaa_mail  = 'YES' ";
                  } else {
                      if ($communication == "allow_email") {
                          $whr_stmt .= " AND pd.hipaa_allowemail  = 'YES' ";
                      }
          }
              }
          }
      }

          //Sorting By filter fields
          $sortby = $_REQUEST['sortby'];
           $sortorder = $_REQUEST['sortorder'];

           // This is for sorting the records.
           switch ($srch_option) {
              case "Medications":
              case "Allergies":
              case "Problems":
              $sort = ["lists_date", "lists_diagnosis", "lists_title"];
              if ($sortby == "") {
                  $sortby = $sort[1];
              }
                  break;
              case "Lab results":
              $sort = [
                  "procedure_result_date",
                  "procedure_result_facility",
                  "procedure_result_units",
                  "procedure_result_result",
                  "procedure_result_range",
                  "procedure_result_abnormal"
              ];
                  //$odrstmt = " procedure_result_result";
                  break;
              case "Communication":
                  //$commsort = " ROUND((LENGTH(communications) - LENGTH(REPLACE(communications, ',', '')))/LENGTH(','))";
              $sort = ["patient_date", "patient_name", "patient_id", "patient_age", "patient_sex", "users_provider", "communications"];
              if ($sortby == "") {
                  $sortby = $sort[6];
              }
                  //$odrstmt = " ROUND((LENGTH(communications) - LENGTH(REPLACE(communications, ',', '')))/LENGTH(',')) , communications";
                  break;
              case "Demographics":
              $sort = [
                  "patient_date",
                  "patient_name",
                  "patient_id",
                  "patient_age",
                  "patient_sex",
                  "patient_race",
                  "patient_ethinic",
                  "users_provider"
              ];
                  break;
          }
              if($sortby == "") {
                  $sortby = $sort[0];
              }
              if($sortorder == "") {
                  $sortorder = "asc";
              }
              for($i = 0; $i < count($sort); $i++) {
                    $sortlink[$i] = "<a href=\"#\" onclick=\"sortingCols('$sort[$i]','asc');\" ><img src=\"../../images/sortdown.gif\" border=0 alt=\"".xla('Sort Up')."\"></a>";
              }
              for($i = 0; $i < count($sort); $i++) {
                  if($sortby == $sort[$i]) {
                      switch($sortorder) {
                  case "asc"      :
                      $sortlink[$i] = "<a href=\"#\" onclick=\"sortingCols('$sortby','desc');\" ><img src=\"../../images/sortup.gif\" border=0 alt=\"" . htmlspecialchars(xl('Sort Up'),
                              ENT_QUOTES) . "\"></a>";
                      break;
                  case "desc"     :
                      $sortlink[$i] = "<a href=\"#\" onclick=\"sortingCols('$sortby','asc');\" onclick=\"top.restoreSession()\"><img src=\"../../images/sortdown.gif\" border=0 alt=\"" . xla('Sort Down') . "\"></a>";
                      break;
              }
              break;
                  }
              }

          switch ($srch_option) {
              case "Medications":
              case "Allergies":
              case "Problems":
                  $odrstmt = " ORDER BY lists_date asc";
                  break;
              case "Lab results":
                  $odrstmt = " ORDER BY procedure_result_date asc";
                  break;
              case "Communication":
                  $odrstmt = "ORDER BY ROUND((LENGTH(communications) - LENGTH(REPLACE(communications, ',', '')))/LENGTH(',')) asc, communications asc";
                  break;
              case "Demographics":
                  $odrstmt = " ORDER BY patient_date asc";
                  //$odrstmt = " ROUND((LENGTH(communications) - LENGTH(REPLACE(communications, ',', '')))/LENGTH(',')) , communications";
                  break;
          }
          if(!empty($_REQUEST['sortby']) && !empty($_REQUEST['sortorder'])){
              if($_REQUEST['sortby'] =="communications"){
                  $odrstmt = "ORDER BY ROUND((LENGTH(communications) - LENGTH(REPLACE(communications, ',', '')))/LENGTH(',')) ".escape_sort_order($_REQUEST['sortorder']).", communications ".escape_sort_order($_REQUEST['sortorder']);
              }else{
              $odrstmt = "ORDER BY " . escape_identifier($_REQUEST['sortby'], $sort, true) . " " . escape_sort_order($_REQUEST['sortorder']);
              }
          }

          $sqlstmt=$sqlstmt." ".$whr_stmt." ".$odrstmt;
          //echo $sqlstmt."<hr>";
          $result = sqlStatement($sqlstmt,$sqlBindArray);
          //print_r($result);
          $row_id = 1.1;//given to each row to identify and toggle
          $img_id = 1.2;
          $k=1.3;

          if(sqlNumRows($result) > 0){
          $patArr = [];
          $patDataArr = [];
              $smoke_codes_arr = getSmokeCodes();
              while ($row = sqlFetchArray($result)) {

                      $patArr[] = $row['patient_id'];
                      $patInfoArr = [];
                      $patInfoArr['patient_id'] = $row['patient_id'];
                      //Diagnosis Check
                      if($srch_option == "Medications" || $srch_option == "Allergies" || $srch_option == "Problems"){
                          $patInfoArr['lists_date'] = $row['lists_date'];
                          $patInfoArr['lists_diagnosis'] = $row['lists_diagnosis'];
                          $patInfoArr['lists_title'] = $row['lists_title'];
                          $patInfoArr['patient_name'] = $row['patient_name'];
                          $patInfoArr['patient_age'] = $row['patient_age'];
                          $patInfoArr['patient_sex'] = $row['patient_sex'];
                          $patInfoArr['patient_race'] = $row['patient_race'];
                          $patInfoArr['patient_ethinic'] = $row['patient_ethinic'];
                          $patInfoArr['users_provider'] = $row['users_provider'];
                      }elseif($srch_option == "Lab results"){
                          $patInfoArr['procedure_result_date'] = $row['procedure_result_date'];
                          $patInfoArr['procedure_result_facility'] = $row['procedure_result_facility'];
                          $patInfoArr['procedure_result_units'] = $row['procedure_result_units'];
                          $patInfoArr['procedure_result_result'] = $row['procedure_result_result'];
                          $patInfoArr['procedure_result_range'] = $row['procedure_result_range'];
                          $patInfoArr['procedure_result_abnormal'] = $row['procedure_result_abnormal'];
                          $patInfoArr['procedure_result_comments'] = $row['procedure_result_comments'];
                          $patInfoArr['procedure_result_document_id'] = $row['procedure_result_document_id'];
                      }elseif($srch_option == "Communication"){
                          $patInfoArr['patient_date'] = $row['patient_date'];
                          $patInfoArr['patient_name'] = $row['patient_name'];
                          $patInfoArr['patient_age'] = $row['patient_age'];
                          $patInfoArr['patient_sex'] = $row['patient_sex'];
                          $patInfoArr['users_provider'] = $row['users_provider'];
                          $patInfoArr['communications'] = $row['communications'];
                      }elseif($srch_option == "Demographics"){
                          $patInfoArr['patient_date'] = $row['patient_date'];
                          $patInfoArr['patient_name'] = $row['patient_name'];
                          $patInfoArr['patient_age'] = $row['patient_age'];
                          $patInfoArr['patient_sex'] = $row['patient_sex'];
                          $patInfoArr['patient_race'] = $row['patient_race'];
                          $patInfoArr['patient_ethinic'] = $row['patient_ethinic'];
                          $patInfoArr['users_provider'] = $row['users_provider'];
                      }

                          $patFinalDataArr[] = $patInfoArr;

              }


              echo '<br>';

              echo '<input type="hidden" name="sortby" id="sortby" value="'; echo attr($sortby); echo'" />';
              echo '<input type="hidden" name="sortorder" id="sortorder" value="'; echo attr($sortorder); echo '" />';
              echo '<div id = "report_results">';
                  echo '<table>';
                      echo '<tr>';
                  echo '<td class="text"><strong>'; echo xlt('Total Number of Patients'); echo ':</strong>&nbsp;<span
                          id="total_patients">'; echo attr(count(array_unique($patArr))); echo '</span></td>';
                      echo '</tr>';
                  echo '</table>';

                  echo '<table width=90% align="center" cellpadding="5" cellspacing="0" style="font-family:tahoma;color:black;" border="0">';

                  if($srch_option == "Medications" || $srch_option == "Allergies" || $srch_option == "Problems"){
                      echo '<tr style="font-size:15px;">';
                          echo '<td width="15%"><b>'; echo xlt('Diagnosis Date'); echo $sortlink[0]; echo '</b></td>';
                          echo '<td width="15%"><b>'; echo xlt('Diagnosis'); echo $sortlink[1]; echo '</b></td>';
                          echo '<td width="15%"><b>'; echo xlt('Diagnosis Name'); echo $sortlink[2]; echo '</b></td>';
                          echo '<td width="15%"><b>'; echo xlt('Patient Name'); echo '</b></td>';
                          echo '<td width="5%"><b>'; echo xlt('PID'); echo '</b></td>';
                          echo '<td width="5%"><b>'; echo xlt('Age'); echo '</b></td>';
                          echo '<td width="10%"><b>'; echo xlt('Gender'); echo '</b></td>';
                          echo '<td colspan=4><b>'; echo xlt('Provider'); echo '</b></td>';
                      echo '</tr>';
                foreach($patFinalDataArr as $patKey => $patDetailVal){
                              echo '<tr bgcolor = "#CCCCCC" style="font-size:15px;">';
                                  echo '<td >'; echo text($patDetailVal['lists_date']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['lists_diagnosis']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['lists_title']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_name']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_id']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_age']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_sex']); echo '</td>';
                                  echo '<td colspan=4>'; echo text($patDetailVal['users_provider']); echo '</td>';
                              echo '</tr>';
                  }
                  }elseif($srch_option == "Lab results"){
                      echo '<tr bgcolor="#C3FDB8" align= "left" >';
                          echo '<td width="15%"><b>'; echo xlt('Date'); echo $sortlink[0]; echo '</b></td>';
                          echo '<td width="15%"><b>'; echo xlt('Facility');  echo $sortlink[1]; echo '</b></td>';
                          echo '<td width="10%"><b>'; echo xlt('Unit'); echo '</b>'; echo $sortlink[2]; echo '</td>';
                          echo '<td width="10%"><b>'; echo xlt('Result'); echo '</b>'; echo $sortlink[3]; echo '</td>';
                          echo '<td width="10%"><b>'; echo xlt('Range'); echo '</b>';  echo $sortlink[4]; echo '</td>';
                          echo '<td width="10%"><b>'; echo xlt('Abnormal');  echo $sortlink[5]; echo '</b></td>';
                          echo '<td><b><'; echo xlt('Comments'); echo '</b></td>';
                          echo '<td width="5%"><b>'; echo xlt('Document ID'); echo '</b></td>';
                          echo '<td width="5%"><b>'; echo xlt('PID'); echo '</b></td>';
                      echo '</tr>';
                      foreach ($patFinalDataArr as $patKey => $labResInsideArr) {
                              echo '<tr bgcolor = "#CCCCCC" >';
                                  echo '<td>'; echo text($labResInsideArr['procedure_result_date']); echo '&nbsp;</td>';
                                  echo '<td>'; echo text($labResInsideArr['procedure_result_facility'],ENT_NOQUOTES); echo '&nbsp;</td>';
                                  echo '<td>'; echo generate_display_field(['data_type' => '1', 'list_id' => 'proc_unit'],
                                  $labResInsideArr['procedure_result_units']); echo '&nbsp;</td>';
                                  echo '<td>'; echo text($labResInsideArr['procedure_result_result']); echo '&nbsp;</td>';
                                  echo '<td>'; echo text($labResInsideArr['procedure_result_range']); echo '&nbsp;</td>';
                                  echo '<td>'; echo text($labResInsideArr['procedure_result_abnormal']); echo '&nbsp;</td>';
                                  echo '<td>'; echo text($labResInsideArr['procedure_result_comments']); echo '&nbsp;</td>';
                                  echo '<td>'; echo text($labResInsideArr['procedure_result_document_id']); echo '&nbsp;</td>';
                                  echo '<td colspan="3">'; echo text($labResInsideArr['patient_id']); echo '&nbsp;</td>';
                             echo '</tr>';
                          }
                  }elseif($srch_option == "Communication"){
                      echo '<tr style="font-size:15px;">';
                          echo '<td width="15%"><b>'; echo xlt('Date'); echo '</b>'; echo $sortlink[0]; echo '</td>';
                          echo '<td width="20%"><b>'; echo xlt('Patient Name'); echo '</b>'; echo $sortlink[1]; echo '</td>';
                          echo '<td width="5%"><b>'; echo xlt('PID'); echo '</b>'; echo $sortlink[2]; echo '</td>';
                          echo '<td width="5%"><b>'; echo xlt('Age'); echo '</b>'; echo $sortlink[3]; echo '</td>';
                          echo '<td width="10%"><b>'; echo xlt('Gender'); echo '</b>'; echo $sortlink[4]; echo '</td>';
                          echo '<td width="15%"><b>'; echo xlt('Provider'); echo '</b>'; echo $sortlink[5]; echo '</td>';
                          echo '<td ><b>'; echo xlt('Communication'); echo '</b>'; echo $sortlink[6]; echo '</td>';
                      echo '</tr>';
                  foreach($patFinalDataArr as $patKey => $patDetailVal){
                              echo '<tr bgcolor = "#CCCCCC" >';
                          echo '<td>'; if ($patDetailVal['patient_date'] != '') {
                                  echo text($patDetailVal['patient_date']);
                              } else {
                                  echo "";
                              } echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_name']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_id']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_age']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_sex']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['users_provider']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['communications']); echo '</td>';
                             echo '</tr>';
                          }
                  }elseif($srch_option == "Demographics"){
                      echo '<tr style="font-size:15px;">';
                          echo '<td width="15%"><b>'; echo xlt('Date'); echo '</b>'; echo $sortlink[0]; echo '</td>';
                          echo '<td width="20%"><b>'; echo xlt('Patient Name'); echo '</b>';  echo $sortlink[1]; echo '</td>';
                          echo '<td width="15%"><b>'; echo xlt('PID'); echo '</b>'; echo $sortlink[2]; echo '</td>';
                          echo '<td width="5%"><b>'; echo xlt('Age'); echo '</b>'; echo $sortlink[3]; echo '</td>';
                          echo '<td width="10%"><b>'; echo xlt('Gender'); echo '</b>'; echo $sortlink[4]; echo '</td>';
                          echo '<td width="20%"><b>'; echo xlt('Race'); echo '</b>'; echo $sortlink[5]; echo '</td>';
                          echo '<td colspan=5><b>'; echo xlt('Provider'); echo '</b>'; echo $sortlink[7]; echo '</td>';
                      echo '</tr>';
                          foreach($patFinalDataArr as $patKey => $patDetailVal){
                              echo '<tr bgcolor = "#CCCCCC" style="font-size:15px;">';
                        echo '<td>'; if ($patDetailVal['patient_date'] != '') {
                                  echo date($DateFormat . " H:i:s", strtotime($patDetailVal['patient_date']));
                              } else {
                                  echo "";
                              }; echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_name']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_id']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_age']); echo '</td>';
                                  echo '<td >'; echo text($patDetailVal['patient_sex']); echo '</td>';
                                  echo '<td>'; echo generate_display_field(['data_type' => '36', 'list_id' => 'race'], $patDetailVal['patient_race']); echo '</td>';
                                  echo '<td colspan=5>'; echo text($patDetailVal['users_provider']); echo '</td>';
                              echo '</tr>';
                      }
                  }

                  echo '</table>';
                   //Main table ends
      } else {//End if $result
                  echo '<table>';
                      echo '<tr>';
                          echo '<td class="text">&nbsp;&nbsp;'; echo xlt('No records found.'); echo '</td>';
                      echo '</tr>';
                  echo '</table>';
              }
              echo '</div>';

          }else{//End if form_refresh
      echo '<div class="text">'; echo xlt('Please input search criteria above, and click Submit to view results.'); echo '</div>';
          }
}
