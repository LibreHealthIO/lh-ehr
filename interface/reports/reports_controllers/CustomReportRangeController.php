<?php 
/*
 * These functions are common functions used in Custome Report Range (Superbill) report. 
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

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/report.inc");
require_once("$srcdir/classes/Document.class.php");
require_once("$srcdir/classes/Note.class.php");
require_once("$srcdir/formatting.inc.php");
require_once("../../library/report_functions.php");
$DateFormat = DateFormatRead(true);
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

$from_date = $to_date = "";
if(empty($_POST['form_from_date']) || empty($_POST['form_to_date'])) {
    // set some default dates
    $from_date = date($DateFormat, (time() - 30*24*60*60));
    $to_date = date($DateFormat, time());
}
else {
    // set dates
    $from_date = $_POST['form_from_date'];
    $to_date = $_POST['form_to_date'];
}
//Patient related stuff
if ($_POST["form_patient"])
    $form_patient = isset($_POST['form_patient']) ? $_POST['form_patient'] : '';
$form_pid = isset($_POST['form_pid']) ? $_POST['form_pid'] : '';
if ($form_patient == '' ) $form_pid = '';

/*function postToGet($patient_encounter, $pids) {
    $getstring="";
    $serialpatient_encounter = serialize($patient_encounter);
    $serialpids = serialize($pids);
    $getstring = "patient_encounter=".urlencode($serialpatient_encounter)."&pids=".urlencode($serialpids);

    return $getstring;
} */

function showResults(){
	//global $from_date, $to_date, $form_pid, $form_patient;
	if( !(empty($_POST['form_from_date']) || empty($_POST['form_to_date']))) {
	    $sql = "select * from facility where billing_location = 1";
	    $db = $GLOBALS['adodb']['db'];
	    $results = $db->Execute($sql);
	    $facility = array();

	    if (!$results->EOF) {
	        $facility = $results->fields;
			echo '<p>
				<h2>'; text($facility['name']); echo '</h2>';
			 text($facility['street']); echo '<br>';
			 text($facility['city']); echo ','; text($facility['state']); text($facility['postal_code']); echo '<br>
			</p>';
	    }

	    $sqlBindArray = array();
	    $res_query = "select * from forms where " .
	                 "form_name = 'Patient Encounter' and " .
	                 "date between ? and ? " ;
	    array_push($sqlBindArray, prepareDateBeforeSave($from_date), prepareDateBeforeSave($to_date));

	    if($form_pid) {
	        $res_query.= " and pid=? "; 
	        array_push($sqlBindArray,$form_pid);
	    }

	    $res_query.= " order by date DESC" ;
	    $res =sqlStatement($res_query,$sqlBindArray);
	    
	    while($result = sqlFetchArray($res)) {
	        if ($result{"form_name"} == "Patient Encounter") {
	        	$patient_encounter[] = $result{"form_id"}.":".$result{"encounter"};
	            $pids[] = $result{"pid"};   
	        }
	    }

	    $N = 6;

	    $iCounter = 0;
	    if(empty($patient_encounter)){ 
	    	$patient_encounter = array();
	    	echo "<div class='text'><p style='color:red; text-align:center; margin-top: -40px'>No Results to display. Enter details and try again.</p></div>";
	    }
	    foreach($patient_encounter as $patient){
	        /*
	        $inclookupres = sqlStatement("select distinct formdir from forms where pid='".$pids[$iCounter]."'");
	        while($result = sqlFetchArray($inclookupres)) {
	            include_once("{$GLOBALS['incdir']}/forms/" . $result{"formdir"} . "/report.php");
	        }
	        */

	        print "<div id='superbill_patientdata'>";
	        print "<h1>".xlt('Patient Data').":</h1>";
	        printRecDataOne($patient_data_array, getRecPatientData ($pids[$iCounter]), $N);
	        print "</div>";

	        print "<div id='superbill_insurancedata'>";
	        print "<h1>".xlt('Insurance Data').":</h1>";
	        print "<h2>".xlt('Primary').":</h2>";
	        printRecDataOne($insurance_data_array, getRecInsuranceData ($pids[$iCounter],"primary"), $N);
	        print "<h2>".xlt('Secondary').":</h2>";
	        printRecDataOne($insurance_data_array, getRecInsuranceData ($pids[$iCounter],"secondary"), $N);
	        print "<h2>".xlt('Tertiary').":</h2>";
	        printRecDataOne($insurance_data_array, getRecInsuranceData ($pids[$iCounter],"tertiary"), $N);
	        print "</div>";

	        print "<div id='superbill_billingdata'>";
	        print "<h1>".xlt('Billing Information').":</h1>";
	        if (count($patient) > 0) {
	            $billings = array();
	            echo "<table width='100%'>";
	            echo "<tr>";
	            echo "<td class='bold' width='10%'>".xlt('Date')."</td>";
	            echo "<td class='bold' width='20%'>".xlt('Provider')."</td>";
	            echo "<td class='bold' width='40%'>".xlt('Code')."</td>";
	            echo "<td class='bold' width='10%'>".xlt('Fee')."</td></tr>\n";
	            $total = 0.00;
	            $copays = 0.00;
	            //foreach ($patient as $be) {

	                $ta = explode(":",$patient);
	                $billing = getPatientBillingEncounter($pids[$iCounter],$ta[1]);

	                $billings[] = $billing;
	                foreach ($billing as $b) {
	                    // grab the date to reformat it in the output
	                    $bdate = strtotime($b['date']);

	                    echo "<tr>\n";
	                    echo "<td class='text' style='font-size: 0.8em'>" . oeFormatShortDate(date("Y-m-d",$bdate)) . "<BR>" . date("h:i a", $bdate) . "</td>";
	                    echo "<td class='text'>" . text($b['provider_name']) . "</td>";
	                    echo "<td class='text'>";
	                    echo text($b['code_type']) . ":\t" . text($b['code']) . "&nbsp;". text($b['modifier']) . "&nbsp;&nbsp;&nbsp;" . text($b['code_text']) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	                    echo "</td>\n";
	                    echo "<td class='text'>";
	                    echo oeFormatMoney($b['fee']);
	                    echo "</td>\n";
	                    echo "</tr>\n";
	                    $total += $b['fee'];
	                }
	            // Calculate the copay for the encounter
	            $copays = getPatientCopay($pids[$iCounter],$ta[1]);
	            //}
	            echo "<tr><td>&nbsp;</td></tr>";
	            echo "<tr><td class='bold' colspan=3 style='text-align:right'>".xlt('Sub-Total')."</td><td class='text'>" . oeFormatMoney($total + abs($copays)) . "</td></tr>";
	            echo "<tr><td class='bold' colspan=3 style='text-align:right'>".xlt('Copay Paid')."</td><td class='text'>" . oeFormatMoney(abs($copays)) . "</td></tr>";
	            echo "<tr><td class='bold' colspan=3 style='text-align:right'>".xlt('Total')."</td><td class='text'>" . oeFormatMoney($total) . "</td></tr>";
	            echo "</table>";
	            echo "<pre>";
	            //print_r($billings);
	            echo "</pre>";
	        }
	        echo "</div>";

	        ++$iCounter;
	        print "<br/><br/>".xlt('Physician Signature').":  _______________________________________________";
	        print "<hr class='pagebreak' />";
	    }
	}
}

?>
