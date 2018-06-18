<?php
 /*
 * Copyright (C) 2017      Suncoast Connection
 *
 * @package PQRS_Gateway
 * @author  Suncoast Connection
 * @author  Bryan Lee
 * @link    http://suncoastconnection.com
*/
//require_once(dirname(__FILE__) . "/jsonwrapper/jsonwrapper.php");
include_once(__DIR__.'/../../interface/globals.php');
include_once(__DIR__.'/../../library/sql.inc');
//error_log("DEBUG PQRSReportManager.php -- started");
//  Begin Main
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$myreport_id = $_POST['report_id'];
	$myaction=$_POST['action'];
	$myreport_raw_name = $_POST['report_new_name'];
	$myreport_new_name = htmlspecialchars($myreport_raw_name);
//error_log("DEBUG PQRSReportManager.php -- POSTed us with report_id=".$myreport_id."  action=".$myaction."  report_new_name = ".$myreport_new_name.".");
	if ( $myaction=='DELETE' && $myreport_id != '' ) {
		$query="DELETE FROM `report_results` WHERE `report_id` = ".$myreport_id.";";
			sqlStatement($query);
		$query="DELETE FROM `report_itemized` WHERE `report_id` = ".$myreport_id.";";
			sqlStatement($query);
			echo ("Deleted 'report_results' and 'report_itemized' that match id ".$myreport_id);
	}
	if ( $myaction=='RENAME' && $myreport_id != '' && $myreport_new_name != '' ) {
    	$query = "UPDATE report_results ".
    	         "SET field_value = '".$myreport_new_name."' ".
    	         "WHERE report_id = ".$myreport_id." AND field_id = 'title';";
		//error_log("DEBUG PQRSReportManager.php -- query = ".$query);
		sqlStatement($query);
	}
	if ( $myaction=='DELETEALL') {
		$query="TRUNCATE TABLE `report_results`;";
		sqlStatement($query);
		$query="TRUNCATE TABLE `report_itemized`;";
		sqlStatement($query);
		echo "Table 'reports_results' truncated and 'report_itemized'.   Old reports deleted.";
	}
	echo 'SUCCESS';
}
?>