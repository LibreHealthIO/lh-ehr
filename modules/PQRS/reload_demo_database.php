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
 * @package LibreEHR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 * @author  Bryan Lee  leebc11 at acm dot org
*/
require_once("../../globals.php");
include_once("$srcdir/api.inc");

?>	
<html>
<form action="reload_demo_database.php" method="post">	
<?php
if($_POST['formSubmit'] == "Submit") 
{
	$action=$_POST['action'];
	if($action == "none"){
		echo "<p><b>Please chose a valid preset and an action.</b><p>";
	}else{
		$preset=$_POST['preset'];
		if( ( 0 < $preset ) && ( $preset < 10 ) ){
			// Doing something with a valid preset
			$demopath=$GLOBALS['fileroot']."/sql/demopresets/".$preset;
			//echo "<p>demopath= $demopath<p>";
			//$files1 = scandir($demopath);
			//echo "File listing:  ";
			//print_r($files1);
			//echo "<hr>";

			$ourtables = array( "addresses", "billing", "facility", "form_encounter", "forms", "insurance_companies", "insurance_data", "patient_data", "phone_numbers", "x12_partners");

			if($action == "load"){
				/// Check if directory exists.
				if (!file_exists($demopath)) {
                                        echo "Directory $demopath does not exist; this preset does not exist...<p>";
				} else {
				// Track whether we actuauly updated anything
					$updated=false;
					echo "Loading from Preset $preset...<p>";
					foreach ($ourtables as $tableName) {
				   	$fileName="$demopath/$tableName.sql";
				   	if (file_exists($fileName)) {
				   		sqlStatement("TRUNCATE TABLE `$tableName`;");
				   		$execCommand='mysql --user='.$GLOBALS['sqlconf']['login'].' --password='.$GLOBALS['sqlconf']['pass'].' --host='.$GLOBALS['sqlconf']['host'].' '.$GLOBALS['sqlconf']['dbase'].' < "'.$fileName.'"';

				   		echo "Loading from $fileName ...";
				   		//echo "Executing command:  $execCommand ";
				   		exec($execCommand, $output, $returnint);
				   		echo (" (Return:  $returnint  |  Output:  ".implode(" ",$output).")<br>");
						if ( $returnint == 0) {
							$updated=true;
						} else {
							echo (" <b> Problem loading file!  (Permissions?) </b><p>");
						}  
				   	} else {
						echo ("$fileName <b>not found.</b> <br>");
				   	} //else
					}
					if ($updated) {
						echo "<p>Database updated!<p>";
					} else {
						echo "<p>Database <b>not</b> updated!<p>";
					}
				}
			} else if ($action == "save"){
				echo "Saving to Preset $preset...<p>";
				if (!file_exists($demopath)) {
					echo "Directory $demopath does not exist; attempting to create...<p>";
					mkdir($demopath, 0755, true );
				}
				if (!file_exists($demopath)) {
					echo "<b>Failed to create Directory $demopath!</b><p>";
				} else {
					foreach ($ourtables as $tableName) {
						$fileName="$demopath/$tableName.sql";
						echo "Saving $tableName to $fileName ...";
						// save this line  $skipComments=" --skip-add-locks --skip-disable-keys --skip-set-charset --skip-comments --compact ";
						$execCommand='mysqldump --user='.$GLOBALS['sqlconf']['login'].' --password='.$GLOBALS['sqlconf']['pass'].' --host='.$GLOBALS['sqlconf']['host'].' '.$GLOBALS['sqlconf']['dbase'].' '.$tableName.' > '.$fileName;
						//echo ($execCommand.PHP_EOL);
						exec($execCommand, $output, $returnint);
						echo (" (Return:  $returnint  |  Output:  ".implode(" ",$output)." )  <br>");
						if ( $returnint != 0) {
							echo (" <b> Problem saving file!  (Permissions?) </b><p>");
						}
					}
					echo "<p>Database saved!<p>";
				}
			}
		}
	}
echo "<hr>";
}
?>
<p>Choose a preset to Load from or Save the database to, then click "Submit".</p>
<div>
<select name="preset">
	<option value="1">Preset 1</option>
	<option value="2">Preset 2</option>
	<option value="3">Preset 3</option>
	<option value="4">Preset 4</option>
	<option value="5">Preset 5</option>
	<option value="6">Preset 6</option>
	<option value="7">Preset 7</option>
	<option value="8">Preset 8</option>
	<option value="9">Preset 9</option>
</select>
<label><input type="radio" name="action" value="none" checked="checked">Do nothing</label>
<label><input type="radio" name="action" value="load">Load</label>
<label><input type="radio" name="action" value="save">Save</label>
</div>
<input type="submit" name="formSubmit" value="Submit" />
</form>
</html>
</form>
