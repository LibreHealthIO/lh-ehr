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
sqlStatement("TRUNCATE TABLE `addresses`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/addresses.sql");
sqlStatement($query);

sqlStatement("TRUNCATE TABLE `billing`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/billing.sql");
sqlStatement($query);

sqlStatement("TRUNCATE TABLE `facility`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/facilities.sql");
sqlStatement($query);

sqlStatement("TRUNCATE TABLE `form_encounter`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/form_encounter.sql");
sqlStatement($query);

sqlStatement("TRUNCATE TABLE `forms`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/forms.sql");
sqlStatement($query);

sqlStatement("TRUNCATE TABLE `insurance_companies`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/insurance_companies.sql");
sqlStatement($query);

sqlStatement("TRUNCATE TABLE `insurance_data`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/insurance_data.sql");
sqlStatement($query);

sqlStatement("TRUNCATE TABLE `patient_data`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/patient_data.sql");
sqlStatement($query);

sqlStatement("TRUNCATE TABLE `phone_numbers`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/phone_numbers.sql");
sqlStatement($query);

sqlStatement("TRUNCATE TABLE `x12_partners`;");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/x12_partners.sql");
sqlStatement($query);

sqlStatement("DELETE FROM `users` WHERE `users`.`id` > '20';");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/users.sql");
sqlStatement($query);

sqlStatement("DELETE FROM `groups` WHERE `groups`.`id` > '20';");
$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/Importer/groups.sql");
sqlStatement($query);
echo "Database updated!";
}
?>
<html>
<input type="submit" name="formSubmit" value="Submit" />
</html>
</form>
