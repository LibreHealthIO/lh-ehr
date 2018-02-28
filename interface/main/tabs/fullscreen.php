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
require_once("../../globals.php");
require_once $GLOBALS['srcdir'].'/headers.inc.php';
require_once $GLOBALS['srcdir'].'/ESign/Api.php';
/* for getPnotesByUser(). */
require_once($GLOBALS['srcdir'] . '/pnotes.inc');
$esignApi = new Api();

?>
<!DOCTYPE html>
<title><?php echo $GLOBALS['libreehr_name'];?></title>
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
