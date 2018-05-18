<?php
/*
 * These functions are common functions used in Alerts Log.
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
//

require_once("../globals.php");
require_once("../../library/patient.inc");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once("$srcdir/headers.inc.php");
require_once "$srcdir/clinical_rules.php";
require_once("../../library/report_functions.php");
require_once($GLOBALS['srcdir']."/formatting.inc.php");
$DateFormat = DateFormatRead(true);
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

/*
 * This function is responsible for showing the results of the search
 * @params: None
 * @return: None
 */
function showResults() {
	if ($_GET['search'] == 1) {

 	echo "<div id='report_results'>
			<table>
				<thead>
			    	<th align='center'>";
     					echo xlt('Date');
    		  echo "</th>

    				<th align='center'>";
			        	echo xlt('Patient ID');
			  echo "</th>

    				<th align='center'>";
     					echo xlt('User ID');
              echo "</th>

				    <th align='center'>";
				     	echo xlt('Category');
			  echo "</th>

				    <th align='center'>";
				    	echo xlt('All Alerts');
			  echo "</th>

				    <th align='center'>";
				    	echo xlt('New Alerts');
			  echo "</th>

   				</thead>
   				<tbody>";  //<!-- added for better print-ability -->
   	$res = listingCDRReminderLog($_POST['form_from_date'],$_POST['form_to_date']);

   	while ($row = sqlFetchArray($res)) {
	   	//Create category title
	    if ($row['category'] == 'clinical_reminder_widget') {
	    	$category_title = xl("Passive Alert");
	    }
	    else if ($row['category'] == 'active_reminder_popup') {
	    	$category_title = xl("Active Alert");
	    }
	    else if ($row['category'] == 'allergy_alert') {
	    	$category_title = xl("Allergy Warning");
	    }
	    else {
	    	$category_title = $row['category'];
	    }
	    //Prepare the targets
	    $all_alerts = json_decode($row['value'], true);
	    if (!empty($row['new_value'])) {
	    	$new_alerts = json_decode($row['new_value'], true);
	    }
	    echo "<tr>
	      <td>"; date($DateFormat, strtotime(text($row['date']))); echo "</td>
	      <td>"; echo text($row['pid']); echo "</td>
	      <td>"; echo text($row['uid']); echo "</td>
	      <td>"; echo text($category_title); echo "</td>
	      <td>";
	        //list off all targets with rule information shown when hover
	    foreach ($all_alerts as $targetInfo => $alert) {
		    if ( ($row['category'] == 'clinical_reminder_widget') || ($row['category'] == 'active_reminder_popup') ) {
		        $rule_title = getListItemTitle("clinical_rules",$alert['rule_id']);
		        $catAndTarget = explode(':',$targetInfo);
		        $category = $catAndTarget[0];
		        $target = $catAndTarget[1];
		        echo "<span title='" .attr($rule_title) . "'>" .
		           generate_display_field(array('data_type'=>'1','list_id'=>'rule_action_category'),$category) .
		            ": " . generate_display_field(array('data_type'=>'1','list_id'=>'rule_action'),$target) .
		            " (" . generate_display_field(array('data_type'=>'1','list_id'=>'rule_reminder_due_opt'),$alert['due_status']) . ")" .
		            "<span><br>";
		    } else { // $row['category'] == 'allergy_alert'
		        echo $alert . "<br>";
		    }
	    }
	    echo "</td>
	      <td>";
	    if (!empty($row['new_value'])) {
	        //list new targets with rule information shown when hover
	        foreach ($new_alerts as $targetInfo => $alert) {
	        	if ( ($row['category'] == 'clinical_reminder_widget') || ($row['category'] == 'active_reminder_popup') ) {
	          		$rule_title = getListItemTitle("clinical_rules",$alert['rule_id']);
	          		$catAndTarget = explode(':',$targetInfo);
	          		$category = $catAndTarget[0];
	          		$target = $catAndTarget[1];
	          		echo "<span title='" .attr($rule_title) . "'>" .
	               		generate_display_field(array('data_type'=>'1','list_id'=>'rule_action_category'),$category) .
	               		": " . generate_display_field(array('data_type'=>'1','list_id'=>'rule_action'),$target) .
	               		" (" . generate_display_field(array('data_type'=>'1','list_id'=>'rule_reminder_due_opt'),$alert['due_status']) . ")" .
	               		"<span><br>";
	        	} else { // $row['category'] == 'allergy_alert'
	        		echo $alert . "<br>";
	        	}
	        }
	    } else {
	        echo "&nbsp;";
	    }
	    echo "</td>
	    </tr>";
   	} // $row = sqlFetchArray($res) while
   echo "</tbody>
 </table>
 </div>"; // <!-- end of search results -->

 	} // end of if search button clicked
}

?>
