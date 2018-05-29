<?php
/*
 * Claims2LHEHR import script
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
<span class='title' visibility: hidden>Import Updates</span>
<h1>Import Processed x12 837 file data</h1>
<b>This tool simply runs update query files.</b>
<form action="update_data.php" method="post">	
<?php
if($_POST['formSubmit'] == "Submit") 
{

if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/1.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/1.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/2.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/2.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/3.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/3.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/4.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/4.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/5.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/5.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/6.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/6.sql");
	sqlStatementNoLog($query);
}
if ( file_exists($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/7.sql")) {
	$query = file_get_contents($GLOBALS['OE_SITE_DIR']."/filemanager/files/Updates/7.sql");
	sqlStatementNoLog($query);
}

echo "Database updated!";
}else{
    echo "<input type='submit' name='formSubmit' value='Submit' />";}
?>
<?php }
else {echo "You do not have access to this feature.";}
?>
</form>
</html>

