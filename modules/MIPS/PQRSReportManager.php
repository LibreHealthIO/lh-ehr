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
	
	
//Delete a report
//error_log("DEBUG PQRSReportManager.php -- POSTed us with report_id=".$myreport_id."  action=".$myaction."  report_new_name = ".$myreport_new_name.".");
	if ( $myaction=='DELETE' && $myreport_id != '' ) {
		$query="DELETE FROM `report_results` WHERE `report_id` = ".$myreport_id.";";
			sqlStatement($query);
		$query="DELETE FROM `report_itemized` WHERE `report_id` = ".$myreport_id.";";
			sqlStatement($query);
			echo ("Deleted 'report_results' and 'report_itemized' that match id ".$myreport_id);
	}
	
//  Rename or name reports
    //check to see if a title exists.  REPLACE INTO will not work well for this.
	if ( $myaction=='RENAME' && $myreport_id != '' && $myreport_new_name != '' ) {
    	$check = "SELECT COUNT(report_id) AS count FROM report_results ".
    	         "WHERE report_id = '".$myreport_id."' AND field_id = 'title';";         
    	$result = sqlFetchArray(sqlStatementNoLog($check));
    	   
        if ($result['count'] > 0){      
        	//when a title field exists                  
        	    $update = "UPDATE report_results ".
        	              "SET field_value = '".$myreport_new_name."' ".
        	              "WHERE report_id = '".$myreport_id."' AND field_id = 'title';";
//error_log("DEBUG PQRSReportManager.php -- rename update = ".$update);
		        sqlStatement($update);
    	         } else {
        	//when a title field does not exist
        	    $update = "INSERT INTO report_results (report_id, field_id, field_value) VALUES ".
        	              " ('".$myreport_id."', 'title', '".$myreport_new_name."');";

//error_log("DEBUG PQRSReportManager.php -- new title insert = ".$update);
		sqlStatement($update);         
        	         }  
	}
	
// deleteall function not used right now.	
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