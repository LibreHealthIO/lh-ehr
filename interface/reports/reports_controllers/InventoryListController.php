<?php
/*
 * These functions are common functions used in Inventory Activity report.
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
//

 require_once("../globals.php");
 require_once("$srcdir/acl.inc");
 require_once("$srcdir/headers.inc.php");
 require_once("$srcdir/options.inc.php");
 require_once("$include_root/drugs/drugs.inc.php");

 // Check authorization.
 $thisauth = acl_check('admin', 'drugs');
 if (!$thisauth) die(xl('Not authorized'));

function addWarning($msg) {
  global $warnings;
  if ($warnings) $warnings .= '<br />';
  $warnings .= $msg;
}

// this is "" or "submit".
$form_action = $_POST['form_action'];

if (!empty($_POST['form_days'])) {
  $form_days = $_POST['form_days'] + 0;
}
else {
  $form_days = sprintf('%d', (strtotime(date('Y-m-d')) - strtotime(date('Y-01-01'))) / (60 * 60 * 24) + 1);
}

// get drugs
$res = sqlStatement("SELECT d.*, SUM(di.on_hand) AS on_hand " .
  "FROM drugs AS d " .
  "LEFT JOIN drug_inventory AS di ON di.drug_id = d.drug_id " .
  "AND di.on_hand != 0 AND di.destroy_date IS NULL " .
  "WHERE d.active = 1 " .
  "GROUP BY d.name, d.drug_id ORDER BY d.name, d.drug_id");

/* This prepares and displays results for Insurance List report
 * @params: None
 * @return: Echo HTML
 * @author: Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * */
function prepareAndShowResults() {
  global $form_days, $res, $encount;

  while ($row = sqlFetchArray($res)) {
    $on_hand = 0 + $row['on_hand'];
    $drug_id = 0 + $row['drug_id'];
    $warnings = '';

    $srow = sqlQuery("SELECT " .
      "SUM(quantity) AS sale_quantity " .
      "FROM drug_sales WHERE " .
      "drug_id = '$drug_id' AND " .
      "sale_date > DATE_SUB(NOW(), INTERVAL $form_days DAY) " .
      "AND pid != 0");

    ++$encount;
    $bgcolor = "#" . (($encount & 1) ? "ddddff" : "ffdddd");

    $sale_quantity = $srow['sale_quantity'];
    $months = $form_days / 30.5;

    $monthly = ($months && $sale_quantity) ?
      sprintf('%0.1f', $sale_quantity / $months) : '&nbsp;';

    $stock_months = '&nbsp;';
    if ($sale_quantity != 0) {
      $stock_months = sprintf('%0.1f', $on_hand * $months / $sale_quantity);
      if ($stock_months < 1.0) {
        addWarning(htmlspecialchars(xl('QOH is less than monthly usage')));
      }
    }

    // Check for reorder point reached.
    if (!empty($row['reorder_point']) && $on_hand <= $row['reorder_point']) {
      addWarning(htmlspecialchars(xl('Reorder point has been reached')));
    }

    // Compute the smallest quantity that might be taken from a lot based on the
    // past 30 days of sales.  If lot combining is allowed this is always 1.
    $min_sale = 1;
    if (!$row['allow_combining']) {
      $sminrow = sqlQuery("SELECT " .
        "MIN(quantity) AS min_sale " .
        "FROM drug_sales WHERE " .
        "drug_id = '$drug_id' AND " .
        "sale_date > DATE_SUB(NOW(), INTERVAL $form_days DAY) " .
        "AND pid != 0 " .
        "AND quantity > 0");
      $min_sale = 0 + $sminrow['min_sale'];
    }

    // Get all lots that we want to issue warnings about.  These are lots
    // expired, soon to expire, or with insufficient quantity for selling.
    $ires = sqlStatement("SELECT * " .
      "FROM drug_inventory WHERE " .
      "drug_id = '$drug_id' AND " .
      "on_hand > 0 AND " .
      "destroy_date IS NULL AND ( " .
      "on_hand < '$min_sale' OR " .
      "expiration IS NOT NULL AND expiration < DATE_ADD(NOW(), INTERVAL 30 DAY) " .
      ") ORDER BY lot_number");

    // Generate warnings associated with individual lots.
    while ($irow = sqlFetchArray($ires)) {
      $lotno = $irow['lot_number'];
      if ($irow['on_hand'] < $min_sale) {
        addWarning(htmlspecialchars(xl('Lot') . " '$lotno' " . xl('quantity seems unusable')));
      }
      if (!empty($irow['expiration'])) {
        $expdays = (int) ((strtotime($irow['expiration']) - time()) / (60 * 60 * 24));
        if ($expdays <= 0) {
          addWarning(htmlspecialchars(xl('Lot') . " '$lotno' " . xl('has expired')));
        }
        else if ($expdays <= 30) {
          addWarning(htmlspecialchars(xl('Lot') . " '$lotno' " . xl('expires in') . " $expdays " . xl('days')));
        }
      }
    }

    if (empty($row) && empty($monthly) &&
        empty($stock_months) && empty($warnings)) {
        echo "<p style='color:red; text-align:center;'>No results to display.</p>";
    } else {
        echo " <tr class='detail' bgcolor='$bgcolor'>\n";
          echo "  <td>" . htmlentities($row['name']) . "</td>\n";
          echo "  <td>" . htmlentities($row['ndc_number']) . "</td>\n";
          echo "  <td>" .
               generate_display_field(array('data_type'=>'1','list_id'=>'drug_form'), $row['form']) .
               "</td>\n";
          echo "  <td align='right'>" . $row['on_hand'] . "</td>\n";
          echo "  <td align='right'>" . $row['reorder_point'] . "</td>\n";
          echo "  <td align='right'>$monthly</td>\n";
          echo "  <td align='right'>$stock_months</td>\n";
          echo "  <td style='color:red'>$warnings</td>\n";
        echo " </tr>\n";
    }
  }
}

?>
