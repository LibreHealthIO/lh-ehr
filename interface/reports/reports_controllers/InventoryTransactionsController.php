<?php
/*
 * These functions are common functions used in Inventory Transactions report.
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
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("../../library/report_functions.php");
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

function esc4Export($str) {
  return str_replace('"', '\\"', $str);
}

function thisLineItem($row, $xfer=false) {
  	global $grandtotal, $grandqty, $encount, $form_action;

  	$invnumber = '';
  	$dpname = '';

  	if (!empty($row['pid'])) {
	    $ttype = xl('Sale');
	    $dpname = $row['plname'];
	    if (!empty($row['pfname'])) {
	      	$dpname .= ', ' . $row['pfname'];
	      	if (!empty($row['pmname'])) $dpname .= ' ' . $row['pmname'];
	    }
	    $invnumber = empty($row['invoice_refno']) ?
	      "{$row['pid']}.{$row['encounter']}" : $row['invoice_refno'];
  	} else if (!empty($row['distributor_id'])) {
	    $ttype = xl('Distribution');
	    if (!empty($row['organization'])) {
	      	$dpname = $row['organization'];
	    } else {
	      	$dpname = $row['dlname'];
	      	if (!empty($row['dfname'])) {
	        	$dpname .= ', ' . $row['dfname'];
	        	if (!empty($row['dmname'])) $dpname .= ' ' . $row['dmname'];
	      	}
	    }
  	} else if (!empty($row['xfer_inventory_id']) || $xfer) {
    	$ttype = xl('Transfer');
  	} else if ($row['fee'] != 0) {
    	$ttype = xl('Purchase');
  	} else {
  	  	$ttype = xl('Adjustment');
  	}

  	if ($form_action == 'export') {
	    echo '"' . oeFormatShortDate($row['sale_date']) . '",';
	    echo '"' . $ttype                               . '",';
	    echo '"' . esc4Export($row['name'])             . '",';
	    echo '"' . esc4Export($row['lot_number'])       . '",';
	    echo '"' . esc4Export($row['warehouse'])        . '",';
	    echo '"' . esc4Export($dpname)                  . '",';
	    echo '"' . (0 - $row['quantity'])               . '",';
	    echo '"' . bucks($row['fee'])                   . '",';
	    echo '"' . $row['billed']                       . '",';
	    echo '"' . esc4Export($row['notes'])            . '"' . "\n";
  	} else {
    	$bgcolor = (++$encount & 1) ? "#ddddff" : "#ffdddd";
		echo '<tr bgcolor="';echo $bgcolor; echo '">';
		  	echo '<td class="detail">';
		   		echo htmlspecialchars(oeFormatShortDate($row['sale_date']));
		  	echo '</td>';
		  	echo '<td class="detail">';
		   		echo htmlspecialchars($ttype);
		  	echo '</td>';
		  	echo '<td class="detail">';
		   		 echo htmlspecialchars($row['name']);
		  	echo '</td>';
		 	echo '<td class="detail">';
		   		echo htmlspecialchars($row['lot_number']);
		  	echo '</td>';
		  	echo '<td class="detail">';
		   		 echo htmlspecialchars($row['warehouse']);
		  	echo '</td>';
		  	echo '<td class="detail">';
		   		echo htmlspecialchars($dpname);
		  	echo '</td>';
		  	echo '<td class="detail" align="right">';
		   		echo htmlspecialchars(0 - $row['quantity']);
		  	echo '</td>';
		  	echo '<td class="detail" align="right">';
		   		 echo htmlspecialchars(bucks($row['fee']));
		  	echo '</td>';
		  	echo '<td class="detail" align="center">';
		   		echo empty($row['billed']) ? '&nbsp;' : '*';
		  	echo '</td>';
		  	echo '<td class="detail">';
		   		echo htmlspecialchars($row['notes']);
		  	echo '</td>';
		echo '</tr>';
  	} // End not csv export

  	$grandtotal   += $row['fee'];
  	$grandqty     -= $row['quantity'];

  	// In the special case of a transfer, generate a second line item for
  	// the source lot.
  	if (!empty($row['xfer_inventory_id'])) {
    	$row['xfer_inventory_id'] = 0;
    	$row['lot_number'] = $row['lot_number_2'];
    	$row['warehouse'] = $row['warehouse_2'];
    	$row['quantity'] = 0 - $row['quantity'];
    	$row['fee'] = 0 - $row['fee'];
    	thisLineItem($row, true);
  	}

} // end function

if (! acl_check('acct', 'rep')) die(htmlspecialchars(xl("Unauthorized access."), ENT_NOQUOTES));

// this is "" or "submit" or "export".
$form_action = $_POST['form_action'];

$from_date  = fixDate($_POST['form_from_date'], date('Y-m-d'));
$to_date    = fixDate($_POST['form_to_date']  , date('Y-m-d'));
$form_trans_type = isset($_POST['form_trans_type']) ? $_POST['form_trans_type'] : '0';

$encount = 0;

/* This prepares and displays results for Insurance Transactions report
 * @params: None
 * @return: Echo HTML
 * @author: Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * */
function prepareAndShowResults() {
	$from_date = $from_date;
  	$to_date   = $to_date;

  	$grandtotal = 0;
  	$grandqty = 0;

  	$query = "SELECT s.sale_date, s.fee, s.quantity, s.pid, s.encounter, " .
    	"s.billed, s.notes, s.distributor_id, s.xfer_inventory_id, " .
    	"p.fname AS pfname, p.mname AS pmname, p.lname AS plname, " .
    	"u.fname AS dfname, u.mname AS dmname, u.lname AS dlname, u.organization, " .
    	"d.name, fe.date, fe.invoice_refno, " .
    	"i1.lot_number, i2.lot_number AS lot_number_2, " .
    	"lo1.title AS warehouse, lo2.title AS warehouse_2 " .
	    "FROM drug_sales AS s " .
	    "JOIN drugs AS d ON d.drug_id = s.drug_id " .
	    "LEFT JOIN drug_inventory AS i1 ON i1.inventory_id = s.inventory_id " .
	    "LEFT JOIN drug_inventory AS i2 ON i2.inventory_id = s.xfer_inventory_id " .
	    "LEFT JOIN patient_data AS p ON p.pid = s.pid " .
	    "LEFT JOIN users AS u ON u.id = s.distributor_id " .
	    "LEFT JOIN list_options AS lo1 ON lo1.list_id = 'warehouse' AND " .
	    "lo1.option_id = i1.warehouse_id " .
	    "LEFT JOIN list_options AS lo2 ON lo2.list_id = 'warehouse' AND " .
	    "lo2.option_id = i2.warehouse_id " .
	    "LEFT JOIN form_encounter AS fe ON fe.pid = s.pid AND fe.encounter = s.encounter " .
	    "WHERE s.sale_date >= ? AND s.sale_date <= ? ";

  	if ($form_trans_type == 2) { // purchase/return
    	$query .= "AND s.pid = 0 AND s.distributor_id = 0 AND s.xfer_inventory_id = 0 AND s.fee != 0 ";
  	} else if ($form_trans_type == 4) { // transfer
    	$query .= "AND s.xfer_inventory_id != 0 ";
  	} else if ($form_trans_type == 5) { // adjustment
    	$query .= "AND s.pid = 0 AND s.distributor_id = 0 AND s.xfer_inventory_id = 0 AND s.fee = 0 ";
  	} else if ($form_trans_type == 6) { // distribution
    	$query .= "AND s.distributor_id != 0 ";
  	} else if ($form_trans_type == 1) { // sale
    	$query .= "AND s.pid != 0 ";
  	}

  	$query .= "ORDER BY s.sale_date, s.sale_id";
  	//
  	$res = sqlStatement($query, array($from_date, $to_date));
  	while ($row = sqlFetchArray($res)) {
    	thisLineItem($row);
  	}

  	// Grand totals line.
  	if ($form_action != 'export') { // if submit
		echo '<tr bgcolor="#dddddd">';
		  	echo '<td class="dehead" colspan="6">';
		   		echo htmlspecialchars(xl('Grand Total'), ENT_NOQUOTES);
		  	echo '</td>';
		  	echo '<td class="dehead" align="right">';
		   		echo htmlspecialchars($grandqty, ENT_NOQUOTES);
		  	echo '</td>';
		  	echo '<td class="dehead" align="right">';
		   		echo htmlspecialchars(bucks($grandtotal), ENT_NOQUOTES);
		  	echo '</td>';
		  	echo '<td class="dehead" colspan="2"></td>';
		echo '</tr>';
  	} // End if submit
}

?>
