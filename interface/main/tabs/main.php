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
    <?php 
        $default_tabs_info = array(
            "/interface/main/main_info.php" => "cal",
            "/interface/main/finder/dynamic_finder.php" => "fin",
            "/interface/new/new_comprehensive.php" => "pat",
            "/interface/patient_tracker/patient_tracker.php?skip_timeout_reset=1" => "flb",
            "/interface/main/messages/messages.php?form_active=1" => "pat"
        );
        
        if(isset($_REQUEST['url']))
        {
            $tab_one_default=$web_root.$GLOBALS['default_tab_1'];
            if($_REQUEST['url']==='TAB_ONE_DEFAULT')
            {
                $tab_one_contents=$tab_one_default;
            }
            else
            {
                /*
                To prevent local file inclusion (LFI) attacks and directory traversals, compare the input to a list of accepted urls.
                The current list of accepted urls (and their required and optional parameters) come from interface/main/main_screen.php.
                */
                $inputURL = $_REQUEST['url'];
                $allowedURLs = array(
                    "pwd_expires_alert.php",
                    "../patient_file/summary/demographics.php" => array(
                        "required" => "set_pid",
                        "optional" => "set_encounterid"
                    ),
                    "../../modules/calendar/index.php" => array(
                        "required" => "pid",
                        "optional" => "date"
                    )
                );
                // regex patterns for query string parameter values
                $queryPatterns = array(
                    "set_pid" => "/^\d+$/",
                    "set_encounterid" => "/^\d+$/",
                    "pid" => "/^\d+$/"
                );

                // check if exact query url is in the accepted list of urls
                if(in_array($inputURL, $allowedURLs)) {
                    $tab_one_contents="../".urldecode($inputURL);
                }
                // otherwise compare each parsed component
                else if(strpos($inputURL, "?")) {
                    // parse input url and extract url param and its query string params
                    $input = $_GET;
                    $inputURLStr = "";
                    $inputParamsList = array();
                    foreach($input as $paramName => $paramVal) {
                        // gather url param and query string param vals
                        if($paramName === "url") {
                            if(strpos($inputURL, "?")) {
                                // separate url param from first query string param
                                $urlParamSplit = explode("?", $paramVal);
                                $inputURLStr = $urlParamSplit[0];
                                // separate name and val of first query string param, then add to list
                                $paramSplit = explode("=", $urlParamSplit[1]);
                                $inputParamsList[$paramSplit[0]] = $paramSplit[1];
                            } else {
                                // gather url param
                                $inputURLStr = $paramVal;
                            }
                        } else {
                            // add name and val of query string param to list
                            $inputParamsList[$paramName] = $paramVal;
                            // rebuild url since $_REQUEST['url'] doesn't pull any other params after the first one
                            $inputURL .= "&".$paramName."=".$paramVal;
                        }
                    }

                    // check if each parsed component of input url is in the accepted list of urls
                    $validURLCheck = true;
                    if(array_key_exists($inputURLStr, $allowedURLs)) {
                        $paramsList = $allowedURLs[$inputURLStr];
                        // make sure input url include required params
                        if(!array_key_exists($paramsList["required"], $inputParamsList)) {
                            $validURLCheck = false;
                        } else {
                            foreach($inputParamsList as $inputParamName => $inputParamVal) {
                                // validate param names
                                if(in_array($inputParamName, $paramsList)) {
                                    // validate param vals
                                    if($inputParamName === "date") {
                                        // date format: YYYYMMDD
                                        $year = substr($inputParamVal, 0, 4);
                                        $month = substr($inputParamVal, 4, 2);
                                        $day = substr($inputParamVal, 6, 2);
                                        if(strlen($inputParamVal) != 8 || !checkdate($month, $day, $year)) {
                                            $validURLCheck = false;
                                            break;
                                        }
                                    } else if(!preg_match($queryPatterns[$inputParamName], $inputParamVal)) {
                                        $validURLCheck = false;
                                        break;
                                    }
                                } else {
                                    $validURLCheck = false;
                                    break;
                                }
                            }
                        }
                    }
                    if($validURLCheck) {
                        $tab_one_contents="../".urldecode($inputURL);
                    } else {
                        $tab_one_contents=urldecode("404.php");
                    }
                // url is not in the accepted list of urls && does not include "?"
                } else {
                    $tab_one_contents=urldecode("404.php");
                }
            }
            $tab_one_contents=json_encode($tab_one_contents);
            $tab_one_default=json_encode($tab_one_default);
        ?>
            tab_defaults=[];
            tab_defaults[0]=<?php echo $tab_one_default; ?>;
            app_view_model.application_data.tabs.tabsList()[0].url(<?php echo $tab_one_contents; ?>);
            app_view_model.application_data.tabs.tabsList()[0].name(<?php echo json_encode($default_tabs_info[$GLOBALS['default_tab_1']]); ?>);
        <?php
        }
        if(isset($GLOBALS['default_tab_2']))
        {
        ?>
            tab_defaults[1]=<?php echo json_encode($web_root.$GLOBALS['default_tab_2']); ?>;
            app_view_model.application_data.tabs.tabsList()[1].url(tab_defaults[1]);
            app_view_model.application_data.tabs.tabsList()[1].name(<?php echo json_encode($default_tabs_info[$GLOBALS['default_tab_2']]); ?>);
        <?php
        }
    ?>
    app_view_model.application_data.user(new user_data_view_model(<?php echo json_encode($_SESSION{"authUser"})
                                                                  .',' . json_encode($userQuery['fname'])
                                                                  .',' . json_encode($userQuery['lname'])
                                                                  .',' . json_encode($_SESSION['authGroup']); ?>));
    // Set the csrf token used in js/tabs_view_model.js script
    var jsCsrfToken = <?php echo $_SESSION['token'];?>;
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
        <div id='updater-options' style='display: none;' title='updater settings'><i class='fa fa-gear'></i></div><div id='updater-icon-wrapper'><div id='updater-icon'>
                <i class='fa fa-refresh'></i>
                </div></div>
                <div id='updater-iframe'></div>
                <div id='updater-settings-iframe'></div>
                <div id='developer-mode-iframe'></div>";
    }
?>
<?php do_action( 'after_main_box' ); ?>
<style type="text/css">
    #updater-icon, #updater-options, #developer-mode {
    border: none; /* Remove borders */
    outline: none; /* Remove outline */
    background-color: #F69600; /* Set a background color */
    color: white; /* Text color */
    cursor: pointer; /* Add a mouse pointer on hover */
    padding: 15px; /* Some padding */
    border-radius: 40px; /* Rounded corners */
    font-size: 18px; /* Increase font size */
    }
    #updater-icon-wrapper{
        position: fixed;
        padding-bottom: 20px; /* Place the button at the bottom of the page */
        padding-right: 30px; /* Place the button 30px from the right */
        z-index: 98; /* Make sure it does not overlap */
        padding-top: 50px;
        padding-left: 50px;
        right: 0;
        bottom: 0;
    }
    #updater-options, #developer-mode { 
        position: fixed; /* Fixed/sticky position */
        border: 2px solid #000;
        background-color: #F69600;
        padding: 5px; 
        border-radius: 40%;
        bottom: 90px; /* Place the button at the bottom of the page */
        right: 30px; /* Place the button 30px from the right */
        z-index: 99;
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
var timer;
var secondtimer;
$('#updater-icon').hover(function () {
    $(this).css("border", "2px solid #000");
    $('#developer-mode').fadeIn(100);
    $('#updater-options').fadeIn(100);
}, function(){
    timer = setTimeout(function(){
        $('#developer-mode').fadeOut(100);
        $('#updater-options').fadeOut(100);
        $('#updater-icon').css("border", "none");
    }, 3000);
});
$('#updater-icon-wrapper').hover(function(){}, function(){
    secondTimer = setTimeout(function(){
        $('#developer-mode').fadeOut(100);
        $('#updater-options').fadeOut(100);
        $('#updater-icon').css("border", "none");
    }, 100);
})
$('#developer-mode').hover(function () {
    clearTimeout(timer);
    clearTimeout(secondTimer);
});
$('#updater-options').hover(function () {
    clearTimeout(timer);
    clearTimeout(secondTimer);
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

