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
include_once(__DIR__.'/../../../../interface/globals.php');
include_once(__DIR__.'/../../../../library/sql.inc');


//error_log("DEBUG PQRSReportManager.php -- started");

//  Begin Main
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$myaction=$_POST['action'];
	$myreport_id = $_POST['report_id'];
	$myreport_new_name = $_POST['report_new_name'];

error_log("DEBUG PQRSReportManager.php -- POSTed us with action=".$myaction."  report_id=".$myreport_id."  report_new_name=".$report_new_name.".");

	if ( $myaction=='DELETE') {
		if ( $myreport_id != '' ) {
			sqlStatement("DELETE FROM `report_results` WHERE `report_id` = ".$myreport_id.";");
			sqlStatement("DELETE FROM `report_itemized` WHERE `report_id` = ".$myreport_id.";");
			echo ("Deleted 'report_results' and 'report_itemized' that match id ".$myreport_id);
	}
	} else {
	if ( $myaction=='RENAME') {
		if ( $myreport_id != '' and $myreport_new_name != '' ) {
			sqlStatement("UPDATE `report_results` SET `field_value` = '".$myreport_new_name."' WHERE `report_results`.`report_id` = ".$myreport_id." AND `report_results`.`field_id` = 'type';");
	}
	} else {
	if ( $myaction=='DELETEALL') {
		sqlStatement("TRUNCATE TABLE `report_results`;");
		sqlStatement("TRUNCATE TABLE `report_itemized`;");
		echo "Table 'reports_results' truncated and 'report_itemized'.   Old reports deleted.";
	}
	}
	}
	echo 'SUCCESS';
}

?>
