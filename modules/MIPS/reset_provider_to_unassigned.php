<?php
/*
 *Reset Primary Provider to "Unassigned"
 *
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
<html>
<form action="reset_provider_to_unassigned.php" method="post">	
<?php
if($_POST['formSubmit'] == "Submit") 
{
sqlStatement("UPDATE `patient_data` SET `patient_data`.`providerID` = '0';");
echo "providerID set to 0 (\"Unassigned\") for all patients.";
echo "<hr>";
}
?>
<p>Click "Submit" to remove all Primary Provider assignments.  (Set all patient's "Primary Provider" to "Unassigned".)</p>
<input type="submit" name="formSubmit" value="Submit" />
</form>
</html>




