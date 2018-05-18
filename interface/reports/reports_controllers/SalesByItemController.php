<?php
/*
 * These functions are common functions used in Receipt by method report.
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

 $sanitize_all_escapes=true;
 $fake_register_globals=false;

 require_once("../globals.php");
 require_once("../../library/report_functions.php");
 require_once("$srcdir/patient.inc");
 require_once("$srcdir/acl.inc");
 require_once("$srcdir/headers.inc.php");
 require_once("$srcdir/formatting.inc.php");
 require_once "$srcdir/options.inc.php";
 require_once "$srcdir/formdata.inc.php";
 /** Current format date */
 $DateFormat = DateFormatRead();
 $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

 $form_provider  = $_POST['form_provider'];
 if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
   $form_details  = $_POST['form_details']      ? true : false;
 }
 else
 {
   $form_details = false;
 }

 function display_desc($desc) {
   if (preg_match('/^\S*?:(.+)$/', $desc, $matches)) {
     $desc = $matches[1];
   }
   return $desc;
 }

 function thisLineItem($patient_id, $encounter_id, $rowcat, $description, $transdate, $qty, $amount, $irnumber='') {
   global $product, $category, $producttotal, $productqty, $cattotal, $catqty, $grandtotal, $grandqty;
   global $productleft, $catleft;

   $invnumber = $irnumber ? $irnumber : "$patient_id.$encounter_id";
   $rowamount = sprintf('%01.2f', $amount);

    $patdata = sqlQuery("SELECT " .
   "p.fname, p.mname, p.lname, p.pid, p.DOB, " .
   "p.street, p.city, p.state, p.postal_code, " .
   "p.ss, p.sex, p.status, p.phone_home, " .
   "p.phone_biz, p.phone_cell, p.hipaa_notice " .
   "FROM patient_data AS p " .
   "WHERE p.pid = ? LIMIT 1", array($patient_id));

   $pat_name = $patdata['fname'] . ' ' . $patdata['mname'] . ' ' . $patdata['lname'];

   if (empty($rowcat)) $rowcat = xl('None');
   $rowproduct = $description;
   if (! $rowproduct) $rowproduct = xl('Unknown');

   if ($product != $rowproduct || $category != $rowcat) {
     if ($product) {
       // Print product total.
       if ($_POST['form_csvexport']) {
         if (! $_POST['form_details']) {
           echo '"' . display_desc($category) . '",';
           echo '"' . display_desc($product)  . '",';
           echo '"' . $productqty             . '",';
           echo '"'; bucks($producttotal); echo '"' . "\n";
         }
       }
       else {
  echo '<tr bgcolor="#ddddff">';
   echo '<td class="detail">';
    echo text(display_desc($catleft)); $catleft = " ";
   echo '</td>';
   echo '<td class="detail" colspan="3">';
    if ($_POST['form_details']) echo xlt('Total for') . ' '; echo text(display_desc($product));
   echo '</td>';
   if($GLOBALS['sales_report_invoice'] == 0 || $GLOBALS['sales_report_invoice'] == 2) {
   echo '<td>&nbsp;';
   echo '</td>';
   }
   echo '<td align="right">&nbsp;';
   echo '</td>';
   echo '<td align="right">';
    echo text($productqty);
   echo '</td>';
   echo '<td align="right">';
    text(bucks($producttotal));
   echo '</td>';
  echo '</tr>';
       } // End not csv export
     }
     $producttotal = 0;
     $productqty = 0;
     $product = $rowproduct;
     $productleft = $product;
   }

   if ($category != $rowcat) {
     if ($category) {
       // Print category total.
       if (!$_POST['form_csvexport']) {

  echo '<tr bgcolor="#ffdddd">';
   echo '<td class="detail">&nbsp;';
   echo '</td>';
   echo '<td class="detail" colspan="3">';
    echo xlt('Total for category') . ' '; echo text(display_desc($category));
   echo '</td>';
   if($GLOBALS['sales_report_invoice'] == 0 || $GLOBALS['sales_report_invoice'] == 2) {
   echo '<td>&nbsp;';
   echo '</td>';
   }
   echo '<td align="right">&nbsp;';
   echo '</td>';
   echo '<td align="right">';
    echo text($catqty);
   echo '</td>';
   echo '<td align="right">';
    text(bucks($cattotal));
   echo '</td>';
  echo '</tr>';
       } // End not csv export
     }
     $cattotal = 0;
     $catqty = 0;
     $category = $rowcat;
     $catleft = $category;
   }

   if ($_POST['form_details']) {
     if ($_POST['form_csvexport']) {
       echo '"' . display_desc($category ) . '",';
       echo '"' . display_desc($product  ) . '",';
       echo '"' . oeFormatShortDate(display_desc($transdate)) . '",';
       if($GLOBALS['sales_report_invoice'] == 1 || $GLOBALS['sales_report_invoice'] == 2 ) {
        echo '"' . $pat_name . '",';
       }
       if($GLOBALS['sales_report_invoice'] == 0 || $GLOBALS['sales_report_invoice'] == 2) {
         echo '"' . display_desc($invnumber) . '",';
       }
       if($GLOBALS['sales_report_invoice'] == 1) {
         echo '"' . $patient_id . '",';
       }
      // echo '"' . display_desc($invnumber) . '",';
       echo '"' . display_desc($qty      ) . '",';
       echo '"'; bucks($rowamount); echo '"' . "\n";
     }
     else {

  echo '<tr>';
   echo '<td class="detail">';
    echo text(display_desc($catleft)); $catleft = " ";
   echo '</td>';
   echo '<td class="detail">';
    echo text(display_desc($productleft)); $productleft = " ";
   echo '</td>';
   echo '<td>';
    echo text(oeFormatShortDate($transdate));
   echo '</td>';
    if($GLOBALS['sales_report_invoice'] == 0 || $GLOBALS['sales_report_invoice'] == 2) {
   echo '<td>&nbsp;';
   echo '</td>';
    } if($GLOBALS['sales_report_invoice'] == 1 || $GLOBALS['sales_report_invoice'] == 2 ) {
   echo '<td>';
    echo text($pat_name);
   echo '</td>';
    }
   echo '<td class="detail">';
   if($GLOBALS['sales_report_invoice'] == 0 || $GLOBALS['sales_report_invoice'] == 2) {
    echo "<a href='../patient_file/pos_checkout.php?ptid="; echo attr($patient_id); echo '&enc='; echo attr($encounter_id); echo"'>";
    echo text($invnumber); echo '</a>';
    }
    if($GLOBALS['sales_report_invoice'] == 1 ) {
      echo text($patient_id);
     }
   echo '</td>';
   if($GLOBALS['sales_report_invoice'] == 0) {
   echo '<td>&nbsp;';
   echo '</td>';
   }
   echo '<td align="right">';
    echo text($qty);
   echo '</td>';
   echo '<td align="right">';
    text(bucks($rowamount));
   echo '</td>';
  echo '</tr>';

     } // End not csv export
   } // end details
   $producttotal += $rowamount;
   $cattotal     += $rowamount;
   $grandtotal   += $rowamount;
   $productqty   += $qty;
   $catqty       += $qty;
   $grandqty     += $qty;
 } // end function

   if (! acl_check('acct', 'rep')) die(xl("Unauthorized access."));


     if (isset($_POST['form_from_date']) && isset($_POST['form_to_date']) && !empty($_POST['form_to_date']) && $_POST['form_from_date']) {
         $from_date = fixDate($_POST['form_from_date'], date(DateFormatRead(true)));
         $to_date   = fixDate($_POST['form_to_date']  , date(DateFormatRead(true)));
     }
   $form_facility  = $_POST['form_facility'];

/* This function prepares the query to get results
 * @parameters: $from_date, $to_date, $form_facility, $form_provider
 * @return: $query --> the query string
 * */
function prepareQuery() {
  global $from_date, $to_date, $form_facility, $form_provider;
  $sqlBindArray = array();
  $query = "SELECT b.fee, b.pid, b.encounter, b.code_type, b.code, b.units, " .
    "b.code_text, fe.date, fe.facility_id, fe.provider_id, fe.invoice_refno, lo.title " .
    "FROM billing AS b " .
    "JOIN code_types AS ct ON ct.ct_key = b.code_type " .
    "JOIN form_encounter AS fe ON fe.pid = b.pid AND fe.encounter = b.encounter " .
    "LEFT JOIN codes AS c ON c.code_type = ct.ct_id AND c.code = b.code AND c.modifier = b.modifier " .
    "LEFT JOIN list_options AS lo ON lo.list_id = 'superbill' AND lo.option_id = c.superbill " .
    "WHERE b.code_type != 'COPAY' AND b.activity = 1 AND b.fee != 0 AND " .
    "fe.date >= ? AND fe.date <= ?";
    array_push($sqlBindArray,$from_date,$to_date);
  // If a facility was specified.
  if ($form_facility) {
    $query .= " AND fe.facility_id = ?";
    array_push($sqlBindArray,$form_facility);
  }
  if ($form_provider) {
    $query .= " AND fe.provider_id = ?";
    array_push($sqlBindArray,$form_provider);
  }
  $query .= " ORDER BY lo.title, b.code, fe.date, fe.id";
  //
  $res = sqlStatement($query,$sqlBindArray);
  while ($row = sqlFetchArray($res)) {
    thisLineItem($row['pid'], $row['encounter'],
      $row['title'], $row['code'] . ' ' . $row['code_text'],
      substr($row['date'], 0, 10), $row['units'], $row['fee'], $row['invoice_refno']);
  }
  //
  $sqlBindArray = array();
  $query = "SELECT s.sale_date, s.fee, s.quantity, s.pid, s.encounter, " .
    "d.name, fe.date, fe.facility_id, fe.provider_id, fe.invoice_refno " .
    "FROM drug_sales AS s " .
    "JOIN drugs AS d ON d.drug_id = s.drug_id " .
    "JOIN form_encounter AS fe ON " .
    "fe.pid = s.pid AND fe.encounter = s.encounter AND " .
    "fe.date >= '$from_date 00:00:00' AND fe.date <= '$to_date 23:59:59' " .
    //"fe.date >= ? AND fe.date <= ? " .
    "WHERE s.fee != 0";
    array_push($sqlBindArray,$from_date,$to_date);
  // If a facility was specified.
  if ($form_facility) {
    $query .= " AND fe.facility_id = ?";
     array_push($sqlBindArray,$form_facility);
  }
  if ($form_provider) {
    $query .= " AND fe.provider_id = ?";
    array_push($sqlBindArray,$form_provider);
  }
  $query .= " ORDER BY d.name, fe.date, fe.id";
  //
  return $query;
}

?>
