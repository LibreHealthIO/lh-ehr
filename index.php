<?php
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// Set the site ID if required.  This must be done before any database
// access is attempted.

if (!empty($_GET['site']))
    $site_id = $_GET['site'];
else if (is_dir("sites/" . $_SERVER['HTTP_HOST']))
    $site_id = $_SERVER['HTTP_HOST'];
else
    $site_id = 'default';

if (empty($site_id) || preg_match('/[^A-Za-z0-9\\-.]/', $site_id))
    die("Site ID '".htmlspecialchars($site_id,ENT_NOQUOTES)."' contains invalid characters.");
    
$config = 0;
if (is_file("sites/" .$site_id."/sqlconf.php")){
require_once "sites/$site_id/sqlconf.php";}

if ($config == 1) {
    header("Location: interface/login/login.php?site=$site_id");
} else {
    if ($site_id != 'default'){
    header("Location: setup.php");
    }else{
        echo "<p>Direct configuration of Site ID \" ".$site_id." \" is not permitted.</p>".
        "<p>If you know the name of the EHR site you want to access<br>".
        "Try adding \"?site=<i>mysitename</i>\" to the URL address.</p>";
        echo "<p>If you have not yet installed LibreEHR, or wish to install a new site, you may <br>".
        " <a href='setup.php'>CLICK HERE</a> to do so.</p>";
    }
}
