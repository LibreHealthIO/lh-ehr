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
<title><?php echo xlt("Flow Board") ?></title>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">

<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/common.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../../library/js/blink/jquery.modern-blink.js"></script>
<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
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
 window.open('../main/calendar/add_edit_event.php?eid=' + eid + '&date=' + date_squash,'_blank', 'width=775,height=400,resizable=1');
 return false;
}

// auto refresh screen pat_trkr_timer is the timer variable
function refreshbegin(first){
  <?php if ($GLOBALS['pat_trkr_timer'] != '0') { ?>
    var reftime="<?php echo attr($GLOBALS['pat_trkr_timer']); ?>";
    var parsetime=reftime.split(":");
    parsetime=(parsetime[0]*60)+(parsetime[1]*1)*1000;
    if (first != '1') {
      refreshme();
    }
    setTimeout("refreshbegin('0')",parsetime);
  <?php } ?>
} 

// used to display the patient demographic and encounter screens
function topatient(newpid, enc) {
 if (document.pattrk.form_new_window.checked) {
   openNewTopWindow(newpid,enc);
 }
 else {
   top.restoreSession();
     if (enc > 0) {
       top.RTop.location= "<?php echo $GLOBALS['webroot']; ?>/interface/patient_file/summary/demographics.php?set_pid=" + newpid + "&set_encounterid=" + enc;
     }
     else {
       top.RTop.location = "<?php echo $GLOBALS['webroot']; ?>/interface/patient_file/summary/demographics.php?set_pid=" + newpid;
     }
 }
}

// opens the demographic and encounter screens in a new window
function openNewTopWindow(newpid,newencounterid) {
 document.fnew.patientID.value = newpid;
 document.fnew.encounterID.value = newencounterid;
 top.restoreSession();
 document.fnew.submit();
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
<body class="body_top" >
<form method='post' name='theform' id='theform' action='<?php echo $action_page; ?>' onsubmit='return top.restoreSession()'>
    <div id="flow_board_parameters">
        <table>
            <tr>
                <td class='label'><?php echo xlt('Provider'); ?>:</td>
                <td><?php

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

                    echo "   <select name='form_provider'>\n";
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
                <td class='label'><?php echo xlt('Status'); # status code drop down creation ?>:</td>
                <td><?php generate_form_field(array('data_type'=>1,'field_id'=>'apptstatus','list_id'=>'apptstat','empty_title'=>'All'),$form_apptstatus);
                ?></td>
                <?php if ($GLOBALS['ptkr_show_visit_type']) { ?>
                <td>
                <?php echo xlt('Category') #category drop down creation ?>:</td>
                    <td>
                        <select id="form_apptcat" name="form_apptcat">
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
            </tr>
            <tr>
                <td class='label'><?php if($GLOBALS['ptkr_date_range']) { echo xlt('From'); } else { echo xlt('Date'); }?>:</td>
                <td><input type='text' size='9' name='form_from_date' id="form_from_date"
                           value='<?php echo (attr($form_from_date)) ?>'>                
                <?php if($GLOBALS['ptkr_date_range']) { ?>
                <td class='label'><?php echo xlt('To'); ?>:</td>
                <td><input type='text' size='9' name='form_to_date' id="form_to_date"
                           value='<?php echo (attr($form_to_date)) ?>'>
                </td>
                <?php } ?>
                </tr>
                <tr>
                    <td>
                        <a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#theform").submit();'>
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
      <h2><span style='float: left'><?php echo xlt('Appointments for') . ' : '. text(reset($chk_prov)) . ' ' . ' : '. xlt('Date Range') . ' ' . text($form_from_date) . ' ' . xlt('to'). ' ' . text($form_to_date) ?></span></h2>
    <?php } else { ?>
      <h2><span style='float: left'><?php echo xlt('Appointments for'). ' : '. text(reset($chk_prov)) . ' : '. xlt('Date') . ' ' . text($form_from_date) ?></span></h2>
    <?php } ?>
  <?php } else { ?>
    <?php if($GLOBALS['ptkr_date_range']) { ?>
      <h2><span style='float: left'><?php echo xlt('Appointments Date Range'). ' : ' . text($form_from_date) . ' ' . xlt('to'). ' ' . text($form_to_date) ?></span></h2>
  <?php } else { ?>
      <h2><span style='float: left'><?php echo xlt('Appointment Date'). ' : ' . text($form_from_date) ?></span></h2>
  <?php } ?>
  <?php } ?>
 <div id= 'inanewwindow' class='inanewwindow'>
 <span style='float: right'>
 <input type='hidden' name='setting_new_window' value='1' />
 <input type='checkbox' name='form_new_window' value='1'<?php echo $new_window_checked; ?> /><?php
  echo xlt('Open Patient in New Window'); ?>
 </span>
 </div>
 </div>
<?php if ($GLOBALS['pat_trkr_timer'] =='0') { ?>
<table border='0' cellpadding='5' cellspacing='0'>
 <tr>
  <td  align='center'><br>
   <a href='javascript:;' class='css_button_small' align='center' style='color:gray' onclick="document.getElementById('pattrk').submit();"><span><?php echo xlt('Refresh Screen'); ?></span></a>
   </td>
 </tr>
</table>
<?php } ?>

<table border='0' cellpadding='1' cellspacing='2' width='100%'>
 <tr>
     <td colspan="12">
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
     </td>
 </tr>
<div id="flowboard_details">
 <tr bgcolor="#cccff">
  <?php if ($GLOBALS['ptkr_show_pid']) { ?>
   <td class="dehead" align="center">
   <?php  echo xlt('PID'); ?>
  </td>
  <?php } ?>
  <td class="dehead" align="center">
   <?php  echo xlt('Patient'); ?>
  </td>
  <?php if ($GLOBALS['ptkr_visit_reason']) { ?>
  <td class="dehead" align="center">
   <?php  echo xlt('Reason'); ?>
  </td>
  <?php } ?>
  <?php if ($GLOBALS['ptkr_show_encounter']) { ?>
  <td class="dehead" align="center">
   <?php  echo xlt('Encounter'); ?>
  </td>
  <?php } ?>
  <?php if ($GLOBALS['ptkr_show_room']) { ?>
  <td class="dehead" align="center">
   <?php  echo xlt('Exam Room #'); ?>
  </td>
  <?php } ?>
  <?php if($GLOBALS['ptkr_date_range']) { ?>
  <td class="dehead" align="center">
   <?php  echo xlt('Appt Date'); ?>
  </td>
  <?php } ?>
  <td class="dehead" align="center">
   <?php  echo xlt('Appt Time'); ?>
  </td>
  <td class="dehead" align="center">
   <?php  echo xlt('Arrive Time'); ?>
  </td>
  <td class="dehead" align="center">
   <?php  echo xlt('Status'); ?>
  </td>
  <td class="dehead" align="center">
   <?php  echo xlt('Current Status Time'); ?>
  </td>
   <?php if ($GLOBALS['ptkr_show_visit_type']) { ?>
   <td class="dehead" align="center">
   <?php  echo xlt('Category'); ?>
  </td>
  <?php } ?>
  <?php if (count($chk_prov) > 1) { ?>
  <td class="dehead" align="center">
   <?php  echo xlt('Provider'); ?>
  </td>
  <?php } ?>
 <td class="dehead" align="center">
   <?php  echo xlt('Total Time'); ?>
  </td>
 <td class="dehead" align="center">
   <?php  echo xlt('Check Out Time'); ?>
  </td>
   <td class="dehead" align="center">
   <?php  echo xlt('Updated By'); ?>
  </td>
 <?php if ($GLOBALS['drug_screen']) { ?> 
  <td class="dehead" align="center">
   <?php  echo xlt('Random Drug Screen'); ?>
  </td>
  <td class="dehead" align="center">
   <?php  echo xlt('Drug Screen Completed'); ?>
  </td>
 <?php } ?>
 </tr>

<?php
    $prev_appt_date_time = "";
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
        <td class="detail" align="center">
        <?php echo text($appt_pid) ?>
         </td>
        <?php } ?>
        <td class="detail" align="center">
        <a href="#" onclick="return topatient('<?php echo attr($appt_pid);?>','<?php echo attr($appt_enc);?>')" >
        <?php echo text($ptname); ?></a>
         </td>
         <!-- reason -->
         <?php if ($GLOBALS['ptkr_visit_reason']) { ?>
         <td class="detail" align="center">
         <?php echo text($reason_visit) ?>
         </td>
         <?php } ?>
         <?php if ($GLOBALS['ptkr_show_encounter']) { ?>
         <td class="detail" align="center">
         <?php if($appt_enc != 0) echo text($appt_enc); ?></a>
         </td>
         <?php } ?>
         <?php if ($GLOBALS['ptkr_show_room']) { ?>
         <td class="detail" align="center">
         <?php echo getListItemTitle('patient_flow_board_rooms', $appt_room);?>
         </td>
         <?php } ?>
         <?php if($GLOBALS['ptkr_date_range']) { ?>
         <td class="detail" align="center">
         <?php echo oeFormatShortDate($date_appt) ?>
         </td>
         <?php }
            $apptflagtd = '<td class="detail" align="center">';
            if ($GLOBALS['ptkr_flag_dblbook']) {
                if ($appt_date_time === $prev_appt_date_time) {
                    $apptflagtd = '<td class="detail" align="center" bgcolor="orange">';
                }
                $prev_appt_date_time = $appt_date_time;
            }
         echo $apptflagtd;  // <TD ..>
            echo oeFormatTime($appt_time);
         ?>
         </td>
         <td class="detail" align="center">
        <?php echo ($newarrive ? oeFormatTime($newarrive) : '&nbsp;') ?>
         </td>
         <td class="detail" align="center"> 
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
         <td class="detail" align="center">
         <?php echo text(xl_appt_category($appointment['pc_title'])) ?>
         </td>
         <?php } ?>
         <?php if (count($chk_prov) > 1) { ?>
         <td class="detail" align="center">
         <?php echo text($docname); ?>
         </td>
         <?php } ?>
         <td class="detail" align="center"> 
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
        <td class="detail" align="center">
         <?php 
         if (strtotime($newend) != '') {
            echo oeFormatTime($newend) ;
         }
         ?>
         </td>
         <td class="detail" align="center">
         <?php echo text($appointment['user']) ?>
         </td>
         <?php if ($GLOBALS['drug_screen']) { ?> 
         <?php if (strtotime($newarrive) != '') { ?> 
         <td class="detail" align="center">
         <?php if (text($appointment['random_drug_test']) == '1') {  echo xl('Yes'); }  else { echo xl('No'); }?>
         </td>
         <?php } else {  echo "  <td>"; }?>
         <?php if (strtotime($newarrive) != '' && $appointment['random_drug_test'] == '1') { ?> 
         <td class="detail" align="center">
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
</div>
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
</form>

<script type="text/javascript">
  $(document).ready(function() { 
      $('#settings').css("display","none");
      refreshbegin('1');
      $('.js-blink-infinite').modernBlink();

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
</script>
</body>
</html>
