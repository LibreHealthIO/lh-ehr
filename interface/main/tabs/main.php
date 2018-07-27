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
require '../../../updater/lib/updater_functions.php';
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
  call_required_libraries(array("jquery-min-3-1-1","bootstrap","knockout", "font-awesome", "iziModalToast"));
?>

<script type="text/javascript" src="js/custom_bindings.js"></script>
<script type="text/javascript" src="js/user_data_view_model.js"></script>
<script type="text/javascript" src="js/patient_data_view_model.js"></script>
<script type="text/javascript" src="js/tabs_view_model.js"></script>
<script type="text/javascript" src="js/application_view_model.js"></script>
<script type="text/javascript" src="js/frame_proxies.js"></script>
<script type="text/javascript" src="js/dialog_utils.js"></script>

<link rel='stylesheet' href='<?php echo $web_root; ?>/library/fonts/typicons/typicons.min.css' />
<link rel="shortcut icon" href="<?php echo $web_root; ?>/favicon.ico" type="image/x-icon">

<?php require_once("templates/tabs_template.php"); ?>
<?php require_once("templates/menu_template.php"); ?>
<?php require_once("templates/patient_data_template.php"); ?>
<?php require_once("templates/user_data_template.php"); ?>
<?php require_once("menu/menu_json.php"); ?>
<?php $userQuery = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'"); ?>
<script type="text/javascript">
    <?php if(isset($_REQUEST['url']))
        {

            $tab_one_default=$web_root.$GLOBALS['default_tab_1'];
            if($_REQUEST['url']==='TAB_ONE_DEFAULT')
            {
                $tab_one_contents=$tab_one_default;
            }
            else
            {
                $tab_one_contents="../".urldecode($_REQUEST['url']);
            }
            $tab_one_contents=json_encode($tab_one_contents);
            $tab_one_default=json_encode($tab_one_default);
        ?>
            tab_defaults=[];
            tab_defaults[0]=<?php echo $tab_one_default; ?>;
            app_view_model.application_data.tabs.tabsList()[0].url(<?php echo $tab_one_contents; ?>);
        <?php
        }
        if(isset($GLOBALS['default_tab_2']))
        {
        ?>
            tab_defaults[1]=<?php echo json_encode($web_root.$GLOBALS['default_tab_2']); ?>;
            app_view_model.application_data.tabs.tabsList()[1].url(tab_defaults[1]);
        <?php
        }
    ?>
    app_view_model.application_data.user(new user_data_view_model(<?php echo json_encode($_SESSION{"authUser"})
                                                                  .',' . json_encode($userQuery['fname'])
                                                                  .',' . json_encode($userQuery['lname'])
                                                                  .',' . json_encode($_SESSION['authGroup']); ?>));
</script>

<style type="text/css">

    #messagesNotification{
        position: relative;
        width: 100%;
        margin: 5px 30px 0 0;
    }

    span.nt-num{
        position: absolute;
        top: -5px;
        right: 20px;
        background-color: red;
        color: white;
        font-weight: bolder;
        padding: 0 5px;
        border: 1px solid red;
        border-radius: 100%;
        font-size: 12px;
    }

</style>
<div id="mainBox">
    <div id="dialogDiv"></div>
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
        <span class="sr-only"><?php echo xlt("Toggle navigation"); ?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="navbar-collapse">
        <div id="menu" data-bind="template: {name: 'menu-template', data: application_data} "></div>
        <div id="userData" data-bind="template: {name: 'user-data-template', data:application_data} "></div>
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

    (function($) {
        $.fn.drags = function(opt) {
          // Min set as 40/200 = 0.2 = 20% size of screen
          opt = $.extend({handle:"",cursor:"ew-resize", min: 40}, opt);

            if(opt.handle === "") {
                var $el = this;
            } else {
                var $el = this.find(opt.handle);
            }

            var priorCursor = $('body').css('cursor');

            return $el.css('cursor', opt.cursor).on("mousedown", function(e) {
                // Activate Frame Barrier!
                $("#frameBarrier").css("visibility", "visible");

                priorCursor = $('body').css('cursor');
                $('body').css('cursor', opt.cursor);

                if(opt.handle === "") {
                    var $drag = $(this).addClass('draggable');
                } else {
                    var $drag = $(this).addClass('active-handle').parent().addClass('draggable');
                }

                var z_idx = $drag.css('z-index'),
                    drg_h = $drag.outerHeight(),
                    drg_w = $drag.outerWidth(),
                    pos_y = $drag.offset().top + drg_h - e.pageY,
                    pos_x = $drag.offset().left + drg_w - e.pageX;

                $drag.css('z-index', 1000).parents().on("mousemove", function(e) {

                    var prev = $('.draggable').prev();
                    var next = $('.draggable').next();

                    if(prev.offset() && next.offset()) {

                        // Assume 50/50 split between prev and next then adjust to
                        // the next X for prev

                        var total = prev.outerWidth() + next.outerWidth();

                        // Each tab has a flex grow of 100 hence their sum must be 200
                        var leftPercentage = (((e.pageX - prev.offset().left) + (pos_x - drg_w / 2)) / total) * 200;
                        var rightPercentage = (200 - leftPercentage);

                        if(leftPercentage < opt.min || rightPercentage < opt.min)  {
                          return;
                        }

                        prev.css('flex', leftPercentage.toString());
                        next.css('flex', rightPercentage.toString());
                    }
                    $(document).on("mouseup", function() {
                        $('body').css('cursor', 'auto');
                        $('.draggable').removeClass('draggable').css('z-index', z_idx);
                        // Deactivate Frame Barrier!
                        $("#frameBarrier").css("visibility", "hidden");
                    });
                });
                e.preventDefault(); // Disable selection
            });

        }
    })(jQuery);
    //calling resize plugin upon
    //1) initial loading -- with 2 tabs only
    $('.handle').drags();
    //2) clicks in main navbar area -- opens a new tab
    $("#navbar-collapse").on("click", function() {
        $('.handle').drags();
    })
    //3) clicks in patient data area -- opens a new tab
    $("#patientData").on("click", function() {
        $('.handle').drags();
    });
  
  
</script>
<?php

   $userAuthorized = $_SESSION['userauthorized'];
   $authUserId = $_SESSION['authUserID'];
   $sql = sqlQuery("SELECT gl_value FROM globals WHERE gl_name = ?", array("updater_icon_visibility") );
    $updater_icon_visibility = $sql['gl_value']; 
    if ($updater_icon_visibility == 1) {
        $show_icon = true;
    }
    else {
        $show_icon = false;
    }
    if(checkAdmin($userAuthorized, $authUserId) && $show_icon) {
        //only admin can use the updater
        echo  "<div id='developer-mode' title='Developer Mode' style='display: none;'><i class='fa fa-code'></i></div>
        <div id='updater-options' style='display: none;' title='updater settings'><i class='fa fa-gear'></i></div><div id='updater-icon'>
                <i class='fa fa-refresh'></i>
                </div>
                <div id='updater-iframe'></div>
                <div id='updater-settings-iframe'></div>
                <div id='developer-mode-iframe'></div>";
    }
?>
<?php do_action( 'after_main_box' ); ?>
<style type="text/css">
    #updater-icon, #updater-options, #developer-mode {
    position: fixed; /* Fixed/sticky position */
    bottom: 20px; /* Place the button at the bottom of the page */
    right: 30px; /* Place the button 30px from the right */
    z-index: 99; /* Make sure it does not overlap */
    border: none; /* Remove borders */
    outline: none; /* Remove outline */
    background-color: #F69600; /* Set a background color */
    color: white; /* Text color */
    cursor: pointer; /* Add a mouse pointer on hover */
    padding: 15px; /* Some padding */
    border-radius: 40px; /* Rounded corners */
    font-size: 18px; /* Increase font size */
    }
    #updater-options, #developer-mode {
        border: 2px solid #000;
        background-color: #F69600;
        padding: 5px; 
        border-radius: 40%;
            bottom: 90px; /* Place the button at the bottom of the page */
        right: 30px; /* Place the button 30px from the right */
    }

    #developer-mode {
        right: 70px;
        font-size: 15px;
        bottom: 70px;
    }
</style>
<script>
$("#updater-settings-iframe").iziModal({
           title: '<i class="fa fa-refresh"></i> <?php echo xlt("Settings for Updater"); ?>',
           subtitle: '<?php echo xlt("Configuration settings for updater"); ?>',
           headerColor: '#F69600',
           closeOnEscape: true,
           fullscreen:true,
           overlayClose: false,
           closeButton: true,
           theme: 'dark',  // light
           iframe: true,
           width:900,
           focusInput: true,
           padding:5,
           iframeHeight: 400,
           iframeURL: "<?php echo $GLOBALS['webroot']; ?>/updater/index.php"
});

$("#developer-mode-iframe").iziModal({
           title: '<i class="fa fa-code"></i> <?php echo xlt("Developer Options"); ?>',
           subtitle: '<?php echo xlt("Developer Mode in the Updater"); ?>',
           headerColor: '#F69600',
           closeOnEscape: true,
           fullscreen:true,
           overlayClose: false,
           closeButton: true,
           theme: 'dark',  // light
           iframe: true,
           width:900,
           focusInput: true,
           padding:5,
           iframeHeight: 400,
           onOpening: function () {
                var imageURL = "../../../updater/updater_loading.gif";
                $('.iziModal-content').css("background-image", "url("+imageURL+")");
           },
           iframeURL: "<?php echo $GLOBALS['webroot']; ?>/updater/developer_mode.php"
});
$("#updater-iframe").iziModal({
           title: '<i class="fa fa-refresh"></i> <?php echo xlt("Updater"); ?>',
           subtitle: '<?php echo xlt("Updater for entire application"); ?>',
           headerColor: '#F69600',
           closeOnEscape: true,
           fullscreen:true,
           overlayClose: false,
           closeButton: true,
           theme: 'dark',  // light
           iframe: true,
           width:900,
           focusInput: true,
           padding:5,
           iframeHeight: 400,
           iframeURL: "<?php echo $GLOBALS['webroot']; ?>/updater/updater.php",
           onOpening: function () {
                var imageURL = "../../../updater/updater_loading.gif";
                $('.iziModal-content').css("background-image", "url("+imageURL+")");
           }
});
$('#developer-mode').click(function () {
    $("#developer-mode-iframe").iziModal('open');
});
$('#updater-options').click(function () {
    $('#updater-settings-iframe').iziModal('open');
});

$('#updater-icon').hover(function () {
    $(this).css("border", "2px solid #000");
    $('#developer-mode').fadeIn(100);
    $('#updater-options').fadeIn(100);
});
$('#updater-icon').click(function () {
    $('#updater-iframe').iziModal('open');
});
$('body').hover(function () {
    $('#developer-mode').css("display", "none");
    $('#updater-options').css("display", "none");
    $(this).css("border", "0px solid #000");
});
function showUpdaterNotifications(type, title, message) {
    if (type == "warning") {
        iziToast.warning({
            title: title,
            message: message,
        });
    }
    if (type == "success") {
        iziToast.success({
            title: title,
            message: message,
        });
    }
    if (type == "info") {
        iziToast.info({
            title: title,
            message: message,
        });
    }
    if (type == "error") {
        iziToast.error({
            title: title,
            message: message,
        });
    }
}
</script>
