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
 */
require_once '../../interface/globals.php';
include_once("$srcdir/api.inc");
 include_once("$srcdir/acl.inc");
?>	
<html>
<span class='title' visibility: hidden><?php echo htmlspecialchars( xl('Delete all QA Reports'), ENT_NOQUOTES); ?></span>
<b>This will delete all old reports!</b>
<BR>
<?php if (acl_check('admin', 'practice' )) { ?>

<input type="submit" name="formSubmit" value="Submit" />
<form action="truncate_reports.php" method="post">	
<?php
if($_POST['formSubmit'] == "Submit") 
{
sqlStatement("TRUNCATE TABLE `report_results`;");
sqlStatement("TRUNCATE TABLE `report_itemized`;");
echo "Table 'reports_results' and 'report_itemized' truncated.   Old reports deleted.";
}
//This whole feature needs to be moved to a integrated admin form, and naturally use css buttons and translations.
}else {echo "You do not have access to this feature.";}
?>

</html>
</form>
