<?php
/*
 * Copyright (C) 2015 - 2017      Suncoast Connection
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */


require_once("../../interface/globals.php");
include_once("../../library/sql.inc");


function AddCPT2CodeBilling($pid,$date,$ourCode,$encounter,$userID)
{


	if ( $ourCode != "" ) {
		$codesplit=explode(":",$ourCode);
                $codeBase=$codesplit[0];
		if ( count($codesplit) > 1 ){
	                $codeModifier=$codesplit[1];
		} else {
			$codeModifier="";
		}

		$query=
		"INSERT INTO `billing` ".
		" ( `date`, `code_type`, `code`, `pid`, ".
		" `provider_id`, `user`, `groupname`, `authorized`, ".
		" `encounter`, `billed`, `activity`, ".
		" `payer_id`, `bill_process`, `modifier`) ".
		" VALUES ".
		" ('".$date."','CPT2','".$codeBase."','".$pid."', ".
		" '".$userID."','".$userID."','Default','1', ".
		" '".$encounter."','0','1', ".
		" '1','0','".$codeModifier."');";
		$result = sqlQuery($query);

	}

	return $result;
}

function AddCPT2CodeEncounter($pid,$date,$passedCodes)
{
//	*	Get the userID
	$myuserID=$_SESSION["authUserID"];
// authUserID = 1 
//	* 	Is this user authorized?
//	\,	check the date
//		x	Format the date for database entry
//		*	check the PID
//		* 	check the CODE
//	\,	Break the code part into $codeBase and $codeModifier
//	*	Query the facility Name
//	*	Query the facility ID
// site_id = default 
	//$myfacility=$_SESSION["pc_facility"];
	//$myfacility=$facility;
	$myfacility=1;	// This doesn't matter for PQRS Numerators
//	*	Find Provider ID  This needs to pick up from the encounter!!!
	$myprovider_id = findProvider( $pid );


	//  Is there already an encounter on our date?
	$query = "SELECT COUNT(*) AS count FROM form_encounter WHERE ".
		"pid = '$pid' AND date = '$date'";
	$result = SqlFetchArray(sqlStatement($query));
//error_log("DEBUG AddCPT2CodeEncounter() -- Checked for an encounter with our date \"".$date."\" with query \"".$query );
	if ($result['count'] > 0 ) {
//error_log("DEBUG AddCPT2CodeEncounter() -- found some encounters with our date: ".$result['count']);
	        $query = "SELECT encounter AS enc FROM form_encounter WHERE ".
                "pid = '$pid' AND date = '$date'";
        	$result = SqlFetchArray(sqlStatement($query));	
		$myencounterNumber = $result['enc'];
// error_log("DEBUG AddCPT2CodeEncounter() -- Will update encounter number: ".$myencounterNumber);
	} else {
		// Find the next encounter number to use
		$query = "SELECT MAX(encounter) as enc FROM form_encounter;";
		$result = sqlStatement($query);
		$tmpEncounterNumber = SqlFetchArray($result);
		$myencounterNumber = 1 + $tmpEncounterNumber['enc'];
// error_log("DEBUG AddCPT2CodeEncounter() -- queried encounter with: \"".$query."\" and got \"".$result."\"  and next encounterNumber as:  ".$myencounterNumber);

		$query= "INSERT INTO `form_encounter` ".
		" ( `date`, `reason`, ".
		" `facility`, `facility_id`, ".
		" `pid`, `encounter`, `onset_date`, ".
		" `sensitivity`, `billing_note`, `pc_catid`, `provider_id`, ".
		" `supervisor_id`, `billing_facility`) ".
		" VALUES ".
		" ('".$date."', 'MIPS Direct Entry Input:  See Fee Sheet', ".
		" '".$myfacility."','1', ".
		" '".$pid."','".$myencounterNumber."','0000-00-00 00:00:00', ".
		" 'normal', 'MIPS CPT2 Entries','9','".$myprovider_id."', ".
		" '0','1');";
		$result = sqlStatement($query);
//error_log("DEBUG AddCPT2CodeEncounter() -- Built form_encounter query: \"".$query." with result \"".SqlFetchArray($result)."\"");

		// Now query and find out the form_id we just created
		$query = "SELECT id FROM form_encounter WHERE encounter = ".$myencounterNumber.";";
		$result = sqlStatement($query);
		$myFormID = SqlFetchArray($result)['id'];
//error_log("DEBUG AddCPT2CodeEncounter() -- Queried form_id with query: \"".$query."\". Ran it with result \"".$result."  myFormID =".$myFormID );

		$query= "INSERT INTO `forms` ".
		" ( `date`, `encounter`, ".
		" `form_name`, `form_id`, ".
		" `pid`, `user`, `groupname`, `authorized`, ".
		"  `deleted`, `formdir`) ".
		" VALUES ".
		" ('".$date."','".$myencounterNumber."', ".
		" 'Patient Encounter', '".$myFormID."', ".
		" '".$pid."', '".$myuserID."','Default', '1', ".
		" '0', 'patient_encounter');";
		$result = sqlStatement($query);
//error_log("DEBUG AddCPT2CodeEncounter() -- Built forms-newpatient query: \"".$query." with result \"".SqlFetchArray($result)."\"");
	}	// End else Create a new encounter
	foreach (explode(" ", $passedCodes) as $ourCode ) {
		AddCPT2CodeBilling($pid,$date,$ourCode,$myencounterNumber,$myuserID);
	}

// TODO:  Return something specific if we were successful
    return "'pid=".$pid." / date=".$date." / codes=".$passedCodes." / provider_id=".$myprovider_id." / user=".$_SESSION["authUserID"]."'" ; //$result;

}	// End function AddCPT2CodeEncounter()


function findProvider($pid) {
	$find_provider = sqlQuery("SELECT providerID FROM patient_data " .
                "WHERE pid = ? ", array($pid) );
	$providerid = $find_provider['providerID'];
// error_log("DEBUG findProvider() -- called with pid:  ".$pid.", found provider:  ".$providerid."  ");
	return $providerid;
}	// End function find_provider()

function update_itemized_report( $myperformance,$myreport_id, $myitemized_test_id, $mypid) {
$query = "UPDATE report_itemized SET pass = '".$myperformance."' WHERE report_id = '".$myreport_id."' AND itemized_test_id = '".$myitemized_test_id."' AND pid = '".$mypid."' AND numerator_label = 'Numerator';";
//error_log("report_itemized query is: ".$query);
 sqlStatement($query);
}

//  Begin Main
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$mypid = $_POST['pid'];
	$mydate = $_POST['date'];
	$mycodeRaw = $_POST['CPT2codevalue'];
	$mycode = substr($mycodeRaw,2);
	$myperformance = substr($mycodeRaw,0,1);
	$myreport_id = $_POST['report_id'];
	$myitemized_test_id = $_POST['itemized_test_id'];
//error_log("DEBUG Main -- POSTed us with pid=".$mypid."  date=".$mydate."  code=".$mycode." performance=".$myperformance." report_id=".$myreport_id." itemized_test_id=".$myitemized_test_id);  //."  encounter=".$encounter."  user/provider=".$userID);

	if ( $mypid !='' and $mydate!='' and $mycode!='') {

		$result=AddCPT2CodeEncounter($mypid,$mydate,$mycode);
        $result2 = update_itemized_report($myperformance, $myreport_id, $myitemized_test_id, $mypid);
//error_log("DEBUG Main -- Post result values: performance=".$myperformance." report_id=".$myreport_id." itemized_test_id=".$myitemized_test_id." pid= ".$mypid );  //."  encounter=".$encounter."  user/provider=".$userID);

        		echo 'SUCCESS';

	}

}

?>
