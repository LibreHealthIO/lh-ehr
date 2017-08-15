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
$esignApi = new Api();

?>
<!DOCTYPE html>
<title>LibreHealth EHR</title>
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
    /*  Include Bootstrap and Knockout Libraries    */
    call_required_libraries(true,false,true,false);
?>

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
<div id="mainBox">
    <div id="dialogDiv"></div>
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>        
    </div>    
    <div class="collapse navbar-collapse" id="navbar-collapse">
        <div id="menu"  data-bind="template: {name: 'menu-template', data: application_data} "> </div>
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

                        $(document).on("mouseup", function() {
                          $('body').css('cursor', priorCursor);
                            $('.draggable').removeClass('draggable').css('z-index', z_idx);

                            // Deactivate Frame Barrier!
                            $("#frameBarrier").css("visibility", "hidden");
                        });
                    }
                });
                e.preventDefault(); // Disable selection
            });

        }
    })(jQuery);

    $('.handle').drags();

</script>

<?php do_action( 'after_main_box' ); ?>
