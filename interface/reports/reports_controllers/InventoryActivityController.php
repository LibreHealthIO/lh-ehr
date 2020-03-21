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

// Report columns:
// Product Name (blank where repeated)
// Warehouse Name (blank where repeated) or Total for Product
// Starting Inventory (detail lines: date)
// Ending Inventory   (detail lines: invoice ID)
// Sales
// Distributions
// Purchases
// Transfers

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

// Specify if product or warehouse is the first column.
$product_first = (!empty($_POST['form_by']) && $_POST['form_by'] == 'w') ? 0 : 1;

$last_warehouse_id = '~';
$last_product_id = 0;

function esc4Export($str) {
  return str_replace('"', '\\"', $str);
}

// Get ending inventory for the report's end date.
// Optionally restricts by product ID and/or warehouse ID.
function getEndInventory($product_id = 0, $warehouse_id = '~') {
  	global $from_date, $to_date, $form_product;

  	$whidcond = '';
  	if ($warehouse_id !== '~') {
    	$whidcond = $warehouse_id === '' ?
      		"AND ( di.warehouse_id IS NULL OR di.warehouse_id = '' )" :
      		"AND di.warehouse_id = '$warehouse_id'";
  	}

  	$prodcond = '';
  	if ($form_product) $product_id = $form_product;
  	if ($product_id) {
    	$prodcond = "AND di.drug_id = '$product_id'";
  	}

  	// Get sum of current inventory quantities + destructions done after the
  	// report end date (which is effectively a type of transaction).
  	$eirow = sqlQuery("SELECT sum(di.on_hand) AS on_hand " .
    	"FROM drug_inventory AS di WHERE " .
   		"( di.destroy_date IS NULL OR di.destroy_date > '$to_date' ) " .
    	"$prodcond $whidcond");

  	// Get sum of sales/adjustments/purchases after the report end date.
  	$sarow = sqlQuery("SELECT sum(ds.quantity) AS quantity " .
    	"FROM drug_sales AS ds, drug_inventory AS di WHERE " .
    	"ds.sale_date > '$to_date' AND " .
    	"di.inventory_id = ds.inventory_id " .
    	"$prodcond $whidcond");

  	// Get sum of transfers out after the report end date.
  	$xfrow = sqlQuery("SELECT sum(ds.quantity) AS quantity " .
    	"FROM drug_sales AS ds, drug_inventory AS di WHERE " .
    	"ds.sale_date > '$to_date' AND " .
    	"di.inventory_id = ds.xfer_inventory_id " .
    	"$prodcond $whidcond");

  	return $eirow['on_hand'] + $sarow['quantity'] - $xfrow['quantity'];
}

function thisLineItem($product_id, $warehouse_id, $patient_id, $encounter_id,
  					$rowprod, $rowwh, $transdate, $qtys, $irnumber='') {
  	global $warehouse, $product, $secqtys, $priqtys, $grandqtys;
  	global $whleft, $prodleft; // left 2 columns, blank where repeated
  	global $last_warehouse_id, $last_product_id, $product_first;
  	global $form_action;

  	$invnumber = empty($irnumber) ? ($patient_id ? "$patient_id.$encounter_id" : "") : $irnumber;

  	// Product name for this detail line item.
  	if (empty($rowprod)) $rowprod = 'Unnamed Product';

  	// Warehouse name for this line item.
  	if (empty($rowwh)) $rowwh = 'None';

  	// If new warehouse or product...
  	if ($warehouse_id != $last_warehouse_id || $product_id != $last_product_id) {

	    // If there was anything to total...
	    if (($product_first && $last_warehouse_id != '~') || (!$product_first && $last_product_id)) {

		    $secei = getEndInventory($last_product_id, $last_warehouse_id);

		    // Print second-column totals.
		    if ($form_action == 'export') {
		        // Export:
		        if (! $_POST['form_details']) {
		          	if ($product_first) {
		            	echo '"'  . esc4Export($product)   . '"';
		            	echo ',"' . esc4Export($warehouse) . '"';
		          	} else {
		            	echo '"'  . esc4Export($warehouse) . '"';
		            	echo ',"' . esc4Export($product)   . '"';
		          	}
		          	echo ',"' . ($secei - $secqtys[0] - $secqtys[1] - $secqtys[2] - $secqtys[3] - $secqtys[4]) . '"'; // start inventory
		          	echo ',"' . $secqtys[0] . '"'; // sales
		          	echo ',"' . $secqtys[1] . '"'; // distributions
		          	echo ',"' . $secqtys[2] . '"'; // purchases
		          	echo ',"' . $secqtys[3] . '"'; // transfers
		          	echo ',"' . $secqtys[4] . '"'; // adjustments
		          	echo ',"' . $secei      . '"'; // end inventory
		          	echo "\n";
		        }
		    } else {
		        // Not export:
				echo '<tr bgcolor="#ddddff">';
					if ($product_first) {
						echo '<td class="detail">';
						   echo htmlspecialchars($prodleft); $prodleft = " ";
						echo '</td>';
						echo '<td class="detail" colspan="3">';
						   if ($_POST['form_details']) echo htmlspecialchars(xl('Total for')) . ' '; echo htmlspecialchars($warehouse);
						echo '</td>';
					} else {
						echo '<td class="detail">';
						   echo htmlspecialchars($whleft); $whleft = " ";
						echo '</td>';
						echo '<td class="detail" colspan="3">';
						   if ($_POST['form_details']) echo htmlspecialchars(xl('Total for')) . ' '; echo htmlspecialchars($product);
						echo '</td>';
					}
					echo '<td class="dehead" align="right">';
					   echo $secei - $secqtys[0] - $secqtys[1] - $secqtys[2] - $secqtys[3] - $secqtys[4];
					echo '</td>';
					echo '<td class="dehead" align="right">';
					   echo $secqtys[0];
					echo '</td>';
					echo '<td class="dehead" align="right">';
					   echo $secqtys[1];
					echo '</td>';
					echo '<td class="dehead" align="right">';
					   echo $secqtys[2];
					echo '</td>';
					echo '<td class="dehead" align="right">';
					   echo $secqtys[3];
					echo '</td>';
					echo '<td class="dehead" align="right">';
					   echo $secqtys[4];
					echo '</td>';
					echo '<td class="dehead" align="right">';
					   echo $secei;
					echo '</td>';
				echo '</tr>';
		    } // End not csv export
		}
		$secqtys = array(0, 0, 0, 0, 0);
		if ($product_first ) {
		    $whleft = $warehouse = $rowwh;
		    $last_warehouse_id = $warehouse_id;
		} else {
		    $prodleft = $product = $rowprod;
		    $last_product_id = $product_id;
		}
  	}

  	// If first column is changing, time for its totals.
  	if (($product_first && $product_id != $last_product_id) ||
       (!$product_first && $warehouse_id != $last_warehouse_id)) {

	    if (($product_first && $last_product_id) ||
	        (!$product_first && $last_warehouse_id != '~')) {
	      	$priei = $product_first ? getEndInventory($last_product_id) :
	        	getEndInventory(0, $last_warehouse_id);

	      	// Print first column total.
	      	if ($form_action != 'export') {
			 	echo '<tr bgcolor="#ffdddd">';
				  	echo '<td class="detail">&nbsp;</td>';
				  	echo '<td class="detail" colspan="3">';
				   		echo htmlspecialchars(xl('Total for')) . ' '; echo htmlspecialchars($product_first ? $product : $warehouse);
				  	echo '</td>';
				  	echo '<td class="dehead" align="right">';
				   		echo $priei - $priqtys[0] - $priqtys[1] - $priqtys[2] - $priqtys[3] - $priqtys[4];
				  	echo '</td>';
				  	echo '<td class="dehead" align="right">';
				   		echo $priqtys[0];
				  	echo '</td>';
				  	echo '<td class="dehead" align="right">';
				  		echo $priqtys[1];
				  	echo '</td>';
				  	echo '<td class="dehead" align="right">';
				   		echo $priqtys[2];
				  	echo '</td>';
				  	echo '<td class="dehead" align="right">';
				   		echo $priqtys[3];
				  	echo '</td>';
				  	echo '<td class="dehead" align="right">';
				   		echo $priqtys[4];
				  	echo '</td>';
				  	echo '<td class="dehead" align="right">';
				   		echo $priei;
				  	echo '</td>';
			 	echo '</tr>';
	      	} // End not csv export
	    }
	    $priqtys = array(0, 0, 0, 0, 0);
	    if ($product_first) {
	      	$prodleft = $product = $rowprod;
	      	$last_product_id = $product_id;
	    } else {
	      	$whleft = $warehouse = $rowwh;
	      	$last_warehouse_id = $warehouse_id;
	    }
  	}

  	// Detail line.
  	if ($_POST['form_details'] && $product_id && ($qtys[0] + $qtys[1] + $qtys[2] + $qtys[3] + $qtys[4])) {
	    if ($form_action == 'export') {
	      	if ($product_first) {
	        	echo '"'  . esc4Export($product )  . '"';
	        	echo ',"' . esc4Export($warehouse) . '"';
	      	} else {
	        	echo '"'  . esc4Export($warehouse) . '"';
	        	echo ',"' . esc4Export($product)   . '"';
	      	}
	      	echo ',"' . oeFormatShortDate($transdate) . '"';
	      	echo ',"' . esc4Export($invnumber) . '"';
	      	echo ',"' . $qtys[0]             . '"'; // sales
	      	echo ',"' . $qtys[1]             . '"'; // distributions
	      	echo ',"' . $qtys[2]             . '"'; // purchases
	      	echo ',"' . $qtys[3]             . '"'; // transfers
	      	echo ',"' . $qtys[4]             . '"'; // adjustments
	      	echo "\n";
	    } else {
		 	echo '<tr>';
				if ($product_first) {
			  		echo '<td class="detail">';
			   			echo htmlspecialchars($prodleft); $prodleft = " ";
			  		echo '</td>';
			  		echo '<td class="detail">';
			   			echo htmlspecialchars($whleft); $whleft = " ";
			 		echo '</td>';
				} else {
				  	echo '<td class="detail">';
				   		echo htmlspecialchars($whleft); $whleft = " ";
				  	echo '</td>';
				  	echo '<td class="detail">';
				  		echo htmlspecialchars($prodleft); $prodleft = " ";
				  	echo '</td>';
				}
			  	echo '<td class="dehead">';
			   		echo oeFormatShortDate($transdate);
			  	echo '</td>';
			  	echo '<td class="detail">';
			   		echo htmlspecialchars($invnumber);
			  	echo '</td>';
			  	echo '<td class="detail">&nbsp;</td>';
			  	echo '<td class="dehead" align="right">';
			   		echo $qtys[0];
			  	echo '</td>';
			  	echo '<td class="dehead" align="right">';
			   		echo $qtys[1];
			  	echo '</td>';
			  	echo '<td class="dehead" align="right">';
			   		echo $qtys[2];
			  	echo '</td>';
			  	echo '<td class="dehead" align="right">';
			   		echo $qtys[3];
			  	echo '</td>';
			  	echo '<td class="dehead" align="right">';
			   		echo $qtys[4];
			  	echo '</td>';
			  	echo '<td class="detail">&nbsp;</td>';
		 	echo '</tr>';
		} // End not csv export
  	} // end details
  	for ($i = 0; $i < 5; ++$i) {
    	$secqtys[$i]   += $qtys[$i];
    	$priqtys[$i]   += $qtys[$i];
    	$grandqtys[$i] += $qtys[$i];
  	}
} // end function

if (! acl_check('acct', 'rep')) die(htmlspecialchars(xl("Unauthorized access.")));

// this is "" or "submit" or "export".
$form_action = $_POST['form_action'];
$from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
$form_product  = $_POST['form_product'];

/* This prepares list of products for Insurance activity report
 * @params: None
 * @return: Echo HTML
 * @author: Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * */
function dropDownProducts() {
	//
	$query = "SELECT drug_id, name FROM drugs ORDER BY name, drug_id";
	$pres = sqlStatement($query);
	echo "      <select name='form_product'>\n";
	echo "       <option value=''>-- " . htmlspecialchars(xl('All Products')) . " --\n";
	while ($prow = sqlFetchArray($pres)) {
  		$drug_id = $prow['drug_id'];
  		echo "       <option value='$drug_id'";
  		if ($drug_id == $form_product) echo " selected";
  		echo ">" . htmlspecialchars($prow['name']) . "\n";
	}
	echo "      </select>\n";
}

/* This prepares and displays results for Insurance activity report
 * @params: None
 * @return: Echo HTML
 * @author: Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * */
function prepareAndShowResults() {
	//global $from_date, $to_date;
	//global $form_product, $form_action, $product_first;

	$from_date = $from_date;
  	$to_date   = $to_date;

  	$product   = "";
  	$prodleft  = "";
  	$warehouse = "";
  	$whleft    = "";
  	$grandqtys = array(0, 0, 0, 0, 0);
  	$priqtys   = array(0, 0, 0, 0, 0);
  	$secqtys   = array(0, 0, 0, 0, 0);
  	$last_inventory_id = 0;

  	$query = "SELECT s.sale_id, s.sale_date, s.quantity, s.fee, s.pid, s.encounter, " .
    	"s.xfer_inventory_id, s.distributor_id, d.name, lo.title, " .
    	"di.drug_id, di.warehouse_id, di.inventory_id, di.destroy_date, di.on_hand, " .
    	"fe.invoice_refno " .
    	"FROM drug_inventory AS di " .
    	"JOIN drugs AS d ON d.drug_id = di.drug_id " .
   	 	"LEFT JOIN drug_sales AS s ON " .
   	 	"s.sale_date >= '$from_date' AND s.sale_date <= '$to_date' AND " .
    	"s.drug_id = di.drug_id AND " .
    	"( s.inventory_id = di.inventory_id OR s.xfer_inventory_id = di.inventory_id ) " .
    	"LEFT JOIN list_options AS lo ON lo.list_id = 'warehouse' AND " .
    	"lo.option_id = di.warehouse_id " .
    	"LEFT JOIN form_encounter AS fe ON fe.pid = s.pid AND fe.encounter = s.encounter " .
    	"WHERE ( di.destroy_date IS NULL OR di.destroy_date >= '$from_date' )";

  	// If a product was specified.
  	if ($form_product) {
    	$query .= " AND di.drug_id = '$form_product'";
  	}

  	if ($product_first) {
   	 	$query .= " ORDER BY d.name, d.drug_id, lo.title, di.warehouse_id, " .
      		"di.inventory_id, s.sale_date, s.sale_id";
  	} else {
    	$query .= " ORDER BY lo.title, di.warehouse_id, d.name, d.drug_id, " .
      		"di.inventory_id, s.sale_date, s.sale_id";
  	}

  	$res = sqlStatement($query);
  	while ($row = sqlFetchArray($res)) {
		// If new lot and it was destroyed during the reporting period,
    	// generate a pseudo-adjustment for that.
    	if ($row['inventory_id'] != $last_inventory_id) {
      		$last_inventory_id = $row['inventory_id'];
      		if (!empty($row['destroy_date']) && $row['on_hand'] != 0
        		&& $row['destroy_date'] <= $to_date) {
        		thisLineItem($row['drug_id'], $row['warehouse_id'], 0,
          		0, $row['name'], $row['title'], $row['destroy_date'],
          		array(0, 0, 0, 0, 0 - $row['on_hand']),
          		xl('Destroyed'));
      		}
    	}

	    $qtys = array(0, 0, 0, 0, 0);
	    if ($row['sale_id']) {
	      	if ($row['xfer_inventory_id']) {
	        	// A transfer sale item will appear twice, once with each lot.
	        	if ($row['inventory_id'] == $row['xfer_inventory_id'])
	          		$qtys[3] = $row['quantity'];
	        	else
	          		$qtys[3] = 0 - $row['quantity'];
	      	} else if ($row['pid'])
	        	$qtys[0] = 0 - $row['quantity'];
	      	else if ($row['distributor_id'])
	        	$qtys[1] = 0 - $row['quantity'];
	      	else if ($row['fee'] != 0)
	        	$qtys[2] = 0 - $row['quantity'];
	      	else // no pid, distributor, source lot or fee: must be an adjustment
	        	$qtys[4] = 0 - $row['quantity'];
	    }
	    thisLineItem($row['drug_id'], $row['warehouse_id'], $row['pid'] + 0,
	      	$row['encounter'] + 0, $row['name'], $row['title'], $row['sale_date'],
	      	$qtys, $row['invoice_refno']);
  	}

  	// Generate totals for last product and warehouse.
  	thisLineItem(0, '~', 0, 0, '', '', '0000-00-00', array(0, 0, 0, 0, 0));

  	// Grand totals line.
  	if ($form_action != 'export') { // if submit
	    $grei = getEndInventory();
		if (empty($grei)) {
			echo "<p style='color:red; text-align:center;'>No results to display.</p>";
		} else {
			echo '<tr bgcolor="#dddddd">';
		  	echo '<td class="detail" colspan="4">';
		   		echo htmlspecialchars(xl('Grand Total'));
		  	echo '</td>';
		  	echo '<td class="dehead" align="right">';
		   		echo $grei - $grandqtys[0] - $grandqtys[1] - $grandqtys[2] - $grandqtys[3] - $grandqtys[4];
		  	echo '</td>';
		  	echo '<td class="dehead" align="right">';
		   		echo $grandqtys[0];
		  	echo '</td>';
		  	echo '<td class="dehead" align="right">';
		  		echo $grandqtys[1];
		  	echo '</td>';
		  	echo '<td class="dehead" align="right">';
		   		echo $grandqtys[2];
		  	echo '</td>';
		 	echo '<td class="dehead" align="right">';
		    	echo $grandqtys[3];
		  	echo '</td>';
		  	echo '<td class="dehead" align="right">';
		   		echo $grandqtys[4];
		  	echo '</td>';
		  	echo '<td class="dehead" align="right">';
		   		echo $grei;
		  	echo '</td>';
		echo '</tr>';
		}

  	} // End if submit
}

?>
