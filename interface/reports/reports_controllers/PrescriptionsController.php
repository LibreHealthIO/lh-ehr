<?php
/*
 * These functions are common functions used in Pre Billing Issues report.
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

  require_once("../globals.php");
  require_once("$srcdir/patient.inc");
  require_once("$srcdir/headers.inc.php");
  require_once("$srcdir/options.inc.php");
  require_once("../drugs/drugs.inc.php");
  require_once("$srcdir/formatting.inc.php");
  require_once("$srcdir/formdata.inc.php");
  require_once("../../library/report_functions.php");

  /** Current format date */
  $DateFormat = DateFormatRead();
  $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);
  $from_date  = fixDate($_POST['form_from_date'], date('Y-m-d'));
  $to_date    = fixDate($_POST['form_to_date'], date('Y-m-d'));
  $form_patient_id = trim($_POST['form_patient_id']);
  $form_drug_name  = trim($_POST['form_drug_name']);
  $form_lot_number = trim($_POST['form_lot_number']);
  $form_facility   = isset($_POST['form_facility']) ? $_POST['form_facility'] : '';

  /* This function prepares the results to be displayed
   * @parameters: void
   * @return: void
   * */
  function prepareAndShowResults() {
    $where = "r.date_modified >= '" . prepareDateBeforeSave($from_date) . "' AND " .
     "r.date_modified <= '" . prepareDateBeforeSave($to_date) . "'";
    //if ($form_patient_id) $where .= " AND r.patient_id = '$form_patient_id'";
    if ($form_patient_id) $where .= " AND p.pid = '$form_patient_id'";
    if ($form_drug_name ) $where .= " AND (d.name LIKE '$form_drug_name' OR r.drug LIKE '$form_drug_name')";
    if ($form_lot_number) $where .= " AND i.lot_number LIKE '$form_lot_number'";

    $query = "SELECT r.id, r.patient_id, " .
     "r.date_modified, r.dosage, r.route, r.interval, r.refills, r.drug, " .
     "d.name, d.ndc_number, d.form, d.size, d.unit, d.reactions, " .
     "s.sale_id, s.sale_date, s.quantity, " .
     "i.manufacturer, i.lot_number, i.expiration, " .
     "p.pid, ".
     "p.fname, p.lname, p.mname, u.facility_id " .
     "FROM prescriptions AS r " .
     "LEFT OUTER JOIN drugs AS d ON d.drug_id = r.drug_id " .
     "LEFT OUTER JOIN drug_sales AS s ON s.prescription_id = r.id " .
     "LEFT OUTER JOIN drug_inventory AS i ON i.inventory_id = s.inventory_id " .
     "LEFT OUTER JOIN patient_data AS p ON p.pid = r.patient_id " .
     "LEFT OUTER JOIN users AS u ON u.id = r.provider_id " .
     "WHERE $where " .
     //"ORDER BY p.lname, p.fname, r.patient_id, r.id, s.sale_id";
     "ORDER BY p.lname, p.fname, p.pid, r.id, s.sale_id";

    // echo "<!-- $query -->\n"; // debugging
    $res = sqlStatement($query);

    $last_patient_id      = 0;
    $last_prescription_id = 0;
    while ($row = sqlFetchArray($res)) {
     // If a facility is specified, ignore rows that do not match.
     if ($form_facility !== '') {
       if ($form_facility) {
         if ($row['facility_id'] != $form_facility) continue;
       }
       else {
         if (!empty($row['facility_id'])) continue;
       }
     }
     $patient_name    = $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'];
     //$patient_id      = $row['patient_id'];
     $patient_id      = $row['pid'];
     $prescription_id = $row['id'];
     $drug_name       = empty($row['name']) ? $row['drug'] : $row['name'];
     $ndc_number      = $row['ndc_number'];
     $drug_units      = $row['size'] . ' ' .
                     generate_display_field(array('data_type'=>'1','list_id'=>'drug_units'), $row['unit']);
     $refills         = $row['refills'];
     $reactions       = $row['reactions'];
     $instructed      = $row['dosage'] . ' ' .
                     generate_display_field(array('data_type'=>'1','list_id'=>'drug_form'), $row['form']) .
                     ' ' .
                         generate_display_field(array('data_type'=>'1','list_id'=>'drug_interval'), $row['interval']);
     //if ($row['patient_id'] == $last_patient_id) {
     if (strcmp($row['pid'], $last_patient_id) == 0) {
      $patient_name = '&nbsp;';
      $patient_id   = '&nbsp;';
      if ($row['id'] == $last_prescription_id) {
       $prescription_id = '&nbsp;';
       $drug_name       = '&nbsp;';
       $ndc_number      = '&nbsp;';
       $drug_units      = '&nbsp;';
       $refills         = '&nbsp;';
       $reactions       = '&nbsp;';
       $instructed      = '&nbsp;';
      }
     }
   echo '<tr>';
    echo '<td>';
      echo $patient_name;
    echo '</td>';
    echo '<td>';
      echo $patient_id;
    echo '</td>';
    echo '<td>';
      echo $prescription_id;
    echo '</td>';
    echo '<td>';
      echo $drug_name ;
    echo '</td>';
    echo '<td>';
      echo $ndc_number;
    echo '</td>';
    echo '<td>';
      echo $drug_units;
  echo '  </td>';
    echo '<td>';
      echo $refills;
    echo '</td>';
    echo '<td>';
      echo $instructed;
    echo '</td>';
    echo '<td>';
     echo $reactions;
    echo '</td>';
    echo '<td>';
     echo '<a href="../drugs/dispense_drug.php?sale_id="'; echo $row['sale_id'];
      echo "style='color:#0000ff' target='_blank'>";
       echo oeFormatShortDate($row['sale_date']);
     echo '</a>';
    echo '</td>';
    echo '<td>';
     echo $row['quantity'];
    echo '</td>';
    echo '<td>';
     echo $row['manufacturer'];
    echo '</td>';
    echo '<td>';
     echo $row['lot_number'];
    echo '</td>';
   echo '</tr>';
     $last_prescription_id = $row['id'];
     //$last_patient_id = $row['patient_id'];
     $last_patient_id = $row['pid'];
    } // end while
  }

?>
