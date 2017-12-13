<?php
	/*
	 * Truncate old MIPS reports
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
	 * 
	 * IMPORTANT:
	 * This whole feature needs to be moved to a integrated admin form, and naturally use css buttons and translations.
	 */
	require_once '../../interface/globals.php';
	include_once("$srcdir/api.inc");
	include_once("$srcdir/acl.inc");

	// Warning message created here and echoed on its place. The single ' is shown as ` don't know why. This must be fixed.
	$reportDeleteWarning = xlt("This will irreversibly delete all existing reports!");
?>	
<html>
	<span class='title' visibility: hidden><?php echo htmlspecialchars( xl('Delete all QA Reports'), ENT_NOQUOTES); ?></span>
	<b style="color: red"><?php echo $reportDeleteWarning; ?></b>
	<BR>
	<?php if (acl_check('admin', 'practice' )) { ?>

	<?php
		// Confirmation message
		$confirmationMessage = xlt("Do you really want to delete all old reports? This action is irreversible!");

		// onSubmit form property created here and echoed on its place
		$formOnSubmit = "onsubmit=\"return confirm('".$confirmationMessage."');\"";
	?>

	<form action="truncate_reports.php" method="post" <?php echo $formOnSubmit; ?>>
		<br>
		<input type="submit" name="formSubmit" value="Submit" />
		<?php
			if($_POST['formSubmit'] == "Submit") {
				sqlStatement("TRUNCATE TABLE `report_results`;");
				sqlStatement("TRUNCATE TABLE `report_itemized`;");
				$successfulMessage = xlt("Old reports deleted successfully.");
				$accessDeniedMessage = xlt("You do not have access to this feature.");

				echo "<script>alert('".$successfulMessage."');</script>";

				// I JUST REALIZED THAT IF I REMOVE ONE OF THE TWO BRACKETS BELOW (}}) THE CODE DOESNT WORK!
				// It must be some kind of PHP error or cache, i don't really know. 
			}} else {
				echo "<script>alert('".$accessDeniedMessage."');</script>";
			}
				
		?>
	</form>
</html>