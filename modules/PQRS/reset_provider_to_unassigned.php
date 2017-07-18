<?php
/*
 *Reset Primary Provider to "Unassigned"
 *
 * Copyright (C) 2017      Suncoast Connection
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package LibreEHR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 * @author  Bryan Lee  leebc11 at acm dot org
*/
require_once("../../globals.php");
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




