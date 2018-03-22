<?php
/*
 * These functions are common functions used in the Adjustments reports. They have pulled out 
 * and placed in this file. This is done to prepare the for building a
 * report generator.
 *
 * Copyright (C) 2018 Tigpezeghe Rodrige <tigrodrige@gmail.com>
 * Copyright (C) 2015-2017 Terry Hill <teryhill@librehealth.io> 
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

 require_once("../globals.php");
 require_once("../../library/report_functions.php");
 require_once "$srcdir/patient.inc";
 require_once "$srcdir/acl.inc";
 require_once "$srcdir/options.inc.php";
 require_once "$srcdir/formatting.inc.php";
 $DateFormat = DateFormatRead();
 $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

 $from_date = fixDate($_POST['form_from_date']);
 $to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));

/*
 * This function is for csv export of adjustments
 * @params None
 * @return void - Simply echo HTML encoded string
 */

function csvexport() {
	header("Pragma: public");
	header("Expires: 0");
  	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  	header("Content-Type: application/force-download");
  	header("Content-Disposition: attachment; filename=adjustment_codes.csv");
  	header("Content-Description: File Transfer");
  	// CSV headers:
  	if (true) {
    	echo '"Adjustment Code",';
    	echo '"Total",'. "\n";
  	}
}

/*
 * This function just displays the report daterange
 * @params None
 * @return void - Simply echo HTML encoded string
 */
function report_parameters_daterange() {
	echo '<div id="report_parameters_daterange">';
    		date("d F Y", strtotime(oeFormatDateForPrintReport($_POST['form_from_date'])))
    	. " &nbsp; to &nbsp; ". date("d F Y", strtotime(oeFormatDateForPrintReport($_POST['form_to_date'])));
	echo '</div>';
}

/*
 * This function is responsible for checking this condition
 * if($_POST['form_refresh'] || $_POST['form_csvexport']) 
 */
function formRefreshOrCsvexport() {
	$adj_reason = $_POST['form_adjreason'];
  	$from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
  	$to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));

  	if ($adj_reason =='') 
  	{
    	$query = " Select   ar_activity.memo, Sum(ar_activity.adj_amount) As adj_amount " .
   		" From ar_activity" .
    	" Where ar_activity.post_time >= '$from_date' ".
    	" And ar_activity.post_time <= '$to_date' ".
    	" Group By ar_activity.memo ";
  	} else {
    	$query = " Select   ar_activity.memo, Sum(ar_activity.adj_amount) As adj_amount " .
  		" From ar_activity" .
  		" Where ar_activity.post_time >= '$from_date' ".
  		" And ar_activity.post_time <= '$to_date' ".
  		" And ar_activity.memo = '$adj_reason' " .
  		" Group By ar_activity.memo ";
  	}

  	$res = sqlStatement($query);
  	while ($row = sqlFetchArray($res))
  	{
	    $adj_name = $row['memo'];
	    $total = $row['adj_amount'];

	    if ($_POST['form_csvexport'])
	    {
	        echo '"' . $adj_name . '",';
	        echo '"' . oeFormatMoney($total) . '"' . "\n";
	    } else {
		 	echo '<tr>
		  			<td align="left">';
		   	echo $adj_name . '
		  			</td>
		  			<td align="right">';
		   				echo oeFormatMoney($total);
		 	  echo '</td>
		 		  </tr>';

        } // end not export
  	}
}