<?php
 /*
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @author  Suncoast Connection
 * @author  Bryan Lee
 * @link    http://suncoastconnection.com
*/

//require_once(dirname(__FILE__) . "/jsonwrapper/jsonwrapper.php");
include_once(__DIR__.'/../../../../interface/globals.php');
include_once(__DIR__.'/../../../../library/sql.inc');
//
//function listingCDRReminderLog($begin_date='',$end_date='') {
  //if (empty($end_date)) {
    //$end_date=date('Y-m-d H:i:s');
  //}
//}

//pid=' + '&date=' + '20160620' + '&CPT2codevalue=

function AddCPT2CodeBilling($pid,$date,$ourCode,$encounter,$userID)
{
//error_log("DEBUG: ACPT2CBilling -- Passed us:  pid=".$pid."  date=".$date."  code=".$ourCode."  encounter=".$encounter."  user/provider=".$userID);

	if ( $ourCode != "" ) {
		$codesplit=explode(":",$ourCode);
                $codeBase=$codesplit[0];
		if ( count($codesplit) > 1 ){
	                $codeModifier=$codesplit[1];
		} else {
			$codeModifier="";
		}
//error_log("DEBUG: ACPT2CBilling -- codeBase=".$codeBase." codeModifier=".$codeModifier);
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
// error_log("DEBUG: ACPT2CBilling -- Generated my query:  ".$query."  Executed with result:  ".$result);
	}
// TODO:  Return something specific if we were successful
	return $result;
}

function AddCPT2CodeEncounter($pid,$date,$passedCodes)
{
	 
//This is a DEBUG block:
/*
	$var="";
	$session_string="";
	foreach ($_SESSION as $k => $var) {
		$session_string=$session_string." // ".$k." = ".$var;
	}
	error_log("DEBUG AddCPT2CodeEncounter() -- _SESSION:".$session_string);
*/
//$_SESSION contains:
// language_choice = 1 
// authUser = admin 
// authPass = $
// authGroup = Default 
// authProvider = Default 
// authId = 1 
// cal_ui = 3 
// userauthorized = 1 
// last_update = 1468528344 
// encounter =  
// pc_username = admin 
// pc_framewidth = 1126 
// pc_facility = 0 
// viewtype = day 
// PNSVrand = 146242944 
// PNSVlang = eng 
// lastcaldate = 2016-07-14 
// pid = 1,


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
//	*	Find Provider ID
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
		" ('".$date."', 'PQRS Direct Entry Input:  See Fee Sheet', ".
		" '".$myfacility."','1', ".
		" '".$pid."','".$myencounterNumber."','0000-00-00 00:00:00', ".
		" 'normal', 'PQRS CPT2 Entries','9','".$myprovider_id."', ".
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
		" 'New Patient Encounter', '".$myFormID."', ".
		" '".$pid."', '".$myuserID."','Default', '1', ".
		" '0', 'newpatient');";
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

function update_itemized_report($report_id, $itemized_test_id, $performance, $pid) {
    return sqlStatementCdrEngine(
        "UPDATE `report_itemized` SET `pass` = ? WHERE `report_id` = ? AND `itemized_test_id` = ? AND `pid` = ?",
        array(
            $performance,
            $report_id,
            $itemized_test_id,
            $pid
        )
    );
}

//  Begin Main
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$mypid = $_POST['pid'];
	$mydate = $_POST['date'];
	$mycode = $_POST['CPT2codevalue'];
	$myperformance = $_POST['performance'];
	$myreport_id = $_POST['report_id'];
	$myitemized_test_id = $_POST['itemized_test_id'];
error_log("DEBUG Main -- POSTed us with pid=".$mypid."  date=".$mydate."  code=".$mycode." performance=".$myperformance." report_id=".$myreport_id." itemized_test_id=".$myitemized_test_id);  //."  encounter=".$encounter."  user/provider=".$userID);

	if ( $mypid !='' and $mydate!='' and $mycode!='') {

		$result=AddCPT2CodeEncounter($mypid,$mydate,$mycode);
        $result2=update_itemized_report($myreport_id, $myitemized_test_id, $myperformance, $mypid);
// TODO:  How do we tell if we REALLY were successful.
//		if(rand(1, 15) > 15) {
        		echo 'SUCCESS';
//		} else {
//        		echo 'FAILED:'.$result;
//		}
	}

	
		

		
}

?>
