<?php
/**
 *  Patient Tracker (Patient Flow Board)
 *
 *  This program displays the information entered in the Calendar program ,
 *  allowing the user to change status and view those changed here and in the Calendar
 *  Will allow the collection of length of time spent in each status
 *
 * Copyright (C) 2015-2017  Terry Hill <teryhill@librehealth.io>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package Librehealth EHR
 * @author Terry Hill <teryhill@librehealth.io>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/patient_tracker.inc.php");
require_once("$srcdir/user.inc");
require_once("$srcdir/headers.inc.php");

$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

if (!is_null($_POST['form_provider']) && ($GLOBALS['docs_see_entire_calendar'] =='1' || $_SESSION['userauthorized'] =='0' )) {
  $provider = $_POST['form_provider'];
}
else if ($_SESSION['userauthorized']) {
  $provider = $_SESSION['authUserID'];
}
else if ($GLOBALS['docs_see_entire_calendar'] =='1') {
  $provider = null;
}

if (substr($GLOBALS['ptkr_end_date'],0,1) == 'Y') {
   $ptkr_time = substr($GLOBALS['ptkr_end_date'],1,1);
   $ptkr_future_time = mktime(0,0,0,date('m'),date('d'),date('Y')+$ptkr_time);
}
elseif (substr($GLOBALS['ptkr_end_date'],0,1) == 'M') {
   $ptkr_time = substr($GLOBALS['ptkr_end_date'],1,1);
   $ptkr_future_time = mktime(0,0,0,date('m')+$ptkr_time ,date('d'),date('Y'));
}
elseif (substr($GLOBALS['ptkr_end_date'],0,1) == 'W') {
   $ptkr_time = substr($GLOBALS['ptkr_end_date'],1,1) * 7;
   $ptkr_future_time = mktime(0,0,0,date('m') ,date('d')+$ptkr_time,date('Y'));
}
elseif (substr($GLOBALS['ptkr_end_date'],0,1) == 'D') {
   $ptkr_time = substr($GLOBALS['ptkr_end_date'],1,1);
   $ptkr_future_time = mktime(0,0,0,date('m') ,date('d')+$ptkr_time,date('Y'));
}

$form_to_date = date($DateFormat, $ptkr_future_time);
# This needs some more thought.
if ($GLOBALS['status_default']) {
    $stat_default = substr($GLOBALS['status_default'],0,1);
}
If ($GLOBALS['status_default'] == 'All') {
    $stat_default = null;
}
$facility  = !is_null($_POST['form_facility']) ? $_POST['form_facility'] : null;
$form_apptstatus = !is_null($_POST['form_apptstatus']) ? $_POST['form_apptstatus'] : $stat_default;
$form_apptcat=null;
$form_from_date = !is_null($_POST['form_from_date']) ? $_POST['form_from_date'] : date($DateFormat);
if($GLOBALS['ptkr_date_range']) {
   $form_to_date = !is_null($_POST['form_to_date']) ? $_POST['form_to_date'] : $form_to_date;
} else {
   $form_to_date = !is_null($_POST['form_from_date']) ? $_POST['form_from_date'] : date($DateFormat);
}
if(isset($_POST['form_apptcat']))
{
    if($form_apptcat!="ALL")
    {
        $form_apptcat=intval($_POST['form_apptcat']);
    }
}

$appointments = array();
#define variables, allow changing the to_date and from_date
#to allow picking a date to review
$from_date = DateToYYYYMMDD($form_from_date);
$to_date = DateToYYYYMMDD($form_to_date);
$datetime = date("Y-m-d H:i:s");
# go get the information and process it
$appointments = fetch_Patient_Tracker_Events($from_date, $to_date, $provider, $facility, $form_apptstatus, $form_apptcat);
$appointments = sortAppointments( $appointments,'date','time' );  // TODO - TIME IS NOT A PARAMETER for this function

//grouping of the count of every status
$appointments_status = getApptStatus($appointments);


$lres = sqlStatement("SELECT option_id, title FROM list_options WHERE list_id = ? AND activity=1", array('apptstat'));
while ( $lrow = sqlFetchArray ( $lres ) ) {
    // if exists, remove the legend character
    if($lrow['title'][1] == ' '){
        $splitTitle = explode(' ', $lrow['title']);
        array_shift($splitTitle);
        $title = implode(' ', $splitTitle);
    }else{
        $title = $lrow['title'];
    }

    $statuses_list[$lrow['option_id']] = $title;
}

$chk_prov = array();  // list of providers with appointments
// Scan appointments for additional info
foreach ( $appointments as $apt ) {
  $chk_prov[$apt['uprovider_id']] = $apt['ulname'] . ', ' . $apt['ufname'] . ' ' . $apt['umname'];
}

?>
<html>
<head>
<style>
    /*  Removing all borders from tables */
    .table th, .table td {
     border-top: none !important;
    }
    span.glyphicon-cog {
    font-size: 1.4em;
    margin-left : 20px;
}
    .glyphicon:hover{
        cursor: pointer;
    }
}
</style>

<title><?php echo xlt("Flow Board") ?></title>
<?php
    //  Include Bootstrap and DateTimePicker
  call_required_libraries(array("jquery-min-3-1-1","bootstrap","datepicker"));
?>

<script type="text/javascript" src="../../library/js/common.js"></script>
<script type="text/javascript" src="../../library/js/blink/jquery.modern-blink.js"></script>
<script language="JavaScript">
// Refresh self
function refreshme() {
  top.restoreSession();
  document.pattrk.submit();
}

// popup for patient tracker status
function bpopup(tkid) {
 top.restoreSession();
 window.open('../patient_tracker/patient_tracker_status.php?tracker_id=' + tkid ,'_blank', 'width=500,height=250,resizable=1');
 return false;
}

// popup for calendar add edit
function calendarpopup(eid,date_squash) {
 top.restoreSession();
 window.open('<?php echo $GLOBALS["web_root"]; ?>/modules/calendar/add_edit_event.php?eid=' + eid + '&date=' + date_squash,'_blank', 'width=775,height=400,resizable=1');
 return false;
}

// auto refresh screen pat_trkr_timer is the timer variable AND IF settings aren't opened
function refreshbegin(first){
  <?php if ($GLOBALS['pat_trkr_timer'] != '0') { ?>
    var reftime="<?php echo attr($GLOBALS['pat_trkr_timer']); ?>";
    var parsetime=reftime.split(":");
    parsetime=(parsetime[0]*60)+(parsetime[1]*1)*1000;

    // contingency for fullscreen mode. settings toggle does not exist
    var pat_settings_toggle = document.getElementById("pat_settings_toggle");
    if(pat_settings_toggle == null) {
        var expanded = null;
    } else {
        var expanded = document.getElementById("pat_settings_toggle").getAttribute('aria-expanded');
    }




    // expanded variable is the status of the pat_settings div. if it is opened, this variable will evaluate to true
    // otherwise, false. this can be used to check if options are opened
    if (first != '1' && (expanded == "false" || expanded == null)) {
      console.log("Refreshing!");
      refreshme();
    }
    setTimeout("refreshbegin('0')",parsetime);
  <?php } ?>
}

// used to display the patient demographic and encounter screens
function topatient(newpid, enc) {
 
   top.restoreSession();
     if (enc > 0) {
       top.RTop.location= "<?php echo $GLOBALS['webroot']; ?>/interface/patient_file/summary/demographics.php?set_pid=" + newpid + "&set_encounterid=" + enc;
     }
     else {
       top.RTop.location = "<?php echo $GLOBALS['webroot']; ?>/interface/patient_file/summary/demographics.php?set_pid=" + newpid;
     }
}


</script>

</head>


<?php
    if ($GLOBALS['pat_trkr_timer'] == '0') {
      // if the screen is not set up for auto refresh, use standard page call
      $action_page = "patient_tracker.php";
    }
    else {
      // if the screen is set up for auto refresh, this will allow it to be closed by auto logoff
      $action_page = "patient_tracker.php?skip_timeout_reset=1";
    }
?>
<span class="title"><?php echo xlt("Flow Board") ?></span>
<span class="glyphicon glyphicon-cog" data-toggle="collapse" title="Set Flowboard Preferences" href="#pat_settings" id="pat_settings_toggle" aria-expanded="false"></span>
<body class="body_top" >
<div id="pat_settings" class="well collapse">
    <form method='post' name='pt_settings' id="pt_settings" action='<?php echo $action_page; ?>'>

        <div class="checkbox">
        <label><input type="checkbox" name="ptkr_visit_reason" value="1" <?php if($GLOBALS['ptkr_visit_reason']=='1') echo "checked"; ?>><?php echo xlt("Show Visit Reason in Patient Flow Board"); ?></label>
        </div>
        <div class="checkbox">
        <label><input type="checkbox" name="ptkr_show_pid" value="1" <?php if($GLOBALS['ptkr_show_pid']=='1') echo "checked"; ?>><?php echo xlt("Show Patient ID in Patient Flow Board"); ?></label>
        </div>
        <div class="checkbox">
        <label><input type="checkbox" name="ptkr_show_room" value="1" <?php if($GLOBALS['ptkr_show_room']=='1') echo "checked"; ?>><?php echo xlt("Show Exam Room Patient Flow Board"); ?></label>
        </div>
        <div class="checkbox">
        <label><input type="checkbox" name="ptkr_show_visit_type" value="1" <?php if($GLOBALS['ptkr_show_visit_type']=='1') echo "checked"; ?>><?php echo xlt("Show Visit Type in Patient Flow Board"); ?></label>
        </div>
        <div class="checkbox">
        <label><input type="checkbox" name="ptkr_show_encounter" value="1" <?php if($GLOBALS['ptkr_show_encounter']=='1') echo "checked"; ?>><?php echo xlt("Show Patient Encounter Number in Patient Flow Board"); ?></label>
        </div>
        <div class="checkbox">
        <label><input type="checkbox" name="ptkr_flag_dblbook" value="1" <?php if($GLOBALS['ptkr_flag_dblbook']=='1') echo "checked"; ?>><?php echo xlt("Flag Double Booked Appt in Flow Board"); ?></label>
        </div>
        <div class="checkbox">
        <label><input type="checkbox" name="ptkr_date_range" value="1" <?php if($GLOBALS['ptkr_date_range']=='1') echo "checked"; ?>><?php echo xlt("Allow Date Range in Patient Flow Board"); ?></label>
        </div>
        <?php echo xlt("Ending Date for Patient Flow Board"); ?>
        <select class="form-control input-sm" name="ptkr_end_date" id="ptkr_end_date">
            <option value="Y1" <?php if($GLOBALS['ptkr_end_date']=='Y1') echo "selected";?>><?php echo xlt("One Year Ahead"); ?></option>
            <option value="Y2" <?php if($GLOBALS['ptkr_end_date']=='Y2') echo "selected";?>><?php echo xlt("Two Years Ahead"); ?></option>
            <option value="M6" <?php if($GLOBALS['ptkr_end_date']=='M6') echo "selected";?>><?php echo xlt("Six Months Ahead"); ?></option>
            <option value="M3" <?php if($GLOBALS['ptkr_end_date']=='M3') echo "selected";?>><?php echo xlt("Three Months Ahead"); ?>Three Months Ahead</option>
            <option value="M1" <?php if($GLOBALS['ptkr_end_date']=='M1') echo "selected";?>><?php echo xlt("One Month Ahead"); ?></option>
            <option value="D1" <?php if($GLOBALS['ptkr_end_date']=='D1') echo "selected";?>><?php echo xlt("One Day Ahead"); ?></option>
        </select>
        <?php echo xlt("Patient Flow Board Timer Interval"); ?>
        <select class="form-control input-sm" name="pat_trkr_timer" id="pat_trkr_timer">
            <option value="0" <?php if($GLOBALS['pat_trkr_timer']=='0') echo "selected";?>><?php echo xlt("No automatic refresh"); ?></option>
            <option value="0:10" <?php if($GLOBALS['pat_trkr_timer']=='0:10') echo "selected";?>>10</option>
            <option value="0:20" <?php if($GLOBALS['pat_trkr_timer']=='0:20') echo "selected";?>>20</option>
            <option value="0:30" <?php if($GLOBALS['pat_trkr_timer']=='0:30') echo "selected";?>>30</option>
            <option value="0:40" <?php if($GLOBALS['pat_trkr_timer']=='0:40') echo "selected";?>>40</option>
            <option value="0:50"> <?php if($GLOBALS['pat_trkr_timer']=='0:50') echo "selected";?>50</option>
            <option value="0:59" <?php if($GLOBALS['pat_trkr_timer']=='0:59') echo "selected";?>>60</option>
        </select>

        <div>
        <br>
        <input type='submit'  name='user_save' value='<?php echo xla('Save'); ?>' class="cp-submit"/>
        </div>
    </form>
</div>

<?php
    if(isset($_POST['user_save'])){
        $setting_names = [
            'ptkr_visit_reason',
            'ptkr_show_pid',
            'ptkr_show_room',
            'ptkr_show_visit_type',
            'ptkr_show_encounter',
            'ptkr_flag_dblbook',
            'ptkr_date_range',
            'ptkr_end_date',
            'pat_trkr_timer'
        ];
        foreach($setting_names as $setting)
        {
            if(isset($_POST[$setting]))
                setUserSetting('global:'.$setting,$_POST[$setting],$_SESSION['authId'],FALSE);
            else
                setUserSetting('global:'.$setting,"",$_SESSION['authId'],FALSE);
        }
        echo "<script type='text/javascript'>";
        echo "self.location.href='patient_tracker.php?skip_timeout_reset=1';";
        echo "</script>";
        }
    ?>
<form method='post' name='theform' id='theform' action='<?php echo $action_page; ?>' onsubmit='return top.restoreSession()'>
    <div class="table-responsive">
        <table class="table well">
            <tr>
                <td><?php echo xlt('Provider'); ?>:
                <?php

                    # Build a drop-down list of providers.
                    $uid = $_SESSION['authUserID'];
                    if ($GLOBALS['docs_see_entire_calendar'] =='1' || $_SESSION['userauthorized'] =='0') {
                      $query = "SELECT id, lname, fname FROM users WHERE ".
                        "authorized = 1  ORDER BY lname, fname";
                    }else{

                    $query = "SELECT id, lname, fname FROM users WHERE ".
                        "authorized = 1 AND id = $uid ORDER BY lname, fname";
                    }

                    $ures = sqlStatement($query);

                    echo "   <select name='form_provider' class='form-control input-sm'>\n";
                    if ($GLOBALS['docs_see_entire_calendar'] =='1' || $_SESSION['userauthorized'] =='0') {
                    echo "    <option value='ALL'>-- " . xlt('All') . " --\n";
                    }

                    while ($urow = sqlFetchArray($ures)) {
                        $provid = $urow['id'];
                        echo "    <option value='" . attr($provid) . "'";
                        if (isset($_POST['form_provider']) && $provid == $_POST['form_provider']){
                            echo " selected";
                        } elseif(!isset($_POST['form_provider'])&& $_SESSION['userauthorized'] && $provid == $_SESSION['authUserID']){
                            echo " selected";
                        }
                        echo ">" . text($urow['lname']) . ", " . text($urow['fname']) . "\n";
                    }

                    echo "   </select>\n";

                    ?>
                </td>
                <td><?php echo xlt('Status'); # status code drop down creation ?>:
                <?php generate_form_field(array('data_type'=>1,'field_id'=>'apptstatus','list_id'=>'apptstat','empty_title'=>'All'),$form_apptstatus);
                ?></td>
                <?php if ($GLOBALS['ptkr_show_visit_type']) { ?>
                <td>
                <?php echo xlt('Category') #category drop down creation ?>:

                        <select id="form_apptcat" name="form_apptcat" class="form-control input-sm">
                            <?php
                            $categories=fetchAppointmentCategories();
                            echo "<option value='ALL'>".xlt("All")."</option>";
                            while($cat=sqlFetchArray($categories))
                            {
                                echo "<option value='".attr($cat['id'])."'";
                                if($cat['id']==$_POST['form_apptcat'])
                                {
                                    echo " selected='true' ";
                                }
                                echo    ">".text(xl_appt_category($cat['category']))."</option>";
                            }
                            ?>
                        </select>
                    </td>
                <?php } ?>

                <td><?php if($GLOBALS['ptkr_date_range']) { echo xlt('From'); } else { echo xlt('Date'); }?>:
                <input type='text' class="form-control input-sm" size='9' name='form_from_date' id="form_from_date"
                           value='<?php echo (attr($form_from_date)) ?>'>
                </td>
                <?php if($GLOBALS['ptkr_date_range']) { ?>
                <td><?php echo xlt('To'); ?>:
                <input type='text' class="form-control input-sm" size='9' name='form_to_date' id="form_to_date"
                           value='<?php echo (attr($form_to_date)) ?>'>
                </td>
                <?php } ?>
            </tr>
                <tr>
                    <td>
                        <a href='#' class='css_button cp-submit' onclick='$("#form_refresh").attr("value","true"); $("#theform").submit();'>
                            <span> <?php echo xlt('Submit'); ?> </span> </a>
                        <?php if ($_POST['form_refresh'] || $_POST['form_orderby'] ) { ?>
                            <a href='#' class='css_button' id='printbutton'>
                                <span> <?php echo xlt('Print'); ?> </span> </a>
                        <?php } ?>
                    </td>
                </tr>
        </table>
    </div>
</form>
<form name='pattrk' id='pattrk' method='post' action='<?php echo $action_page; ?>' onsubmit='return top.restoreSession()' enctype='multipart/form-data'>

<div id="flowboard_header">
  <?php if (count($chk_prov) == 1) {?>
    <?php if($GLOBALS['ptkr_date_range']) { ?>
      <h3><?php echo xlt('Appointments for') . ' : '. text(reset($chk_prov)) . ' ' . ' : '. xlt('Date Range') . ' ' . text($form_from_date) . ' ' . xlt('to'). ' ' . text($form_to_date) ?></h3>
    <?php } else { ?>
      <h3><?php echo xlt('Appointments for'). ' : '. text(reset($chk_prov)) . ' : '. xlt('Date') . ' ' . text($form_from_date) ?></h3>
    <?php } ?>
  <?php } else { ?>
    <?php if($GLOBALS['ptkr_date_range']) { ?>
      <h3><?php echo xlt('Appointments Date Range'). ' : ' . text($form_from_date) . ' ' . xlt('to'). ' ' . text($form_to_date) ?></h3>
  <?php } else { ?>
      <h3><?php echo xlt('Appointment Date'). ' : ' . text($form_from_date) ?></h3>
  <?php } ?>
  <?php } ?>
 <div id= 'inanewwindow' class='inanewwindow'>
 </div>
<?php if ($GLOBALS['pat_trkr_timer'] =='0') { ?>
<table class="table">
 <tr>
  <td  align='center'><br>
   <a href='javascript:;' class='css_button_small' align='center' style='color:gray' onclick="document.getElementById('pattrk').submit();"><span><?php echo xlt('Refresh Screen'); ?></span></a>
   </td>
 </tr>
</table>
<?php } ?>

<div class="table-responsive">
<table class="table">
 <tr>
    <b><small>
        <?php
        $statuses_output =  xlt('Total patients')  . ':' . text($appointments_status['count_all']);
        unset($appointments_status['count_all']);
        foreach($appointments_status as $status_symbol => $count){
            $statuses_output .= " | " . text(xl_list_label($statuses_list[$status_symbol]))  .":" . $count;
        }
        echo $statuses_output;
        ?>
    </small></b>
 </tr>

 <tr bgcolor="#cccff">
  <?php if ($GLOBALS['ptkr_show_pid']) { ?>
   <td>
   <?php  echo xlt('PID'); ?>
  </td>
  <?php } ?>
  <td>
   <?php  echo xlt('Patient'); ?>
  </td>
  <?php if ($GLOBALS['ptkr_visit_reason']) { ?>
  <td>
   <?php  echo xlt('Reason'); ?>
  </td>
  <?php } ?>
  <?php if ($GLOBALS['ptkr_show_encounter']) { ?>
  <td>
   <?php  echo xlt('Encounter'); ?>
  </td>
  <?php } ?>
  <?php if ($GLOBALS['ptkr_show_room']) { ?>
  <td>
   <?php  echo xlt('Exam Room #'); ?>
  </td>
  <?php } ?>
  <?php if($GLOBALS['ptkr_date_range']) { ?>
  <td>
   <?php  echo xlt('Appt Date'); ?>
  </td>
  <?php } ?>
  <td>
   <?php  echo xlt('Appt Time'); ?>
  </td>
  <td>
   <?php  echo xlt('Arrive Time'); ?>
  </td>
  <td>
   <?php  echo xlt('Status'); ?>
  </td>
  <td>
   <?php  echo xlt('Current Status Time'); ?>
  </td>
   <?php if ($GLOBALS['ptkr_show_visit_type']) { ?>
   <td>
   <?php  echo xlt('Category'); ?>
  </td>
  <?php } ?>
  <?php if (count($chk_prov) > 1) { ?>
  <td>
   <?php  echo xlt('Provider'); ?>
  </td>
  <?php } ?>
 <td>
   <?php  echo xlt('Total Time'); ?>
  </td>
 <td>
   <?php  echo xlt('Check Out Time'); ?>
  </td>
   <td>
   <?php  echo xlt('Updated By'); ?>
  </td>
 <?php if ($GLOBALS['drug_screen']) { ?>
  <td>
   <?php  echo xlt('Random Drug Screen'); ?>
  </td>
  <td>
   <?php  echo xlt('Drug Screen Completed'); ?>
  </td>
 <?php } ?>
 </tr>

<?php
    $prev_appt_date_time = "";

    $overbookStatuses = array_map('trim', explode( ',', $GLOBALS['appt_overbook_statuses'] ) );
  foreach ( $appointments as $appointment ) {

                # Collect appt date and set up squashed date for use below
                $date_appt = $appointment['pc_eventDate'];
                $date_squash = str_replace("-","",$date_appt);

                # Collect variables and do some processing
                $docname  = $chk_prov[$appointment['uprovider_id']];
                if (strlen($docname)<= 3 ) continue;
                $ptname = $appointment['lname'] . ', ' . $appointment['fname'] . ' ' . $appointment['mname'];
                $appt_enc = $appointment['encounter'];
                $appt_eid = (!empty($appointment['eid'])) ? $appointment['eid'] : $appointment['pc_eid'];
                $appt_pid = (!empty($appointment['pid'])) ? $appointment['pid'] : $appointment['pc_pid'];
                if ($appt_pid ==0 ) continue; // skip when $appt_pid = 0, since this means it is not a patient specific appt slot
                $status = (!empty($appointment['status'])) ? $appointment['status'] : $appointment['pc_apptstatus'];
                $appt_room = (!empty($appointment['room'])) ? $appointment['room'] : $appointment['pc_room'];
                $appt_time = (!empty($appointment['appttime'])) ? $appointment['appttime'] : $appointment['pc_startTime'];
                if ( in_array( $status, $overbookStatuses )) {
                    $appt_canceled = true;
                } else {
                    $appt_canceled = false;
                }
                $appt_date_time = $date_appt .' '. $appt_time;  // used to find flag double booked
                $tracker_id = $appointment['id'];
                # reason for visit
                if ($GLOBALS['ptkr_visit_reason']) {
                  $reason_visit = $appointment['pc_hometext'];
                }
                $newarrive = collect_checkin($tracker_id);
                $newend = collect_checkout($tracker_id);
                $colorevents = (collectApptStatusSettings($status));
                $bgcolor = $colorevents['color'];
                $statalert = $colorevents['time_alert'];
                # process the time to allow items with a check out status to be displayed
                if ( is_checkout($status) && (($GLOBALS['checkout_roll_off'] > 0) && strlen($form_apptstatus) != 1 )  ) {
                        $to_time = strtotime($newend);
                        $from_time = strtotime($datetime);
                        $display_check_out = round(abs($from_time - $to_time) / 60,0);
                        if ( $display_check_out >= $GLOBALS['checkout_roll_off'] ) continue;
                }
?>
        <tr bgcolor='<?php echo $bgcolor ?>'>
        <?php if ($GLOBALS['ptkr_show_pid']) { ?>
        <td class="detail">
        <?php echo text($appt_pid) ?>
         </td>
        <?php } ?>
        <td class="detail">
        <a href="#" onclick="return topatient('<?php echo attr($appt_pid);?>','<?php echo attr($appt_enc);?>')" >
        <?php echo text($ptname); ?></a>
         </td>
         <!-- reason -->
         <?php if ($GLOBALS['ptkr_visit_reason']) { ?>
         <td class="detail">
         <?php echo text($reason_visit) ?>
         </td>
         <?php } ?>
         <?php if ($GLOBALS['ptkr_show_encounter']) { ?>
         <td class="detail">
         <?php if($appt_enc != 0) echo text($appt_enc); ?></a>
         </td>
         <?php } ?>
         <?php if ($GLOBALS['ptkr_show_room']) { ?>
         <td class="detail">
         <?php echo getListItemTitle('patient_flow_board_rooms', $appt_room);?>
         </td>
         <?php } ?>
         <?php if($GLOBALS['ptkr_date_range']) { ?>
         <td class="detail">
         <?php echo oeFormatShortDate($date_appt) ?>
         </td>
         <?php }
            // flag possible double booked, ignoring canceled appt types
            //  and skipping of ALL providers are viewed
            $apptflagtd = '<td class="detail">';
            if ((count($chk_prov) == 1 ) and $appt_canceled != true) {
                if ($GLOBALS['ptkr_flag_dblbook']) {
                    if ($appt_date_time === $prev_appt_date_time) {
                        $apptflagtd = '<td class="detail" bgcolor="orange">';  //TODO Make color configurable
                    }
                    $prev_appt_date_time = $appt_date_time;
                }
            }
         echo $apptflagtd;  // <TD ..>
            echo oeFormatTime($appt_time);
         ?>
         </td>
         <td class="detail">
        <?php echo ($newarrive ? oeFormatTime($newarrive) : '&nbsp;') ?>
         </td>
         <td class="detail">
         <?php if (empty($tracker_id)) { #for appt not yet with tracker id and for recurring appt ?>
           <a href=""  onclick="return calendarpopup(<?php echo attr($appt_eid).",".attr($date_squash); # calls popup for add edit calendar event?>)">
         <?php } else { ?>
           <a href=""  onclick="return bpopup(<?php echo attr($tracker_id); # calls popup for patient tracker status?>)">
         <?php } ?>
         <?php echo text(getListItemTitle("apptstat",$status)); # drop down list for appointment status?>
         </a>

         </td>
        <?php
         #time in current status
         $to_time = strtotime(date("Y-m-d H:i:s"));
         $yestime = '0';
         if (strtotime($newend) != '') {
            $from_time = strtotime($newarrive);
            $to_time = strtotime($newend);
            $yestime = '0';
         }
         else
        {
            $from_time = strtotime($appointment['start_datetime']);
            $yestime = '1';
        }

        $timecheck = round(abs($to_time - $from_time) / 60,0);
        if ($timecheck >= $statalert && ($statalert != '0')) { # Determine if the time in status limit has been reached.
           echo "<td align='center' class='js-blink-infinite'>  "; # and if so blink
        }
        else
        {
           echo "<td align='center' class='detail'> "; # and if not do not blink
        }
        if (($yestime == '1') && ($timecheck >=1) && (strtotime($newarrive)!= '')) {
           echo text($timecheck . ' ' .($timecheck >=2 ? xl('minutes'): xl('minute')));
        }
        #end time in current status
        ?>
         <?php if ($GLOBALS['ptkr_show_visit_type']) { ?>
         <td class="detail">
         <?php echo text(xl_appt_category($appointment['pc_title'])) ?>
         </td>
         <?php } ?>
         <?php if (count($chk_prov) > 1) { ?>
         <td class="detail">
         <?php echo text($docname); ?>
         </td>
         <?php } ?>
         <td class="detail">
         <?php

         # total time in practice
         if (strtotime($newend) != '') {
            $from_time = strtotime($newarrive);
            $to_time = strtotime($newend);
         }
         else
         {
            $from_time = strtotime($newarrive);
            $to_time = strtotime(date("Y-m-d H:i:s"));
         }
         $timecheck2 = round(abs($to_time - $from_time) / 60,0);
         if (strtotime($newarrive) != '' && ($timecheck2 >=1)) {
            echo text($timecheck2 . ' ' .($timecheck2 >=2 ? xl('minutes'): xl('minute')));
         }
         # end total time in practice
        ?>
        <?php echo text($appointment['pc_time']); ?>
         </td>
        <td class="detail">
         <?php
         if (strtotime($newend) != '') {
            echo oeFormatTime($newend) ;
         }
         ?>
         </td>
         <td class="detail">
         <?php echo text($appointment['user']) ?>
         </td>
         <?php if ($GLOBALS['drug_screen']) { ?>
         <?php if (strtotime($newarrive) != '') { ?>
         <td class="detail">
         <?php if (text($appointment['random_drug_test']) == '1') {  echo xl('Yes'); }  else { echo xl('No'); }?>
         </td>
         <?php } else {  echo "  <td>"; }?>
         <?php if (strtotime($newarrive) != '' && $appointment['random_drug_test'] == '1') { ?>
         <td class="detail">
         <?php if (strtotime($newend) != '') { # the following block allows the check box for drug screens to be disabled once the status is check out ?>
             <input type=checkbox  disabled='disable' class="drug_screen_completed" id="<?php echo htmlspecialchars($appointment['pt_tracker_id'], ENT_NOQUOTES) ?>"  <?php if ($appointment['drug_screen_completed'] == "1") echo "checked";?>>
         <?php } else { ?>
             <input type=checkbox  class="drug_screen_completed" id='<?php echo htmlspecialchars($appointment['pt_tracker_id'], ENT_NOQUOTES) ?>' name="drug_screen_completed" <?php if ($appointment['drug_screen_completed'] == "1") echo "checked";?>>
         <?php } ?>
         </td>
         <?php } else {  echo "  <td>"; }?>
         <?php } ?>
         </tr>
        <?php
    } //end for
?>

<?php
//saving the filter for auto refresh
if(!is_null($_POST['form_provider']) ){
    echo "<input type='hidden' name='form_provider' value='" . attr($_POST['form_provider']) . "'>";
}
if(!is_null($_POST['form_facility']) ){
    echo "<input type='hidden' name='form_facility' value='" . attr($_POST['form_facility']) . "'>";
}
if(!is_null($_POST['form_apptstatus']) ){
    echo "<input type='hidden' name='form_apptstatus' value='" . attr($_POST['form_apptstatus']) . "'>";
}
if(!is_null($_POST['form_apptcat']) ){
    echo "<input type='hidden' name='form_apptcat' value='" . attr($_POST['form_apptcat']) . "'>";
}
if(!is_null($_POST['form_from_date']) ){
    echo "<input type='hidden' name='form_from_date' value='" . attr($_POST['form_from_date']) . "'>";
}
if(!is_null($_POST['form_to_date']) ){
    echo "<input type='hidden' name='form_to_date' value='" . attr($_POST['form_to_date']) . "'>";
}
?>

</table>
</div>
</form>


<script type="text/javascript">
  $(document).ready(function() {
      $('#settings').css("display","none");
      refreshbegin('1');
      $('.js-blink-infinite').modernBlink();


  // check whether this is a fullscreen page or not
  if(parent.fullscreen_page == true) {
      $("#pat_settings_toggle").remove();
  }
  // toggle of the check box status for drug screen completed and ajax call to update the database
 $(".drug_screen_completed").change(function() {
      top.restoreSession();
    if (this.checked) {
      testcomplete_toggle="true";
    } else {
      testcomplete_toggle="false";
    }
      $.post( "../../library/ajax/drug_screen_completed.php", {
        trackerid: this.id,
        testcomplete: testcomplete_toggle
      });
    });
  });
</script>
<!-- form used to open a new top level window when a patient row is clicked -->
<form name='fnew' method='post' target='_blank' action='../main/main_screen.php?auth=login&site=<?php echo attr($_SESSION['site_id']); ?>'>
<input type='hidden' name='patientID'      value='0' />
<input type='hidden' name='encounterID'    value='0' />
</form>
</body>
<script>
    $(function() {
        $("#form_from_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        <?php if ($GLOBALS['ptkr_date_range']) { ?>
        $("#form_to_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        <?php } ?>
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });
    $("#form_apptstatus").addClass('form-control input-sm');
    $('#form_apptstatus').removeAttr('style');
</script>
</body>
</html>
