<?php
/**
 *  Fullscreen Program Page
 *
 *  This program displays the information entered in the Calendar program ,
 *  allowing the user to change status and view those changed here and in the Calendar
 *  Will allow the collection of length of time spent in each status
 *
 * Copyright (C) 02/28/2018 - Anirudh Singh (or LibreHealth.io Project)
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0 and the following
 * Healthcare Disclaimer
 *
 * In the United States, or any other jurisdictions where they may apply, the following additional disclaimer of
 * warranty and limitation of liability are hereby incorporated into the terms and conditions of MPL 2.0:
 *
 * No warranties of any kind whatsoever are made as to the results that You will obtain from relying upon the covered code
 *(or any information or content obtained by way of the covered code), including but not limited to compliance with privacy
 * laws or regulations or clinical care industry standards and protocols. Use of the covered code is not a substitute for a
 * health care provider’s standard practice or professional judgment. Any decision with regard to the appropriateness of treatment,
 * or the validity or reliability of information or content made available by the covered code, is the sole responsibility
 * of the health care provider. Consequently, it is incumbent upon each health care provider to verify all medical history
 * and treatment plans with each patient.
 *
 * Under no circumstances and under no legal theory, whether tort (including negligence), contract, or otherwise,
 * shall any Contributor, or anyone who distributes Covered Software as permitted by the license,
 * be liable to You for any indirect, special, incidental, consequential damages of any character including,
 * without limitation, damages for loss of goodwill, work stoppage, computer failure or malfunction,
 * or any and all other damages or losses, of any nature whatsoever (direct or otherwise)
 * on account of or associated with the use or inability to use the covered content (including, without limitation,
 * the use of information or content made available by the covered code, all documentation associated therewith,
 * and the failure of the covered code to comply with privacy laws and regulations or clinical care industry
 * standards and protocols), even if such party shall have been informed of the possibility of such damages.
 *
 * See the Mozilla Public License for more details.
 *
 * @package LibreHealth EHR
 * @author Anirudh Singh <anirudh.s.c.96@hotmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
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

<div id="mainBox">

    <div class="body_title" data-bind="template: {name: 'tabs-controls', data: application_data} "> </div>

    <iframe height="800px" src="/LibreEhr/interface/patient_tracker/patient_tracker.php?skip_timeout_reset=1" name="flb">
    </iframe>
    
</div>

<?php do_action( 'after_main_box' ); ?>
