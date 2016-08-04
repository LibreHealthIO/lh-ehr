<?php
Use Esign\Api;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$fake_register_globals=false;
$sanitize_all_escapes=true;

/* Include our required headers */
require_once('../../globals.php');
require_once($webserver_root."/interface/includes/include_utils.php");
require_once $GLOBALS['srcdir'].'/ESign/Api.php';
$esignApi = new Api();

?>
<!DOCTYPE html>
<title>LibreEHR Tabs</title>
<script type="text/javascript">
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

//  Include this variable for backward compatibility 
var loadedFrameCount = 0;
var tab_mode=true;
function allFramesLoaded() {
// Stub function for backward compatibility with frame race condition mechanism
 return true;
}

function isEncounterLocked( encounterId ) {
    <?php if ( $esignApi->lockEncounters() ) { ?>
	// If encounter locking is enabled, make a syncronous call (async=false) to check the
	// DB to see if the encounter is locked.
	// Call restore session, just in case
	top.restoreSession();
    $.ajax({
        type: 'POST',
        url: '<?php echo $GLOBALS['webroot']?>/interface/esign/index.php?module=encounter&method=esign_is_encounter_locked',
        data: { encounterId : encounterId },
        success: function( data ) {
            encounter_locked = data;
        },
        dataType: 'json',
        async:false
	});	    
	return encounter_locked;
	<?php } else { ?>
	// If encounter locking isn't enabled then always return false
	return false;
	<?php } ?>
}
var webroot_url="<?php echo $web_root; ?>";
</script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href="css/tabs.css"/>
<link rel="stylesheet" type="text/css" href="css/menu.css"/>

<?php include_js_library("knockout/knockout-3.4.0.js"); ?>
<?php include_js_library("jquery-2.2.0.min.js"); ?>

<script type="text/javascript" src="js/custom_bindings.js"></script>



<script type="text/javascript" src="js/user_data_view_model.js"></script>
<script type="text/javascript" src="js/patient_data_view_model.js"></script>
<script type="text/javascript" src="js/tabs_view_model.js"></script>
<script type="text/javascript" src="js/application_view_model.js"></script>
<script type="text/javascript" src="js/frame_proxies.js"></script>
<script type="text/javascript" src="js/dialog_utils.js"></script>

<link rel='stylesheet' href='<?php echo $web_root; ?>/library/fonts/typicons/typicons.min.css' />

<?php require_once("templates/tabs_template.php"); ?>
<?php require_once("templates/menu_template.php"); ?>
<?php require_once("templates/patient_data_template.php"); ?>
<?php require_once("templates/user_data_template.php"); ?>
<?php require_once("menu/menu_json.php"); ?>
<?php $userQuery = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'"); ?>
<script type="text/javascript">
    <?php if(isset($_REQUEST['url']))
        {
        ?>
            app_view_model.application_data.tabs.tabsList()[0].url(<?php echo json_encode("../".urldecode($_REQUEST['url'])); ?>);
        <?php 
        }
    ?>
    app_view_model.application_data.user(new user_data_view_model(<?php echo json_encode($_SESSION{"authUser"})
                                                                  .',' . json_encode($userQuery['fname'])
                                                                  .',' . json_encode($userQuery['lname'])
                                                                  .',' . json_encode($_SESSION['authGroup']); ?>));
</script>
<div id="mainBox">
    <div id="dialogDiv"></div>
    <div class="body_top">
        <span id="menu"  data-bind="template: {name: 'menu-template', data: application_data} "> </span>
        <span id="userData" data-bind="template: {name: 'user-data-template', data:application_data} "></span>
    </div>
    <div id="patientData" class="body_title" data-bind="template: {name: 'patient-data-template', data: application_data} "></div>
    <div class="body_title" data-bind="template: {name: 'tabs-controls', data: application_data} "> </div>

    <div class="mainFrames">
        <div id="framesDisplay" data-bind="template: {name: 'tabs-frames', data: application_data}"> </div>
    </div>
</div>
<script>
    $("#dialogDiv").hide();
    ko.applyBindings(app_view_model);

</script>

<?php do_action( 'after_main_box' ); ?>
