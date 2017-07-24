<?php
/*
 * Claims2PQRS import script
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
 */
require_once '../../interface/globals.php';
include_once("$srcdir/api.inc");

?>	
<form action="import_data.php" method="post">	
<?php
if($_POST['formSubmit'] == "Submit") 
{
sqlStatementNoLog("TRUNCATE TABLE `addresses`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/addresses.sql");
sqlStatementNoLog($query);

sqlStatementNoLog("TRUNCATE TABLE `billing`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/billing.sql");
sqlStatementNoLog($query);

sqlStatementNoLog("TRUNCATE TABLE `facility`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/facilities.sql");
sqlStatementNoLog($query);

sqlStatementNoLog("TRUNCATE TABLE `form_encounter`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/form_encounter.sql");
sqlStatementNoLog($query);

sqlStatementNoLog("TRUNCATE TABLE `forms`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/forms.sql");
sqlStatementNoLog($query);

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

sqlStatementNoLog("DELETE FROM `users` WHERE `users`.`id` > '20';");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/users.sql");
sqlStatementNoLog($query);

sqlStatementNoLog("DELETE FROM `groups` WHERE `groups`.`id` > '20';");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Importer/groups.sql");
sqlStatementNoLog($query);
echo "Database updated!";
}
?>
<html>
<input type="submit" name="formSubmit" value="Submit" />
</html>
</form>
