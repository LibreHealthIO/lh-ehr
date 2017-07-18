<?php
/*
 *Claims2OEMR import script
 *
 * Copyright (C) 2016      Suncoast Connection
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
 * @package OpenEMR
 * @link    http://www.oemr.org
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
*/
require_once("../../globals.php");
include_once("$srcdir/api.inc");

?>	
<form action="truncate_reports.php" method="post">	
<?php
if($_POST['formSubmit'] == "Submit") 
{
sqlStatement("TRUNCATE TABLE `report_results`;");
sqlStatement("TRUNCATE TABLE `report_itemized`;");
echo "Table 'reports_results' truncated and 'report_itemized'.   Old reports deleted.";
}
?>
<html>
<b>This will delete all old reports!</b>
<input type="submit" name="formSubmit" value="Submit" />
</html>
</form>
