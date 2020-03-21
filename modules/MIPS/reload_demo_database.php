<?php
/*
 *Load Demo Database from Presets
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
include_once("$srcdir/acl.inc");

?>	
<html>
<?php if (acl_check('admin', 'practice' )) { ?>
<span class='title' visibility: hidden><?php echo htmlspecialchars( xl('Demo Data Loading'), ENT_NOQUOTES); ?></span>
<form action="reload_demo_database.php" method="post">	
<?php
if($_POST['formSubmit'] == "Submit") 
{
	$action=$_POST['action'];
	if($action == "none"){
		echo "<p><b>". xlt("Please chose a valid preset and an action").".</b><p>";
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
<p>Choose a LOAD/SAVE Preset Slot for your patient database, then click "Submit".<br>
This ONLY affects the following tables:  addresses, billing, facility, form_encounter, forms, insurance_companies, insurance_data, patient_data, phone_numbers, x12_partners.
</p>
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
<?php }
else {echo "You do not have access to this feature.";}
?>
</html>

