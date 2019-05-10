<?php
/*
 * Claims2LHEHR import script
 * Copyright (C) 2015 - 2017      Suncoast Connection
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @author  Bryan lee <leebc@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 * TODO:  UPDATE THIS FORM using a file iterator or SWITCH STatement
 */
require_once '../../interface/globals.php';
include_once("$srcdir/api.inc");
include_once("$srcdir/acl.inc");
?>	
<html>
<head>
    <link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
</head>
<body class="body_top">

<?php if (acl_check('admin', 'practice' )) { ?>
<span class='title' visibility: hidden>Import Database</span>
<h1>Import Processed x12 837 file data</h1>
<b>This tool truncates all data from previous imports, and loads the new data uploaded to the sites directory.  It also removes all users with an ID number greater than 1000.  Imported data user (providers) 
are set to NPI numbers which are ten digit numbers.</b>
<form action="import_data.php" method="post">	
<?php
if($_POST['formSubmit'] == "Submit") 
{
    
    $billingfile = 0;
    $encounterfile = 0;
    $formfile = 0;
	$billingsearch = true;    
    $encountersearch = true;
    $formsearch = true;    
sqlStatementNoLog("TRUNCATE TABLE `addresses`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/addresses.sql");
sqlStatementNoLog($query);

/*  This method will not work due to the max_execution_time limit
sqlStatementNoLog("TRUNCATE TABLE `billing`;");
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing.sql");
	sqlStatementNoLog($query);
}

while ($billingsearch == true){
    $billfile +=1;
    if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing".$billfile.".sql")) {
    	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing".$billfile.".sql");
    	sqlStatementNoLog($query);
    }else{$billingsearch == false;}
}
*/

//version to run each file in a special script to avoid time out
sqlStatementNoLog("TRUNCATE TABLE `billing`;");
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing1.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing1.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing2.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing2.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing3.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing3.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing4.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing4.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing5.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing5.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing6.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing6.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing7.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing7.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing8.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing8.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing9.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing9.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing10.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing10.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing11.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing11.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing12.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing12.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing13.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing13.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing14.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing14.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing15.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing15.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing16.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing16.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing17.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing17.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing18.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing18.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing19.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing19.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing20.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing20.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing21.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing21.sql");
	sqlStatementNoLog($query);
}


sqlStatementNoLog("TRUNCATE TABLE `facility`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/facilities.sql");
sqlStatementNoLog($query);

/*  This method will not work due to the max_execution_time limit
sqlStatementNoLog("TRUNCATE TABLE `form_encounter`;");
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter.sql");
	sqlStatementNoLog($query);
}

while ($encountersearch == true){
    $encounterfile +=1;
    if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter".$encounterfile.".sql")) {
    	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter".$encounterfile.".sql");
    	sqlStatementNoLog($query);
    }else{$encountersearch = false;}

}
*/
//version to run each file in a special script to avoid time out

sqlStatementNoLog("TRUNCATE TABLE `form_encounter`;");
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter1.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter1.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter2.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter2.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter3.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter3.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter4.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter4.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter5.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter5.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter6.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter6.sql");
	sqlStatementNoLog($query);
}

/*  This method will not work due to the max_execution_time limit
sqlStatementNoLog("TRUNCATE TABLE `forms`;");
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms.sql");
	sqlStatementNoLog($query);
}

while ($formsearch == true){
    $formfile +=1;
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms".$formfile.".sql")) {
        $query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms".$formfile.".sql");
        sqlStatementNoLog($query);
}else {$formsearch = false;}

}
*/
//version to run each file in a special script to avoid time out
sqlStatementNoLog("TRUNCATE TABLE `forms`;");
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms1.sql")) {
        $query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms1.sql");
        sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms2.sql")) {
        $query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms2.sql");
        sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms3.sql")) {
        $query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms3.sql");
        sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms4.sql")) {
        $query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms4.sql");
        sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms5.sql")) {
        $query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms5.sql");
        sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms6.sql")) {
        $query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms6.sql");
        sqlStatementNoLog($query);
}

sqlStatementNoLog("TRUNCATE TABLE `insurance_companies`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/insurance_companies.sql");
sqlStatementNoLog($query);

sqlStatementNoLog("TRUNCATE TABLE `insurance_data`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/insurance_data.sql");
sqlStatementNoLog($query);

sqlStatementNoLog("TRUNCATE TABLE `patient_data`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/patient_data.sql");
sqlStatementNoLog($query);

sqlStatementNoLog("TRUNCATE TABLE `phone_numbers`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/phone_numbers.sql");
sqlStatementNoLog($query);

sqlStatementNoLog("TRUNCATE TABLE `x12_partners`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/x12_partners.sql");
sqlStatementNoLog($query);

sqlStatementNoLog("DELETE FROM `users` WHERE `users`.`id` > '1000000000';");
sqlStatementNoLog("INSERT INTO `users` (`id`,`username`,`password`,`authorized`,`fname`,`mname`,`lname`,`federaltaxid`,`federaldrugid`,`facility`,`facility_id`,`see_auth`,`active`,`npi`,`cal_ui`,`taxonomy`,`calendar`,`abook_type`,`state_license_number`) VALUES ('1000000001','ClinicGroup','70702b9402107c11ef9d18d9daad4ff1','1','Clinic','','Group','','','','1','3','1','1000000001','3','','1','miscellaneous','');");

$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/users.sql");
sqlStatementNoLog($query);

sqlStatementNoLog("DELETE FROM `groups` WHERE `groups`.`id` > '1000000000';");
sqlStatementNoLog("INSERT INTO `groups` ( `id`, `name`,`user`) VALUES ('1000000001','Default','ClinicGroup');");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/groups.sql");
sqlStatementNoLog($query);
sqlStatementNoLog("UPDATE `sequences` SET id = (SELECT MAX(form_encounter.encounter) FROM form_encounter);");
echo "Database updated!";
}else{
    echo "<input type='submit' name='formSubmit' value='Submit' />";}
?>
<?php }
else {echo "You do not have access to this feature.";}
?>
</form>

</body>
</html>

