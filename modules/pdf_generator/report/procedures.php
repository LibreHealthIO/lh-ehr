<?php
/**
 * The purpose of this code is to get the patient procedure orders data
 * in a formatted manner and store it in a single variable $content_pro.
 * The content of this variable will be printed in the PDF if required.
 *
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package Librehealth EHR
 * @author Abhinav(abhinavsingh22@hotmail.com)
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */

$auth_med = acl_check('patients'  , 'med');

if ($auth_med) {
  foreach($_POST as $name => $value){
    if(substr($name, 0, 15) == "procedure_order")         //there are two
    {                                                     //ways to select
      procedure_order_data(substr($name, 16));            //procedures orders                                               //in the form,
    }                                                     //in the form
  }
  if(count($_POST['procedures']) > 0){
    $procedures = $_POST['procedures'];
    foreach($procedures as $procedure_value){
      procedure_order_data($procedure_value);
    }
  }
}


function procedure_order_data($orderid){
    global $content_pro;
    $content_pro .= '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Procedure Order") . ':<br></span>';

    $orow = sqlQuery("SELECT " .
    "po.procedure_order_id, po.date_ordered, po.control_id, " .
    "po.order_status, po.specimen_type, po.patient_id, " .
    "pd.pid, pd.lname, pd.fname, pd.mname, pd.language, " .
    "fe.date, " .
    "pp.name AS labname, " .
    "u.lname AS ulname, u.fname AS ufname, u.mname AS umname " .
    "FROM procedure_order AS po " .
    "LEFT JOIN patient_data AS pd ON pd.pid = po.patient_id " .
    "LEFT JOIN procedure_providers AS pp ON pp.ppid = po.lab_id " .
    "LEFT JOIN users AS u ON u.id = po.provider_id " .
    "LEFT JOIN form_encounter AS fe ON fe.pid = po.patient_id AND fe.encounter = po.encounter_id " .
    "WHERE po.procedure_order_id = ?",
    $orderid);

    $content_pro .= "(" . oeFormatShortDate(substr($orow['date'], 0, 10)) . ")<br><table border=\"1\" style=\"background-color: silver\"><tr><td>" . xlt("Patient ID") . "</td>".
                    "<td>" . $orow['pid'] . "</td>".
                    "<td>" . xlt("Order ID") . "</td>".
                    "<td>" . $orow['procedure_order_id'] ;

    if ($orow['control_id']) {
        $content_pro .= xlt(" Lab: ") . $orow['control_id'];
    }
    $content_pro .= "</td></tr>";

    $content_pro .= "<tr><td>" . xlt("Patient Name") . "</td>".
                    "<td>" . $orow['lname'] . ', ' . $orow['fname'] . ' ' . $orow['mname'] . "</td>".
                    "<td>" . xlt("Ordered By") . "</td>".
                    "<td>" . $orow['ulname'] . ', ' . $orow['ufname'] . ' ' . $orow['umname'] . "</td></tr>";

    $content_pro .= "<tr><td>" . xlt("Order Date") . "</td>".
                    "<td>" . $orow['date_ordered'] . "</td>".
                    "<td>" . xlt("Print Date") . "</td>".
                    "<td>" . oeFormatShortDate(date('Y-m-d')) ."</td></tr>";

    $content_pro .= "<tr><td>" . xlt("Order Status") . "</td>".
                    "<td>" . $orow['order_status'] . "</td>".
                    "<td>" . xlt("Encounter Date") . "</td>".
                    "<td>" . oeFormatShortDate(substr($orow['date'], 0, 10)) . "</td></tr>";

    $content_pro .= "<tr><td>" . xlt("Lab") . "</td>".
                    "<td>" . $orow['labname'] . "</td>";
    if($orow['specimen_type'])  $content_pro .= "<td>" . xlt("Specimen Type") . "</td>";

    $content_pro .= "<td>" . $orow['specimen_type'] . "</td></tr></table><br><br>";

    $content_pro .= <<<_END
    <table border="1" style="background-color: silver">
    <tr>
    <th rowspan="2" valign="center">Ordered Procedure</th>
    <th colspan="5" align="center">Report</th>
    <th colspan="7" align="center">Results</th>
    </tr>
    <tr>
    <th align="center">Reported</th>
    <th align="center">Collected</th>
    <th align="center">Specimen</th>
    <th align="center">Status</th>
    <th align="center">Note</th>
    <th align="center">Code</th>
    <th align="center">Name</th>
    <th align="center">Abn</th>
    <th align="center">Value</th>
    <th align="center">Range</th>
    <th align="center">Units</th>
    <th align="center">Note</th>
    </tr>
_END;

$input_form=false;
$genstyles=true;
$finals_only=false;
$query = "SELECT " .
    "po.lab_id, po.date_ordered, pc.procedure_order_seq, pc.procedure_code, " .
    "pc.procedure_name, " .
    "pr.date_report, pr.date_report_tz, pr.date_collected, pr.date_collected_tz, " .
    "pr.procedure_report_id, pr.specimen_num, pr.report_status, pr.review_status, pr.report_notes " .
    "FROM procedure_order AS po " .
    "JOIN procedure_order_code AS pc ON pc.procedure_order_id = po.procedure_order_id " .
    "LEFT JOIN procedure_report AS pr ON pr.procedure_order_id = po.procedure_order_id AND " .
    "pr.procedure_order_seq = pc.procedure_order_seq " .
    "WHERE po.procedure_order_id = ? " .
    "ORDER BY pc.procedure_order_seq, pr.date_report, pr.procedure_report_id";

  $res = sqlStatement($query, array($orderid));
  $aNotes = array();
  $finals = array();
  $empty_results = array('result_code' => '');

  // Context for this call that may be used in other functions.
  $ctx_pdf = array(
    'lastpcid' => -1,
    'lastprid' => -1,
    'encount' => 0,
    'lino' => 0,
    'sign_list' => '',
    'seen_report_ids' => array(),
  );

  while ($row_pdf = sqlFetchArray($res)) {
    $report_id = empty($row_pdf['procedure_report_id']) ? 0 : ($row_pdf['procedure_report_id'] + 0);

    $query = "SELECT " .
      "ps.result_code, ps.result_text, ps.abnormal, ps.result, ps.range, " .
      "ps.result_status, ps.facility, ps.units, ps.comments, ps.document_id, ps.date " .
      "FROM procedure_result AS ps " .
      "WHERE ps.procedure_report_id = ? " .
      "ORDER BY ps.result_code, ps.procedure_result_id";

    $rres = sqlStatement($query, array($report_id));

    if ($finals_only) {
      // We are consolidating reports.
      if (sqlNumRows($rres)) {
        $rrowsets = array();
        // First pass creates a $rrowsets[$key] for each unique result code in *this* report, with
        // the value being an array of the corresponding result rows. This caters to multiple
        // occurrences of the same result code in the same report.
        while ($rrow_pdf = sqlFetchArray($rres)) {
          $result_code = empty($rrow_pdf['result_code']) ? '' : $rrow_pdf['result_code'];
          $key = sprintf('%05d/', $row_pdf['procedure_order_seq']) . $result_code;
          if (!isset($rrowsets[$key])) $rrowsets[$key] = array();
          $rrowsets[$key][] = $rrow_pdf;
        }
        // Second pass builds onto the array of final results for *all* reports, where each final
        // result for a given result code is its *array* of result rows from *one* of the reports.
        foreach ($rrowsets as $key => $rrowset) {
          // When two reports have the same date, use the result date to decide which is "latest".
          if (isset($finals[$key]) &&
            $row_pdf['date_report'] == $finals[$key][0]['date_report'] &&
            !empty($rrow_pdf['date']) && !empty($finals[$key][1]['date']) &&
            $rrow_pdf['date'] < $finals[$key][1]['date'])
          {
            $finals[$key][2] = true;
            continue;
          }
          $finals[$key] = array($row_pdf, $rrowset, isset($finals[$key]));
        }
      }
      else {
        // We have no results for this report.
        $key = sprintf('%05d/', $row_pdf['procedure_order_seq']);
        $finals[$key] = array($row_pdf, array($empty_results), false);
      }
    }
    else {
      // We are showing all results for all reports.
      if (sqlNumRows($rres)) {
        while ($rrow = sqlFetchArray($rres)) {
          generate_result_row_pdf($ctx_pdf, $row_pdf, $rrow, false);
        }
      }
      else {
        generate_result_row_pdf($ctx_pdf, $row_pdf, $empty_results, false);
      }
    }
}

  if ($finals_only) {
    ksort($finals);
    foreach ($finals as $final) {
      foreach ($final[1] as $rrow) {
        generate_result_row_pdf($ctx_pdf, $final[0], $rrow_pdf, $final[2]);
      }
    }
  }
  $content_pro .= "</table><br><br>";
}

if($content_pro)
{
  $pdf->Ln(2);
  $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());
  $pdf->Ln(2);
  $pdf->WriteHTML($content_pro, true, false, false, false, '');
}
