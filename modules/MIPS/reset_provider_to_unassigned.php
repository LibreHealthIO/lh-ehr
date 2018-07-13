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
include_once("$srcdir/acl.inc");
$form_doctor = "";

?>	
<html>
<?php if (acl_check('admin', 'practice' )) { ?>


    
    <span class='title'><?php echo htmlspecialchars( xl('Reset Providers'), ENT_NOQUOTES); ?></span>
    <form action="reset_provider_to_unassigned.php" method="post">	
    <?php
    if($_POST['formSubmit'] == "Submit") 
    {
    sqlStatement("UPDATE `patient_data` SET `patient_data`.`providerID` = '0';");
    echo "providerID set to 0 (\"Unassigned\") for all patients.";
    echo "<hr>";
    }else{
    ?>
    <p><?php echo xlt("WARNING:  This will remove all Primary Provider assignments.  [Set all patient's demographic setting PRIMARY PROVIDER to Unassigned.]");?></p>
    <input type="submit" name="formSubmit" value=<?php echo xlt("Submit");?> />
    <?php }
    ?>
    </form>
    
    <span class='title'><?php echo htmlspecialchars( xl('Assign Providers'), ENT_NOQUOTES); ?></span>
    <form action="reset_provider_to_unassigned.php" method="post">
        <?php
      // Build a drop-down list of providers.
 
					$query = "select id, lname, fname from users where " .
						"authorized = 1 order by lname, fname";
					$res = sqlStatement($query);
					echo "   &nbsp;<select name='form_doctor'>\n";
					while ($row = sqlFetchArray($res)) {
						$provid = $row['id'];
						echo "    <option value='$provid'";
						if ($provid == $_POST['form_doctor']) echo " selected";
						echo ">" . $row['lname'] . ", " . $row['fname'] . "\n";
					}
					echo "   </select>\n";
					
        if($_POST['formSubmit2'] == "Set") 
        {
                $form_doctor = $_POST['form_doctor'];
            sqlStatement("UPDATE `patient_data` SET `patient_data`.`providerID` = '$form_doctor';");
            echo "providerID set to $form_doctor for all patients.";
            echo "<hr>";
        }else{
            ?>
            <p><?php echo xlt("WARNING:  This will set all Primary Provider assignments in Patient demographics.  [Use to set all Patients to the Provider Group.]");?></p>
            <input type="submit" name="formSubmit2" value=<?php echo xlt("Set");?> />
        <?php }
    ?>
    </form>



<?php }
else {echo "You do not have access to this feature.";}
?>
</html>
