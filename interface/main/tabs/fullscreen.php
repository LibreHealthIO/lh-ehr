<?php
/** 
 * Fullscreen application page 
 * Displays any of the components of the website in a fullscreen manner, 
 * disabling navigation to any other page.
 * 
 * Copyright (C) 2018 Anirudh Singh
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @package Librehealth EHR 
 * @author Anirudh (anirudh.s.c.96@hotmail.com)
 * @link http://librehealth.io
 *  
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 * 
 */
$fake_register_globals=false;
$sanitize_all_escapes=true;

/* Include our required headers */
require_once("../../globals.php");
require_once $GLOBALS['srcdir'].'/headers.inc.php';

?>
<!DOCTYPE html>
<title><?php echo $GLOBALS['libreehr_name'];?></title>
<script type="text/javascript">
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>
var webroot_url="<?php echo $web_root; ?>";
var fullscreen_page = true;
</script>
<link rel="stylesheet" type="text/css" href="css/tabs.css"/>

<?php
    /*  Include Bootstrap, Knockout Libraries and Font Awesome library   */
  call_required_libraries(array("jquery-min-3-1-1","bootstrap", "font-awesome"));
?>
<div class="fullscreen-nav" style="background-color: #f5f5f5;">
    <div style="float: right; font-size: 18px;"><a href="../../logout.php"> <?php echo xlt('Logout'); ?> </a>&nbsp;</div>
</div>

<link rel='stylesheet' href='<?php echo $web_root; ?>/library/fonts/typicons/typicons.min.css' />
<link rel="shortcut icon" href="<?php echo $web_root; ?>/favicon.ico" type="image/x-icon">

<?php $userQuery = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'"); ?>
<?php $pageUrl = explode('|', $userQuery['fullscreen_page'])[1]; ?>
<div id="mainBox">

    <div class="body_title" data-bind="template: {name: 'tabs-controls', data: application_data} "> </div>

    <iframe height="800px" src="<?= $web_root.$pageUrl; ?>">
    </iframe>
    
</div>

<?php do_action( 'after_main_box' ); ?>
