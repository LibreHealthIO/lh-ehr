<?php
/**
 * Add or edit an event in the calendar.
 *
 * Can be displayed as a popup window, or as an iframe via
 * fancybox.
 *
 * Copyright (C) 2005-2013 Rod Roark <rod@sunsetsystems.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * @package LibreHealth EHR
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @link    http://librehealth.io
 */

 // The event editor looks something like this:

 //------------------------------------------------------------//
 // Category __________________V   O All day event             //
 // Date     _____________ [?]     O Time     ___:___ __V      //
 // Title    ___________________     duration ____ minutes     //
 // Patient  _(Click_to_select)_                               //
 // Provider __________________V   X Repeats  ______V ______V  //
 // Status   __________________V     until    __________ [?]   //
 // Comments ________________________________________________  //
 //                                                            //
 //       [Save]  [Find Available]  [Delete]  [Cancel]         //
 //------------------------------------------------------------//

 $fake_register_globals=false;
 $sanitize_all_escapes=true;

require_once('../../interface/globals.php');
require_once($GLOBALS['srcdir'].'/patient.inc');
require_once($GLOBALS['srcdir'].'/forms.inc');
require_once($GLOBALS['srcdir'].'/calendar.inc');
require_once($GLOBALS['srcdir'].'/options.inc.php');
require_once($GLOBALS['srcdir'].'/headers.inc.php');
require_once($GLOBALS['srcdir'].'/encounter_events.inc.php');
require_once($GLOBALS['srcdir'].'/acl.inc');
require_once($GLOBALS['srcdir'].'/patient_tracker.inc.php');
require_once($GLOBALS['srcdir']."/formatting.inc.php");

$library_array = array('iziModalToast');
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);
 //Check access control
if (!acl_check('patients', 'appt', '', array('write' , 'wsome'))) {
   die(xl('Access not allowed'));
}

/* Things that might be passed by our opener. */
 $eid           = $_GET['eid'];         // only for existing events
 $date          = $_GET['date'];        // this and below only for new events
 $userid        = $_GET['userid'];
 $default_catid = $_GET['catid'] ? $_GET['catid'] : '5';
 //
if ($date) {
  $date = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6);
} else {
  $date = date("Y-m-d");
}

 //
 $starttimem = '00';
if (isset($_GET['starttimem'])) {
  $starttimem = substr('00' . $_GET['starttimem'], -2);
}
 $endtimem = '00';
if (isset($_GET['endtimem'])) {
  $endtimem = substr('00' . $_GET['endtimem'], -2);
}
 //
 if (isset($_GET['starttimeh'])) {
  $starttimeh = $_GET['starttimeh'];
  if (isset($_GET['startampm'])) {
        if ($_GET['startampm'] == '2' && $starttimeh < 12) {
    $starttimeh += 12;
  }
    }
 } else {
  $starttimeh = date("G");
 }
  if (isset($_GET['endtimeh'])) {
  $endtimeh = $_GET['endtimeh'];
  if (isset($_GET['endampm'])) {
        if ($_GET['endampm'] == '2' && $endtimeh < 12) {
    $endtimeh += 12;
  }
    }
 } else {
  $endtimeh = date("G");
 }
 $startampm = '';
 $endampm = '';

 $info_msg = "";

 ?>
 <script type="text/javascript" src="<?php echo $webroot ?>/interface/main/tabs/js/include_opener.js"></script>
 <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.9.1.min.js"></script>

 <?php

function InsertEventFull()
 {
    global $new_multiple_value,$provider,$event_date,$duration,$recurrspec,$starttime,$endtime,$locationspec;
    // =======================================
    // multi providers case
    // =======================================
        if (is_array($_POST['form_provider'])) {

            // obtain the next available unique key to group multiple providers around some event
            $q = sqlStatement ("SELECT MAX(pc_multiple) as max FROM libreehr_postcalendar_events");
            $max = sqlFetchArray($q);
            if (!$max['max']){$new_multiple_value = $max['max'] + 1;
                }else{
                    $new_multiple_value = 1;}

            foreach ($_POST['form_provider'] as $provider) {
                $args = $_POST;
                // specify some special variables needed for the INSERT
                $args['new_multiple_value'] = $new_multiple_value;
                $args['form_provider'] = $provider;
                $args['event_date'] = $event_date;
                $args['duration'] = $duration * 60;
                $args['recurrspec'] = $recurrspec;
                $args['starttime'] = $starttime;
                $args['endtime'] = $endtime;
                $args['locationspec'] = $locationspec;
                InsertEvent($args);
            }

        // ====================================
        // single provider
        // ====================================
        } else {
            $args = $_POST;
            // specify some special variables needed for the INSERT
            $args['new_multiple_value'] = "";
            $args['event_date'] = $event_date;
            $args['duration'] = $duration * 60;
            $args['recurrspec'] = $recurrspec;
            $args['starttime'] = $starttime;
            $args['endtime'] = $endtime;
            $args['locationspec'] = $locationspec;
            InsertEvent($args);
        }
 }
function DOBandEncounter()
 {
   global $event_date,$info_msg;
     // Save new DOB if it's there.
     $patient_dob = trim($_POST['form_dob']);
     $tmph = $_POST['form_hour'] + 0;
     $tmpm = $_POST['form_minute'] + 0;
        if ($_POST['form_ampm'] == '2' && $tmph < 12) {
            $tmph += 12;
        }

     $appttime = "$tmph:$tmpm:00";

     if ($patient_dob && $_POST['form_pid']) {
             sqlStatement("UPDATE patient_data SET DOB = ? WHERE " .
                                     "pid = ?", array($patient_dob,$_POST['form_pid']) );
     }

    // Manage tracker status.
    // And auto-create a new encounter if appropriate.
    if (!empty($_POST['form_pid'])) {
     if ($GLOBALS['auto_create_new_encounters'] && $event_date == date('Y-m-d') && (is_checkin($_POST['form_apptstatus']) == '1') && !is_tracker_encounter_exist($event_date,$appttime,$_POST['form_pid'],$_GET['eid']))
     {
         $encounter = todaysEncounterCheck($_POST['form_pid'], $event_date, $_POST['form_comments'], $_POST['facility'], $_POST['billing_facility'], $_POST['form_provider'], $_POST['form_category'], false);
         if($encounter){
                 $info_msg .= xl("New encounter created with id");
                 $info_msg .= " $encounter";
         }
                 # Capture the appt status and room number for patient tracker. This will map the encounter to it also.
                 if ( isset($GLOBALS['temporary-eid-for-manage-tracker']) || !empty($_GET['eid']) ) {
                    // Note that the temporary-eid-for-manage-tracker is used to capture the eid for new appointments and when separate a recurring
                    // appointment. It is set in the InsertEvent() function. Note that in the case of spearating a recurrent appointment, the get eid
                    // parameter is actually erroneous(is eid of the recurrent appt and not the new separated appt), so need to use the
                    // temporary-eid-for-manage-tracker global instead.
                    $temp_eid = (isset($GLOBALS['temporary-eid-for-manage-tracker'])) ? $GLOBALS['temporary-eid-for-manage-tracker'] : $_GET['eid'];
            manage_tracker_status($event_date,$appttime,$temp_eid,$_POST['form_pid'],$_SESSION["authUser"], $_POST['form_apptstatus'],$_POST['form_room'],$_POST['form_reason_to_cancel'], $encounter);
                 }
     } else {
             # Capture the appt status and room number for patient tracker.
             if (!empty($_GET['eid'])) {
                manage_tracker_status($event_date,$appttime,$_GET['eid'],$_POST['form_pid'],$_SESSION["authUser"], $_POST['form_apptstatus'],$_POST['form_room'], '', $_POST['form_reason_to_cancel']);
             }
     }
    }

 }


 /*This function is used for setting the date of the first event when using the "day_every_week" repetition mechanism.
 When the 'start date' is not one of the days chosen for the repetition, the start date needs to be changed to the first
 occurrence of one of these set days. */
    function setEventDate($start_date, $recurrence)
    {
     $timestamp = strtotime($start_date);
     $day = date('w', $timestamp);
     //If the 'start date' is one of the set days
     if(in_array(($day+1), explode(',',$recurrence))){
         return $start_date;
     }
     //else: (we need to change start date to first occurrence of one of the set days)

     $new_date = getTheNextAppointment($start_date, $recurrence);

     return $new_date;


 }
//================================================================================================================

// EVENTS TO FACILITIES (lemonsoftware)
//(CHEMED) get facility name
// edit event case - if there is no association made, then insert one with the first facility
if ( $eid ) {
    $selfacil = '';
    $facility = sqlQuery("SELECT pc_facility, pc_multiple, pc_aid, facility.name
                            FROM libreehr_postcalendar_events
                              LEFT JOIN facility ON (libreehr_postcalendar_events.pc_facility = facility.id)
                              WHERE pc_eid = ?", array($eid) );
    if ( is_array($facility) && !$facility['pc_facility'] ) {
        $qmin = sqlQuery("SELECT facility_id as minId, facility FROM users WHERE id = ?", array($facility['pc_aid']) );
        $min  = $qmin['minId'];
        $min_name = $qmin['facility'];

        // multiple providers case
        if ( $GLOBALS['select_multi_providers'] ) {
            $mul  = $facility['pc_multiple'];
            sqlStatement("UPDATE libreehr_postcalendar_events SET pc_facility = ? WHERE pc_multiple = ?", array($min,$mul) );
        }
        // EOS multiple

        sqlStatement("UPDATE libreehr_postcalendar_events SET pc_facility = ? WHERE pc_eid = ?", array($min,$eid) );
        $e2f = $min;
        $e2f_name = $min_name;
    } else {
      // not edit event
      if (!$facility['pc_facility'] && $_SESSION['pc_facility']) {
        $e2f = $_SESSION['pc_facility'];
      } elseif (!$facility['pc_facility'] && $_COOKIE['pc_facility'] && $GLOBALS['set_facility_cookie']) {
    $e2f = $_COOKIE['pc_facility'];
      } else {
        $e2f = $facility['pc_facility'];
        $e2f_name = $facility['name'];
      }
    }
}
// EOS E2F
// ===========================
//=============================================================================================================================
if ($_POST['form_action'] == "duplicate" || $_POST['form_action'] == "save")
 {

    // Compute start and end time strings to be saved.
    if ($_POST['form_allday']) {
        $tmph = 0;
        $tmpm = 0;
        $duration = 24 * 60;
    } else {
        $tmph = $_POST['form_hour'] + 0;
        $tmpm = $_POST['form_minute'] + 0;
        if ($_POST['form_ampm'] == '2' && $tmph < 12){
             $tmph += 12;
        }
        $duration = abs($_POST['form_duration']); // fixes #395
    }

    $tmph = sprintf( "%02d", $tmph );
    $tmpm = sprintf( "%02d", $tmpm );
    $starttime = "$tmph:$tmpm:00";
    //
    $tmpm += $duration;
    while ($tmpm >= 60) {
        $tmpm -= 60;
        ++$tmph;
    }


    if($_POST['form_hour_end'] > '00') {
       $tmph = $_POST['form_hour_end'] + 0;
       $tmpm = $_POST['form_minute_end'] + 0;
       if ($_POST['form_ampm_end'] == '2' && $tmph < 12){
           $tmph += 12;
       }
       $endtime = "$tmph:$tmpm:00";
    }else{
    $endtime = "$tmph:$tmpm:00";
    }

    // Set up working variables related to repeated events.
    $my_recurrtype = 0;
    $my_repeat_freq = 0 + $_POST['form_repeat_freq'];
    $my_repeat_type = 0 + $_POST['form_repeat_type'];
    $my_repeat_on_num  = 1;
    $my_repeat_on_day  = 0;
    $my_repeat_on_freq = 0;

     // the starting date of the event, pay attention with this value
     // when editing recurring events -- JRM Oct-08
     $event_date = fixDate($_POST['form_date']);

         //If used new recurrence mechanism of set days every week
         if(!empty($_POST['days_every_week'])){
             $my_recurrtype = 3;
             //loop through checkboxes and insert encounter days into array
             $days_every_week_arr = array();
             for($i=1; $i<=7; $i++){
                 if(!empty($_POST['day_' . $i])){
                     array_push($days_every_week_arr, $i);
                 }
             }
             $my_repeat_freq = implode(",",$days_every_week_arr);
             $my_repeat_type = 6;
             $event_date = fixDate(setEventDate($_POST['form_date'], $my_repeat_freq));

         }
         elseif (!empty($_POST['form_repeat'])){

      $my_recurrtype = 1;
      if ($my_repeat_type > 4) {
        $my_recurrtype = 2;
        $time = strtotime($event_date);
        $my_repeat_on_day = 0 + date('w', $time);
        $my_repeat_on_freq = $my_repeat_freq;
        if ($my_repeat_type == 5) {
          $my_repeat_on_num = intval((date('j', $time) - 1) / 7) + 1;
        } else {
          // Last occurence of this weekday on the month
          $my_repeat_on_num = 5;
        }
        // Maybe not needed, but for consistency with postcalendar:
        $my_repeat_freq = 0;
        $my_repeat_type = 0;
      }
    }

    // Useless garbage that we must save.
    $locationspecs = array("event_location" => "",
                            "event_street1" => "",
                            "event_street2" => "",
                            "event_city" => "",
                            "event_state" => "",
                            "event_postal" => ""
                        );
    $locationspec = serialize($locationspecs);

    // capture the recurring specifications
    $recurrspec = array("event_repeat_freq" => "$my_repeat_freq",
                        "event_repeat_freq_type" => "$my_repeat_type",
                        "event_repeat_on_num" => "$my_repeat_on_num",
                        "event_repeat_on_day" => "$my_repeat_on_day",
                        "event_repeat_on_freq" => "$my_repeat_on_freq",
                        "exdate" => $_POST['form_repeat_exdate']
                    );

            //
        if ($my_recurrtype == 2) { // Added by epsdky 2016 (details in commit)
            if ($_POST['old_repeats'] == 2) {
                if ($_POST['rt2_flag2']) {
                    $recurrspec['rt2_pf_flag'] = "1";
                }
            } else {
                $recurrspec['rt2_pf_flag'] = "1";
            }
        } // End of addition by epsdky
            //

    // no recurr specs, this is used for adding a new non-recurring event
    $noRecurrspec = array("event_repeat_freq" => "",
                        "event_repeat_freq_type" => "",
                        "event_repeat_on_num" => "1",
                        "event_repeat_on_day" => "0",
                        "event_repeat_on_freq" => "0",
                        "exdate" => ""
                    );

 }//if ($_POST['form_action'] == "duplicate" || $_POST['form_action'] == "save")
//=============================================================================================================================
if ($_POST['form_action'] == "duplicate") {

    InsertEventFull();
    DOBandEncounter();
    refreshCalendar(); //after "Create Duplicate" process is complete

 }

// If we are saving, then save and close the window.
//
if ($_POST['form_action'] == "save") {
    if (($_POST['form_category'] == '2') || ($_POST['form_category'] == '3')) {
      // save the end time information to create the start and end time for the out of office record
      $cat3time = $endtime;
      // in the provider records for in and out of office start and end time are the same
      $endtime = $starttime;
    }
    /* =======================================================
     *                    UPDATE EVENTS
     * =====================================================*/
    if ($eid) {

        // what is multiple key around this $eid?
        $row = sqlQuery("SELECT pc_multiple FROM libreehr_postcalendar_events WHERE pc_eid = ?", array($eid) );

        // ====================================
        // multiple providers
        // ====================================
        if ($GLOBALS['select_multi_providers'] && $row['pc_multiple']) {

            // obtain current list of providers regarding the multiple key
            $up = sqlStatement("SELECT pc_aid FROM libreehr_postcalendar_events WHERE pc_multiple=?", array($row['pc_multiple']) );
            while ($current = sqlFetchArray($up)) {
                $providers_current[] = $current['pc_aid'];
            }

            // get the new list of providers from the submitted form
            $providers_new = $_POST['form_provider'];

            // ===== Only current event of repeating series =====
            if ($_POST['recurr_affect'] == 'current') {

                // update all existing event records to exlude the current date
                foreach ($providers_current as $provider) {
                    // update the provider's original event
                    // get the original event's repeat specs
                    $origEvent = sqlQuery("SELECT pc_recurrspec FROM libreehr_postcalendar_events ".
                        " WHERE pc_aid = ? AND pc_multiple=?", array($provider,$row['pc_multiple']) );
                    $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                    $selected_date = date("Ymd", strtotime($_POST['selected_date']));
                    if ($oldRecurrspec['exdate'] != "") {
                        $oldRecurrspec['exdate'] .= ",".$selected_date;
                    } else {
                        $oldRecurrspec['exdate'] .= $selected_date;
                    }

                    // mod original event recur specs to exclude this date
                    sqlStatement("UPDATE libreehr_postcalendar_events SET " .
                        " pc_recurrspec = ? ".
                        " WHERE pc_aid = ? AND pc_multiple=?", array(serialize($oldRecurrspec),$provider,$row['pc_multiple']) );
                }

                    // obtain the next available unique key to group multiple providers around some event
                    $q = sqlStatement ("SELECT MAX(pc_multiple) as max FROM libreehr_postcalendar_events");
                    $max = sqlFetchArray($q);
                    if (!$max['max']){$new_multiple_value = $max['max'] + 1;
                        } else {
                    $new_multiple_value = 1;}

                // insert a new event record for each provider selected on the form
                foreach ($providers_new as $provider) {
                    // insert a new event on this date with POST form data
                    $args = $_POST;
                    // specify some special variables needed for the INSERT
                    $args['new_multiple_value'] = $new_multiple_value;
                    $args['form_provider'] = $provider;
                    $args['event_date'] = $event_date;
                    $args['duration'] = $duration * 60;
                    // this event is forced to NOT REPEAT
                    $args['form_repeat'] = "0";
                    $args['days_every_week'] = "0";
                    $args['recurrspec'] = $noRecurrspec;
                    $args['form_enddate'] = "0000-00-00";
                    $args['starttime'] = $starttime;
                    $args['endtime'] = $endtime;
                    $args['locationspec'] = $locationspec;
                    InsertEvent($args);
                }
            }

            // ===== Future Recurring events of a repeating series =====
            else if ($_POST['recurr_affect'] == 'future') {
                // update all existing event records to
                // stop recurring on this date-1
                $selected_date = date("Ymd", (strtotime($_POST['selected_date'])-24*60*60));
                foreach ($providers_current as $provider) {
                    // mod original event recur specs to end on this date
                    sqlStatement("UPDATE libreehr_postcalendar_events SET " .
                        " pc_enddate = ? ".
                        " WHERE pc_aid = ? AND pc_multiple=?", array($selected_date,$provider,$row['pc_multiple']) );
                }

            // obtain the next available unique key to group multiple providers around some event
            $q = sqlStatement ("SELECT MAX(pc_multiple) as max FROM libreehr_postcalendar_events");
            $max = sqlFetchArray($q);
            if (!$max['max']){$new_multiple_value = $max['max'] + 1;
                } else {
                    $new_multiple_value = 1;}

                // insert a new event record for each provider selected on the form
                foreach ($providers_new as $provider) {
                    // insert a new event on this date with POST form data
                    $args = $_POST;
                    // specify some special variables needed for the INSERT
                    $args['new_multiple_value'] = $new_multiple_value;
                    $args['form_provider'] = $provider;
                    $args['event_date'] = $event_date;
                    $args['duration'] = $duration * 60;
                    $args['recurrspec'] = $recurrspec;
                    $args['starttime'] = $starttime;
                    $args['endtime'] = $endtime;
                    $args['locationspec'] = $locationspec;
                    InsertEvent($args);
                }
            } else {
                /* ================================================================== */
                // ===== a Single event or All events in a repeating series ==========
                /* =================================================================== */

                // this difference means that some providers from current was UNCHECKED
                // so we must delete this event for them
                $r1 = array_diff ($providers_current, $providers_new);
                if (count ($r1)) {
                    foreach ($r1 as $to_be_removed) {
                        sqlQuery("DELETE FROM libreehr_postcalendar_events WHERE pc_aid=? AND pc_multiple=?", array($to_be_removed,$row['pc_multiple']) );
                    }
                }

                // perform a check to see if user changed event date
                // this is important when editing an existing recurring event
                // oct-08 JRM
                if ($_POST['form_date'] == $_POST['selected_date']) {
                        // user has NOT changed the start date of the event (and not recurrtype 3)
                        if ($my_recurrtype != 3) {
                            $event_date = fixDate($_POST['event_start_date']);
                }
                }

                // this difference means that some providers were added
                // so we must insert this event for them
                $r2 = array_diff ($providers_new, $providers_current);
                if (count ($r2)) {
                    foreach ($r2 as $to_be_inserted) {
                        $args = $_POST;
                        // specify some special variables needed for the INSERT
                        $args['new_multiple_value'] = $row['pc_multiple'];
                        $args['form_provider'] = $to_be_inserted;
                        $args['event_date'] = $event_date;
                        $args['duration'] = $duration * 60;
                        $args['recurrspec'] = $recurrspec;
                        $args['starttime'] = $starttime;
                        $args['endtime'] = $endtime;
                        $args['locationspec'] = $locationspec;
                        InsertEvent($args);
                    }
                }

                // after the two diffs above, we must update for remaining providers
                // those who are intersected in $providers_current and $providers_new
                foreach ($_POST['form_provider'] as $provider) {
                    sqlStatement("UPDATE libreehr_postcalendar_events SET " .
                        "pc_catid = '" . add_escape_custom($_POST['form_category']) . "', " .
                        "pc_pid = '" . add_escape_custom($_POST['form_pid']) . "', " .
                        "pc_title = '" . add_escape_custom($_POST['form_title']) . "', " .
                        "pc_time = NOW(), " .
                        "pc_hometext = '" . add_escape_custom($_POST['form_comments']) . "', " .
                        "pc_room = '" . add_escape_custom($_POST['form_room']) . "', " .
                        "pc_informant = '" . add_escape_custom($_SESSION['authUserID']) . "', " .
                        "pc_eventDate = '" . add_escape_custom($event_date) . "', " .
                        "pc_endDate = '" . add_escape_custom(prepareDateBeforeSave($_POST['form_enddate'])) . "', " .
                        "pc_duration = '" . add_escape_custom(($duration * 60)) . "', " .
                        "pc_recurrtype = '" . add_escape_custom($my_recurrtype) . "', " .
                        "pc_recurrspec = '" . add_escape_custom(serialize($recurrspec)) . "', " .
                        "pc_startTime = '" . add_escape_custom($starttime) . "', " .
                        "pc_endTime = '" . add_escape_custom($endtime) . "', " .
                        "pc_alldayevent = '" . add_escape_custom($_POST['form_allday']) . "', " .
                        "pc_apptstatus = '" . add_escape_custom($_POST['form_apptstatus']) . "', "  .
                        "pc_prefcatid = '" . add_escape_custom($_POST['form_prefcat']) . "' ,"  .
                        "pc_facility = '" . add_escape_custom((int)$_POST['facility']) ."' ,"  . // FF stuff
                        "cancel_reason = '" . add_escape_custom($_POST['form_reason_to_cancel']) ."' ,"  .
                        "pc_billing_location = '" . add_escape_custom((int)$_POST['billing_facility']) ."' "  .
                        "WHERE pc_aid = '" . add_escape_custom($provider) . "' AND pc_multiple = '" . add_escape_custom($row['pc_multiple'])  . "'");
                } // foreach
            }

        // ====================================
        // single provider
        // ====================================
        } elseif ( !$row['pc_multiple'] ) {
            if ( $GLOBALS['select_multi_providers'] ) {
                $prov = $_POST['form_provider'][0];
            } else {
                $prov =  $_POST['form_provider'];
            }

            if ($_POST['recurr_affect'] == 'current') {
                // get the original event's repeat specs
                $origEvent = sqlQuery("SELECT pc_recurrspec FROM libreehr_postcalendar_events WHERE pc_eid = ?", array($eid) );
                $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                $selected_date = date("Ymd", strtotime($_POST['selected_date']));
                    if ($oldRecurrspec['exdate'] != "") {
                        $oldRecurrspec['exdate'] .= ",".$selected_date;
                    } else {
                        $oldRecurrspec['exdate'] .= $selected_date;
                    }

                // mod original event recur specs to exclude this date
                sqlStatement("UPDATE libreehr_postcalendar_events SET " .
                    " pc_recurrspec = ? ".
                    " WHERE pc_eid = ?", array(serialize($oldRecurrspec),$eid) );

                // insert a new event on this date with POST form data
                $args = $_POST;
                // specify some special variables needed for the INSERT
                $args['event_date'] = $event_date;
                $args['duration'] = $duration * 60;
                // this event is forced to NOT REPEAT
                $args['form_repeat'] = "0";
                $args['days_every_week'] = "0";
                $args['recurrspec'] = $noRecurrspec;
                $args['form_enddate'] = "0000-00-00";
                $args['starttime'] = $starttime;
                $args['endtime'] = $endtime;
                $args['locationspec'] = $locationspec;
                InsertEvent($args);
                } else if ($_POST['recurr_affect'] == 'future') {
                // mod original event to stop recurring on this date-1
                $selected_date = date("Ymd", (strtotime($_POST['selected_date'])-24*60*60));
                sqlStatement("UPDATE libreehr_postcalendar_events SET " .
                    " pc_enddate = ? ".
                    " WHERE pc_eid = ?", array($selected_date,$eid) );

                // insert a new event starting on this date with POST form data
                $args = $_POST;
                // specify some special variables needed for the INSERT
                $args['event_date'] = $event_date;
                $args['duration'] = $duration * 60;
                $args['recurrspec'] = $recurrspec;
                $args['starttime'] = $starttime;
                $args['endtime'] = $endtime;
                $args['locationspec'] = $locationspec;
                InsertEvent($args);
            } else {

    // perform a check to see if user changed event date
    // this is important when editing an existing recurring event
    // oct-08 JRM
    if ($_POST['form_date'] == $_POST['selected_date']) {
         // user has NOT changed the start date of the event (and not recurrtype 3)
          if ($my_recurrtype != 3) {
              $event_date = fixDate($_POST['event_start_date']);
          }
    }

                do_action( 'before_update_event',  $data = [ 'pc_eid' => $eid, ] );

                // mod the SINGLE event or ALL EVENTS in a repeating series
                // simple provider case
                sqlStatement("UPDATE libreehr_postcalendar_events SET " .
                    "pc_catid = '" . add_escape_custom($_POST['form_category']) . "', " .
                    "pc_aid = '" . add_escape_custom($prov) . "', " .
                    "pc_pid = '" . add_escape_custom($_POST['form_pid']) . "', " .
                    "pc_title = '" . add_escape_custom($_POST['form_title']) . "', " .
                    "pc_time = NOW(), " .
                    "pc_hometext = '" . add_escape_custom($_POST['form_comments']) . "', " .
                    "pc_room = '" . add_escape_custom($_POST['form_room']) . "', " .
                    "pc_informant = '" . add_escape_custom($_SESSION['authUserID']) . "', " .
                    "pc_eventDate = '" . add_escape_custom($event_date) . "', " .
                    "pc_endDate = '" . add_escape_custom(prepareDateBeforeSave($_POST['form_enddate'])) . "', " .
                    "pc_duration = '" . add_escape_custom(($duration * 60)) . "', " .
                    "pc_recurrtype = '" . add_escape_custom($my_recurrtype) . "', " .
                    "pc_recurrspec = '" . add_escape_custom(serialize($recurrspec)) . "', " .
                    "pc_startTime = '" . add_escape_custom($starttime) . "', " .
                    "pc_endTime = '" . add_escape_custom($endtime) . "', " .
                    "pc_alldayevent = '" . add_escape_custom($_POST['form_allday']) . "', " .
                    "pc_apptstatus = '" . add_escape_custom($_POST['form_apptstatus']) . "', "  .
                    "pc_prefcatid = '" . add_escape_custom($_POST['form_prefcat']) . "' ,"  .
                    "pc_facility = '" . add_escape_custom((int)$_POST['facility']) ."' ,"  . // FF stuff
                    "cancel_reason = '" . add_escape_custom($_POST['form_reason_to_cancel']) ."' ,"  .
                    "pc_billing_location = '" . add_escape_custom((int)$_POST['billing_facility']) ."' "  .
                    "WHERE pc_eid = '" . add_escape_custom($eid) . "'");

                do_action( 'after_update_event',
                    $data = [ 'pc_eid' => $eid, 'pc_pid' => $_POST['form_pid'],
                        'pc_aid' => $_POST['form_provider'], 'pc_eventDate' => $event_date, 'pc_startTime' => $starttime,
                        'pc_catid' => $_POST['form_category'], 'pc_apptstatus' => $_POST['form_apptstatus'], 'pc_title' => $_POST['form_title'],
                        ] );
            }
        }

        // =======================================
        // end Update Multi providers case
        // =======================================

        // EVENTS TO FACILITIES
        $e2f = (int)$eid;


    } else {
        /* =======================================================
         *                    INSERT NEW EVENT(S)
         * ======================================================*/

        InsertEventFull();

    }

    //when selected status is "Deleted" and save is clicked in add_edit_event
    //delete the event from the database and remove it from calendar
    if ($_POST['form_apptstatus'] == 'Deleted') {
        sqlStatement("DELETE FROM libreehr_postcalendar_events WHERE pc_eid = ?", array($_GET['eid']) );
    }

    // done with EVENT insert/update statements

      if ($_POST['form_category'] == '2' && (!$eid)) { //adding category 3 based on category 2
        $endtime = $cat3time;
        $starttime = $cat3time;
        $_POST['form_category'] = '3';
        $_POST['form_title'] = 'Out Of Office';
        InsertEventFull();
      }

        DOBandEncounter();

    refreshCalendar(); //after "Save" process is complete

 }

// =======================================
//    DELETE EVENT(s)
// =======================================
 else if ($_POST['form_action'] == "delete") {
        // =======================================
        //  multi providers event
        // =======================================
        if ($GLOBALS['select_multi_providers']) {

            // what is multiple key around this $eid?
            $row = sqlQuery("SELECT pc_multiple FROM libreehr_postcalendar_events WHERE pc_eid = ?", array($eid) );

            // obtain current list of providers regarding the multiple key
            $providers_current = array();
            $up = sqlStatement("SELECT pc_aid FROM libreehr_postcalendar_events WHERE pc_multiple=?", array($row['pc_multiple']) );
            while ($current = sqlFetchArray($up)) { $providers_current[] = $current['pc_aid']; }

            // establish a WHERE clause
            if ( $row['pc_multiple'] ) {
                 $whereClause = "pc_multiple = '{$row['pc_multiple']}'";
            } else {
                 $whereClause = "pc_eid = '$eid'";
            }

            if ($_POST['recurr_affect'] == 'current') {
                // update all existing event records to exlude the current date
                foreach ($providers_current as $provider) {
                    // update the provider's original event
                    // get the original event's repeat specs
                    $origEvent = sqlQuery("SELECT pc_recurrspec FROM libreehr_postcalendar_events ".
                        " WHERE pc_aid = ? AND pc_multiple=?", array($provider,$row['pc_multiple']) );
                    $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                    $selected_date = date("Ymd", strtotime($_POST['selected_date']));
                    if ($oldRecurrspec['exdate'] != "") {
                        $oldRecurrspec['exdate'] .= ",".$selected_date;
                    } else {
                        $oldRecurrspec['exdate'] .= $selected_date;
                    }

                    // mod original event recur specs to exclude this date
                    sqlStatement("UPDATE libreehr_postcalendar_events SET " .
                        " pc_recurrspec = ? ".
                        " WHERE ". $whereClause, array(serialize($oldRecurrspec)) );
                }
            }
            else if ($_POST['recurr_affect'] == 'future') {
                // update all existing event records to stop recurring on this date-1
                $selected_date = date("Ymd", (strtotime($_POST['selected_date'])-24*60*60));
                foreach ($providers_current as $provider) {
                    // update the provider's original event
                    sqlStatement("UPDATE libreehr_postcalendar_events SET " .
                        " pc_enddate = ? ".
                        " WHERE ".$whereClause, array($selected_date) );
                }
            } else {
                // really delete the event from the database
                sqlStatement("DELETE FROM libreehr_postcalendar_events WHERE ".$whereClause);
            }
        }

        // =======================================
        //  single provider event
        // =======================================
        else {

            if ($_POST['recurr_affect'] == 'current') {
                // mod original event recur specs to exclude this date

                // get the original event's repeat specs
                $origEvent = sqlQuery("SELECT pc_recurrspec FROM libreehr_postcalendar_events WHERE pc_eid = ?", array($eid) );
                $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                $selected_date = date("Ymd", strtotime($_POST['selected_date']));
                if ($oldRecurrspec['exdate'] != "") {
                    $oldRecurrspec['exdate'] .= ",".$selected_date;
                } else {
                    $oldRecurrspec['exdate'] .= $selected_date;
                }
                sqlStatement("UPDATE libreehr_postcalendar_events SET " .
                    " pc_recurrspec = ? ".
                    " WHERE pc_eid = ?", array(serialize($oldRecurrspec),$eid) );
            }

            else if ($_POST['recurr_affect'] == 'future') {
                // mod original event to stop recurring on this date-1
                $selected_date = date("Ymd", (strtotime($_POST['selected_date'])-24*60*60));
                sqlStatement("UPDATE libreehr_postcalendar_events SET " .
                    " pc_enddate = ? ".
                    " WHERE pc_eid = ?", array($selected_date,$eid) );
            } else {
                // fully delete the event from the database
                sqlStatement("DELETE FROM libreehr_postcalendar_events WHERE pc_eid = ?", array($eid) );
            }
        }

        #pass status Deleted to patient_tracker_element when deleting appointment by clicking delete
        if (!empty($_GET['eid']) && $_POST['form_pid'] != 0) {
            $tmph = $_POST['form_hour'] + 0;
            $tmpm = $_POST['form_minute'] + 0;
            if ($_POST['form_ampm'] == '2' && $tmph < 12) $tmph += 12;
            $appttime = "$tmph:$tmpm:00";
            $event_date = $_POST['form_date'];

            manage_tracker_status($event_date,$appttime,$_GET['eid'],$_POST['form_pid'],$_SESSION["authUser"],'Deleted',$_POST['form_room']);
        }

        refreshCalendar(); //after "Delete" process is complete
 }

 if ($_POST['form_action'] != "") {
  // Close this window and refresh the calendar (or the patient_tracker) display.
  echo "<html>\n<body>\n<script language='JavaScript'>\n";
  if ($info_msg) echo " alert('" . addslashes($info_msg) . "');\n";
  echo " if (opener && !opener.closed && opener.refreshme) {\n " .
       "  opener.refreshme();\n " . // This is for standard calendar page refresh
       " } else if(window.opener.pattrk){\n " .
       "  window.opener.pattrk.submit()\n " . // This is for patient flow board page refresh
       " };\n";
  echo " window.close();\n";
  echo "</script>\n</body>\n</html>\n";
  exit();
 }

 //*********************************
 // If we get this far then we are displaying the form.
 //*********************************

/*********************************************************************
        This has been migrate to the administration->lists
 $statuses = array(
  '-' => '',
  '*' => xl('* Reminder done'),
  '+' => xl('+ Chart pulled'),
  'x' => xl('x Cancelled'), // added Apr 2008 by JRM
  '?' => xl('? No show'),
  '@' => xl('@ Arrived'),
  '~' => xl('~ Arrived late'),
  '!' => xl('! Left w/o visit'),
  '#' => xl('# Ins/fin issue'),
  '<' => xl('< In exam room'),
  '>' => xl('> Checked out'),
  '$' => xl('$ Coding done'),
   '%' => xl('% Cancelled <  24h ')
 );
*********************************************************************/

 $repeats = 0; // if the event repeats
 $repeattype = '0';
 $repeatfreq = '0';
 $patientid = '';
 $cancelreason = '';
 // fix for new calendar
 if (isset($_SESSION['pid']) && $_SESSION['pid'] > 0) $patientid = $_SESSION['pid'];

 // used in find_patient.php
 if ($_REQUEST['patientid']) {
     $patientid = $_REQUEST['patientid'];
 }

 $patientname = xl('Click to select');
 $patienttitle = "";
 $pcroom = "";
 $hometext = "";
 $row = array();
 $informant = "";

 // If we are editing an existing event, then get its data.
 if ($eid) {

  $row = sqlQuery("SELECT e.*, u.fname, u.mname, u.lname " .
    "FROM libreehr_postcalendar_events AS e " .
    "LEFT OUTER JOIN users AS u ON u.id = e.pc_informant " .
    "WHERE pc_eid = ?", array($eid) );
  $informant = $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'];
  $cancelreason = $row['cancel_reason'];
  // instead of using the event's starting date, keep what has been provided
  // via the GET array, see the top of this file
  if (empty($_GET['date'])) {
      $date = $row['pc_eventDate'];
  }
  $eventstartdate = $row['pc_eventDate']; // for repeating event stuff - JRM Oct-08
  $userid = $row['pc_aid'];
  $patientid = $row['pc_pid'];
  $starttimeh = substr($row['pc_startTime'], 0, 2) + 0;
  $starttimem = substr($row['pc_startTime'], 3, 2);
  $endtimeh = substr($row['pc_endTime'], 0, 2) + 0;
  $endtimem = substr($row['pc_endTime'], 3, 2);
  $repeats = $row['pc_recurrtype'];
  $multiple_value = $row['pc_multiple'];

  // parse out the repeating data, if any
  $rspecs = unserialize($row['pc_recurrspec']); // extract recurring data
  $repeattype = $rspecs['event_repeat_freq_type'];
  $repeatfreq = $rspecs['event_repeat_freq'];
  $repeatexdate = $rspecs['exdate']; // repeating date exceptions

  // Adjustments for repeat type 2, a particular weekday of the month.
  if ($repeats == 2) {
    $repeatfreq = $rspecs['event_repeat_on_freq'];
    if ($rspecs['event_repeat_on_num'] < 5) {
      $repeattype = 5;
    } else {
      $repeattype = 6;
    }
  }
  $pcroom = $row['pc_room'];
  $hometext = $row['pc_hometext'];
  if (substr($hometext, 0, 6) == ':text:') $hometext = substr($hometext, 6);
 } else {
    // a NEW event
    $eventstartdate = $date; // for repeating event stuff - JRM Oct-08

    //-------------------------------------

        //Set default facility for a new event based on logged in user, not provider (event slot)
    if ($_SESSION['authId']) {
        /*************************************************************
        $pref_facility = sqlFetchArray(sqlStatement("SELECT facility_id, facility FROM users WHERE id = $userid"));
        *************************************************************/
        if ($_SESSION['pc_facility']) {
            $pref_facility = sqlFetchArray(sqlStatement("
                SELECT f.id as facility_id,
                f.name as facility
                FROM facility f
                WHERE f.id = ?
              ",
                array($_SESSION['pc_facility'])
              ));
        } else {
          $pref_facility = sqlFetchArray(sqlStatement("
            SELECT u.facility_id,
              f.name as facility
            FROM users u
            LEFT JOIN facility f on (u.facility_id = f.id)
            WHERE u.id = ?
            ", array($userid) ));
        }
        /************************************************************/
        $e2f = $pref_facility['facility_id'];
        $e2f_name = $pref_facility['facility'];
    }
    //END of CHEMED -----------------------
 }

 // If we have a patient ID, get the name and phone numbers to display.
 if ($patientid) {
  $prow = sqlQuery("SELECT lname, fname, phone_home, phone_biz, DOB " .
   "FROM patient_data WHERE pid = ?", array($patientid) );
  $patientname = $prow['lname'] . ", " . $prow['fname'];
  if ($prow['phone_home']) $patienttitle .= " H=" . $prow['phone_home'];
  if ($prow['phone_biz']) $patienttitle  .= " W=" . $prow['phone_biz'];
 }

 // Get the providers list.
 $ures = sqlStatement("SELECT id, username, fname, lname FROM users WHERE " .
  "authorized != 0 AND active = 1 ORDER BY lname, fname");

 // Get event categories.
 $cres=fetchAppointmentCategories( true );

 // Fix up the time format for AM/PM.
 $startampm = '1';
 $endampm = '1';
 if ($starttimeh >= 12) { // p.m. starts at noon and not 12:01
  $startampm = '2';
      if ( $GLOBALS['time_display_format'] == 1) {
  if ($starttimeh > 12) $starttimeh -= 12;
    } else {
       if ($starttimeh > 24) $starttimeh -= 12;
    }
 }
  if ($endtimeh >= 12) { // p.m. starts at noon and not 12:01
  $endampm = '2';
      if ( $GLOBALS['time_display_format'] == 1) {
  if ($endtimeh > 12) $endtimeh -= 12;
    } else {
       if ($endtimeh > 24) $endtimeh -= 12;
    }
 }

?>
<html>
<head>
<?php html_header_show(); ?>
<title><?php echo $eid ? xlt('Edit') : xlt('Add New') ?> <?php echo xlt('Event');?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">

<style>
td { font-size:0.8em; }
</style>

<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>

<script type="text/javascript" src="../../library/topdialog.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>

<script language="JavaScript">



 var durations = new Array();
 // var rectypes  = new Array();
<?php
 // Read the event categories, generate their options list, and get
 // the default event duration from them if this is a new event.
 $cattype=0;
 if($_GET['prov']==true){
  $cattype=1;
 }
 $cres=fetchAppointmentCategories( true );
 $catoptions = "";
 $prefcat_options = "    <option value='0'>-- " . xlt("None") . " --</option>\n";
 $thisduration = 0;
 if ($eid) {
  $thisduration = $row['pc_alldayevent'] ? 1440 : round($row['pc_duration'] / 60);
 }
 while ($crow = sqlFetchArray($cres)) {
  $duration = round($crow['pc_duration'] / 60);
    if ($crow['pc_end_all_day']) {
        $duration = 1440;
    }

  // This section is to build the list of preferred categories:
  if ($duration) {
   $prefcat_options .= "    <option value='" . attr($crow['pc_catid']) . "'";
   if ($eid) {
       if ($crow['pc_catid'] == $row['pc_prefcatid']) {
           $prefcat_options .= " selected";
       }
   }
   $prefcat_options .= ">" . text(xl_appt_category($crow['pc_catname'])) . "</option>\n";
  }

  if ($crow['pc_cattype'] != $cattype) {
      continue;
  }

  echo " durations[" . attr($crow['pc_catid']) . "] = " . attr($duration) . "\n";
  $catoptions .= "    <option value='" . attr($crow['pc_catid']) . "'";
  if ($eid) {
      if ($crow['pc_catid'] == $row['pc_catid']) {
          $catoptions .= " selected";
      }
  } else {
   if ($crow['pc_catid'] == $default_catid) {
    $catoptions .= " selected";
    $thisduration = $duration;
   }
  }
  $catoptions .= ">" . text(xl_appt_category($crow['pc_catname'])) . "</option>\n";
 }
?>

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 // This is for callback by the find-patient popup.
 function setpatient(pid, lname, fname, dob) {
  var f = document.forms[0];
  f.form_patient.value = lname + ', ' + fname;
  f.form_pid.value = pid;
  dobstyle = (dob == '' || dob.substr(5, 10) == '00-00') ? '' : 'none';
  document.getElementById('dob_row').style.display = dobstyle;
 }

 // This invokes the find-patient popup.
 function sel_patient() {
  dlgopen('<?php echo $GLOBALS["web_root"]; ?>/modules/calendar/find_patient_popup.php', '_blank', 500, 400);
 }

 // Do whatever is needed when a new event category is selected.
 // For now this means changing the event title and duration.
 function set_display() {
  var f = document.forms[0];
  var s = f.form_category;
  if (s.selectedIndex >= 0) {
   var catid = s.options[s.selectedIndex].value;
   var style_apptstatus = document.getElementById('title_apptstatus').style;
   var style_prefcat = document.getElementById('title_prefcat').style;
   if (catid == '2') { // In Office
    style_apptstatus.display = 'none';
    style_prefcat.display = '';
    f.form_apptstatus.style.display = 'none';
    f.form_prefcat.style.display = '';
   } else {
    style_prefcat.display = 'none';
    style_apptstatus.display = '';
    f.form_prefcat.style.display = 'none';
    f.form_apptstatus.style.display = '';
   }
  }
 }

 // Do whatever is needed when a new event category is selected.
 // For now this means changing the event title and duration.
 function set_category() {
  var f = document.forms[0];
  var s = f.form_category;
  if (s.selectedIndex >= 0) {
   var catid = s.options[s.selectedIndex].value;
   f.form_title.value = s.options[s.selectedIndex].text;
   f.form_duration.value = durations[catid];
   set_display();
  }
 }

 // Modify some visual attributes when the all-day or timed-event
 // radio buttons are clicked.
 function set_allday() {
  var f = document.forms[0];
  var color1 = '#777777';
  var color2 = '#777777';
  var disabled2 = true;
  if (document.getElementById('rballday1').checked) {
   color1 = '#000000';
  }
  if (document.getElementById('rballday2').checked) {
   color2 = '#000000';
   disabled2 = false;
  }
  document.getElementById('tdallday1').style.color = color1;
  document.getElementById('tdallday2').style.color = color2;
  document.getElementById('tdallday3').style.color = color2;
  document.getElementById('tdallday4').style.color = color2;
  document.getElementById('tdallday5').style.color = color2;
  f.form_hour.disabled     = disabled2;
  f.form_minute.disabled   = disabled2;
  f.form_ampm.disabled     = disabled2;
  f.form_duration.disabled = disabled2;
 }

 // Modify some visual attributes when the Repeat checkbox is clicked.
 function set_repeat() {
  var f = document.forms[0];
  var isdisabled = true;
  var mycolor = '#777777';
  var myvisibility = 'hidden';
  if (f.form_repeat.checked) {
      f.days_every_week.checked = false;
      document.getElementById("days_label").style.color = mycolor;
      var days = document.getElementById("days").getElementsByTagName('input');
      var labels = document.getElementById("days").getElementsByTagName('label');
      for(var i=0; i < days.length; i++){
          days[i].disabled = isdisabled;
          labels[i].style.color = mycolor;
      }
   isdisabled = false;
   mycolor = '#000000';
   myvisibility = 'visible';
  }
  f.form_repeat_type.disabled = isdisabled;
  f.form_repeat_freq.disabled = isdisabled;
  f.form_enddate.disabled = isdisabled;
  document.getElementById('tdrepeat1').style.color = mycolor;
  document.getElementById('tdrepeat2').style.color = mycolor;
 }

 // Event when days_every_week is checked.
 function set_days_every_week() {
     var f = document.forms[0];
     if (f.days_every_week.checked) {
         //disable regular repeat
         f.form_repeat.checked = false;
         f.form_repeat_type.disabled = true;
         f.form_repeat_freq.disabled = true;
         document.getElementById('tdrepeat1').style.color = '#777777';

         //enable end_date setting
         document.getElementById('tdrepeat2').style.color = '#000000';
         f.form_enddate.disabled = false;

         var isdisabled = false;
         var mycolor = '#000000';
         var myvisibility = 'visible';
     }
     else{
         var isdisabled = true;
         var mycolor = '#777777';
         var myvisibility = 'hidden';
     }
     document.getElementById("days_label").style.color = mycolor;
     var days = document.getElementById("days").getElementsByTagName('input');
     var labels = document.getElementById("days").getElementsByTagName('label');
     for(var i=0; i < days.length; i++){
         days[i].disabled = isdisabled;
         labels[i].style.color = mycolor;
     }

     //If no repetition is checked, disable end_date setting.
     if(!f.days_every_week.checked  && !f.form_repeat.checked){
         //disable end_date setting
         document.getElementById('tdrepeat2').style.color = mycolor;
         f.form_enddate.disabled = isdisabled;
     }


 }

 // Constants used by dateChanged() function.
 var occurNames = new Array(
  '<?php echo xls("1st"); ?>',
  '<?php echo xls("2nd"); ?>',
  '<?php echo xls("3rd"); ?>',
  '<?php echo xls("4th"); ?>'
 );

var weekDays = new Array(
  '<?php echo xls("Sunday"); ?>',
  '<?php echo xls("Monday"); ?>',
  '<?php echo xls("Tuesday"); ?>',
  '<?php echo xls("Wednesday"); ?>',
  '<?php echo xls("Thursday"); ?>',
  '<?php echo xls("Friday"); ?>',
  '<?php echo xls("Saturday"); ?>'
 );

 // Monitor start date changes to adjust repeat type options.
 function dateChanged() {
  var f = document.forms[0];
  if (!f.form_date.value) return;
  var d = new Date(f.form_date.value);
  var downame = weekDays[d.getUTCDay()];
  var nthtext = '';
  var occur = Math.floor((d.getUTCDate() - 1) / 7);
  if (occur < 4) { // 5th is not allowed
   nthtext = occurNames[occur] + ' ' + downame;
  }
  var lasttext = '';
  var tmp = new Date(d.getUTCFullYear(), d.getUTCMonth() + 1, 0);
  if (tmp.getDate() - d.getUTCDate() < 7) { // Modified by epsdky 2016 (details in commit)
   // This is a last occurrence of the specified weekday in the month,
   // so permit that as an option.
   lasttext = '<?php echo xls("Last"); ?> ' + downame;
  }
  var si = f.form_repeat_type.selectedIndex;
  var opts = f.form_repeat_type.options;
  opts.length = 5; // remove any nth and Last entries
  if (nthtext ) opts[opts.length] = new Option(nthtext , '5');
  if (lasttext) opts[opts.length] = new Option(lasttext, '6');
  if (si < opts.length) f.form_repeat_type.selectedIndex = si;
  else f.form_repeat_type.selectedIndex = 5; // Added by epsdky 2016 (details in commit)
 }

 // This is for callback by the find-available popup.
 function setappt(year,mon,mday,hours,minutes) {
  var f = document.forms[0];
  f.form_date.value = '' + year + '-' +
   ('' + (mon  + 100)).substring(1) + '-' +
   ('' + (mday + 100)).substring(1);
  f.form_ampm.selectedIndex = (hours >= 12) ? 1 : 0;
  f.form_hour.value = (hours > 12) ? hours - 12 : hours;
  f.form_minute.value = ('' + (minutes + 100)).substring(1);
 }

    // Invoke the find-available popup.
    function find_available(extra) {
        top.restoreSession();
        // (CHEMED) Conditional value selection, because there is no <select> element
        // when making an appointment for a specific provider
        var s = document.forms[0].form_provider;
        var f = document.forms[0].facility;
        <?php if ($userid != 0) { ?>
            s = document.forms[0].form_provider.value;
            f = document.forms[0].facility.value;
        <?php } else {?>
            s = document.forms[0].form_provider.options[s.selectedIndex].value;
            f = document.forms[0].facility.options[f.selectedIndex].value;
        <?php }?>
        var c = document.forms[0].form_category;
        var formDate = document.forms[0].form_date;

        // evdur must be a positive number; if evdur <= 0, it will output an infinite stream of times
        // If evdur is positive, include it in URL.
        if (document.forms[0].form_duration.value > 0) {
            dlgopen('<?php echo $GLOBALS["web_root"]; ?>/modules/calendar/find_appt_popup.php' +
                '?providerid=' + s +
                '&catid=' + c.options[c.selectedIndex].value +
                '&facility=' + f +
                '&startdate=' + formDate.value +
                '&evdur=' + document.forms[0].form_duration.value +
                '&eid=<?php echo 0 + $eid; ?>' +
                extra,
                '_blank', 500, 400);
        } else { // If evdur is zero or negative, do not include it in URL.
            dlgopen('<?php echo $GLOBALS["web_root"]; ?>/modules/calendar/find_appt_popup.php' +
                '?providerid=' + s +
                '&catid=' + c.options[c.selectedIndex].value +
                '&facility=' + f +
                '&startdate=' + formDate.value +
                '&eid=<?php echo 0 + $eid; ?>' +
                extra,
                '_blank', 500, 400);
        }
    }

</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body class="body_top" onunload='imclosing()'>

<form method='post' name='theform' id='theform' action='add_edit_event.php?eid=<?php echo attr($eid) ?>' />
<!-- ViSolve : Requirement - Redirect to Create New Patient Page -->
<input type='hidden' size='2' name='resname' value='empty' />
<?php
if ($_POST["resname"]=="noresult"){
echo '
<script language="Javascript">
            // refresh and redirect the parent window
            if (!opener.closed && opener.refreshme) opener.refreshme();
            top.restoreSession();
            opener.document.location="../../interface/new/new_comprehensive.php";
            // Close the window
            window.close();
</script>';
}
$classprov='current';
$classpati='';
?>
<!-- ViSolve : Requirement - Redirect to Create New Patient Page -->
<input type="hidden" name="form_action" id="form_action" value="">
<input type="hidden" name="recurr_affect" id="recurr_affect" value="">
<!-- used for recurring events -->
<input type="hidden" name="selected_date" id="selected_date" value="<?php echo attr($date); ?>">
<input type="hidden" name="event_start_date" id="event_start_date" value="<?php echo attr($eventstartdate); ?>">
<!-- Following added by epsdky 2016 (details in commit) -->
<input   type="hidden" name="old_repeats" id="old_repeats" value="<?php echo attr($repeats); ?>">
<input   type="hidden" name="rt2_flag2" id="rt2_flag2" value="<?php echo attr(isset($rspecs['rt2_pf_flag']) ? $rspecs['rt2_pf_flag'] : '0'); ?>">
<!-- End of addition by epsdky -->
<center>
<table border='0' >
<?php
    $provider_class='';
    $normal='';
    if($_GET['prov']==true){
    $provider_class="class='current'";
    } else {
    $normal="class='current'";
    }
?>
<tr><th><ul class="tabNav">
<?php
    $eid=$_REQUEST["eid"];
    $startm=$_REQUEST["startampm"];
    $starth=$_REQUEST["starttimeh"];
    $endm=$_REQUEST["endampm"];
    $endh=$_REQUEST["endtimeh"];
    $uid=$_REQUEST["userid"];
    $starttm=$_REQUEST["starttimem"];
    $endtm=$_REQUEST["endtimem"];
    $dt=$_REQUEST["date"];
    $cid=$_REQUEST["catid"];
?>
         <li <?php echo $normal;?>>
         <a href='add_edit_event.php?eid=<?php echo attr($eid);?>&startampm=<?php echo attr($startm);?>&starttimeh=<?php echo attr($starth);?>&userid=<?php echo attr($uid);?>&starttimem=<?php echo attr($starttm);?>&date=<?php echo attr($dt);?>&catid=<?php echo attr($cid);?>'>
         <?php echo xlt('Patient');?></a>
         </li>
         <li <?php echo $provider_class;?>>
         <a href='add_edit_event.php?prov=true&eid=<?php echo attr($eid);?>&startampm=<?php echo attr($startm);?>&starttimeh=<?php echo attr($starth);?>&userid=<?php echo attr($uid);?>&starttimem=<?php echo attr($starttm);?>&date=<?php echo attr($dt);?>&catid=<?php echo attr($cid);?>'>
         <?php echo xlt('Provider');?></a>
         </li>
        </ul>
</th></tr>
<tr><td colspan='10'>
<table border='0' width='100%' bgcolor='#DDDDDD' >

 <tr>
  <td width='1%' nowrap>
   <b><?php echo xlt('Category'); ?>:</b>
  </td>
  <td nowrap>
   <select name='form_category' onchange='set_category()' style='width:100%'>
<?php echo $catoptions ?>
   </select>
  </td>
  <td width='1%' nowrap>
   &nbsp;&nbsp;
   <input type='radio' name='form_allday' onclick='set_allday()' value='1' id='rballday1'
    <?php if ($thisduration == 1440) echo "checked " ?>/>
  </td>
  <td colspan='2' nowrap id='tdallday1'>
   <?php echo xlt('All day event'); ?>
  </td>
 </tr>

 <tr>
  <td nowrap>
   <b><?php echo xlt('Date'); ?>:</b>
  </td>
  <td nowrap>
   <input type='text' size='10' name='form_date' id='form_date'
    value='<?php echo oeFormatShortDate(attr($date));  ?>'/>


  </td>
  <td nowrap>
   &nbsp;&nbsp;
   <input type='radio' name='form_allday' onclick='set_allday()' value='0' id='rballday2' <?php if ($thisduration != 1440) echo "checked " ?>/>
  </td>
  <td width='1%' nowrap id='tdallday2'>
   <?php echo xlt('Time'); ?>
  </td>
  <td width='1%' nowrap id='tdallday3'>
   <span>
    <input type='text' size='2' name='form_hour' value='<?php echo attr($starttimeh) ?>'
     title='<?php echo xla('Event start time'); ?>' /> :
    <input type='text' size='2' name='form_minute' value='<?php echo attr($starttimem) ?>'
     title='<?php echo xla('Event start time'); ?>' />&nbsp;
   </span>
   <select name='form_ampm' title='<?php echo xla("Note: 12:00 noon is PM, not AM"); ?>'>
    <option value='1'><?php echo xlt('AM'); ?></option>
    <option value='2'<?php if ($startampm == '2') echo " selected" ?>><?php echo xlt('PM'); ?></option>
   </select>
  </td>
 </tr>

 <tr>
  <td nowrap>
   <b><?php echo xlt('Title'); ?>:</b>
  </td>
  <td nowrap>
   <input type='text' size='10' name='form_title' value='<?php echo attr($row['pc_title']); ?>'
    style='width:100%'
    title='<?php echo xla('Event title'); ?>' />
  </td>
  <td nowrap>&nbsp;

  </td>
     <?php if($_GET['prov']==true){ ?>
    <td width='1%' nowrap id='tdallday2'>
   <?php echo xlt('End Time'); ?>
  </td>
  <td width='1%' nowrap id='tdallday3'>
   <span>
    <input type='text' size='2' name='form_hour_end' value='<?php echo attr($endtimeh) ?>'
     title='<?php echo xla('Event end time'); ?>' /> :
    <input type='text' size='2' name='form_minute_end' value='<?php echo attr($endtimem) ?>'
     title='<?php echo xla('Event end time'); ?>' />&nbsp;
   </span>
   <select name='form_ampm_end' title='<?php echo xla("Note: 12:00 noon is PM, not AM"); ?>'>
    <option value='1'><?php echo xlt('AM'); ?></option>
    <option value='2'<?php if ($endampm == '2') echo " selected" ?>><?php echo xlt('PM'); ?></option>
   </select>
  </td>
     <?php } else { ?>
  <td nowrap id='tdallday4'><?php echo xlt('duration'); ?>
  </td>
  <td nowrap id='tdallday5'>
   <input type='text' size='4' name='form_duration' value='<?php echo attr($thisduration) ?>' title='<?php echo xla('Event duration in minutes'); ?>' />
    <?php echo xlt('minutes'); ?>
  </td>
     <?php }?>

 </tr>

    <tr>
      <td nowrap><b><?php echo xlt('Facility'); ?>:</b></td>
      <td>
      <select name="facility" id="facility" >
      <?php

      // ===========================
      // EVENTS TO FACILITIES
      $facils = getUserFacilities($_SESSION['authId']);
      //$_SESSION['authId'] is id of logged in user
      //ufid is an array of id of schedule facilities of that user
      $ufid = array();
      foreach ($facils as $uf) {
        $ufid[] = $uf['id'];
      }
      $qsql = sqlStatement("SELECT id, name FROM facility WHERE service_location != 0");
      /**************************************************************/
      while ($facrow = sqlFetchArray($qsql)) {
        /*************************************************************
        $selected = ( $facrow['id'] == $e2f ) ? 'selected="selected"' : '' ;
        echo "<option value={$facrow['id']} $selected>{$facrow['name']}</option>";
        *************************************************************/
        if ($GLOBALS['restrict_user_facility']) {
           //if restricting user to scheduling appointments at only their allowed facilities
          //then list only schedule facilities of that user or default facility
          //in Facility drop down in calendar's event panel
          //e2f denotes id of default facility
          if (in_array($facrow['id'], $ufid) || $facrow['id'] == $e2f) {
            //id 3 is for "Your Clinic Name Here"
            $selected = ( $facrow['id'] == $e2f ) ? 'selected="selected"' : '' ;
            echo "<option value='" . attr($facrow['id']) . "' $selected>" . text($facrow['name']) . "</option>";
          }
        } else {
          //if not restricting then list all facilities
          //where service_location is not 0 including default facility
          //in Facility in calendar's event panel
          $selected = ( $facrow['id'] == $e2f ) ? 'selected="selected"' : '' ;
          echo "<option value='" . attr($facrow['id']) . "' $selected>" . text($facrow['name']) . "</option>";
        }
        /************************************************************/
      }
      // EOS E2F
      // ===========================
      ?>
                            </select>

      </td>
    <?php if($_GET['prov']==true){ ?>
    <td nowrap id='tdallday4'><td>
    <td nowrap id='tdallday5'><td>
   <input type='hidden' size='4' name='form_duration' value='' title='' />
     <?php } ?>
    </tr>
    <tr>
        <td nowrap>
        <b><?php echo xlt('Billing Facility'); ?>:</b>
        </td>
        <td>
            <?php
            billing_facility('billing_facility',$row['pc_billing_location']);
            ?>
        </td>
    </tr>
 <?php
 if($_GET['prov']!=true){
 ?>
 <tr id="patient_details">
  <td nowrap>
   <b><?php echo xlt('Patient'); ?>:</b>
  </td>
  <td nowrap>
   <input type='text' size='10' name='form_patient' style='width:100%;cursor:pointer;cursor:hand' value='<?php echo attr($patientname); ?>' onclick='sel_patient()' title='<?php echo xla('Click to select patient'); ?>' readonly />
   <input type='hidden' name='form_pid' value='<?php echo attr($patientid) ?>' />
  </td>
  <td colspan='3' nowrap style='font-size:8pt'>
   &nbsp;
   <span class="infobox">
   <?php if ($patienttitle != "") { echo $patienttitle; } ?>
   </span>
  </td>
 </tr>
 <?php
 }
 ?>
 <tr>
  <td nowrap>
   <b><?php echo xlt('Provider'); ?>:</b>
  </td>
  <td nowrap>

<?php

// =======================================
// multi providers
// =======================================
if  ($GLOBALS['select_multi_providers']) {

    //  there are two posible situations: edit and new record
    $providers_array = array();

    // this is executed only on edit ($eid)
    if ($eid) {
        if ( $multiple_value ) {
            // find all the providers around multiple key
            $qall = sqlStatement ("SELECT pc_aid AS providers FROM libreehr_postcalendar_events WHERE pc_multiple = ?", array($multiple_value) );
            while ($r = sqlFetchArray($qall)) {
                $providers_array[] = $r['providers'];
            }
        } else {
            $qall = sqlStatement ("SELECT pc_aid AS providers FROM libreehr_postcalendar_events WHERE pc_eid = ?", array($eid) );
            $providers_array = sqlFetchArray($qall);
        }
    }

    // build the selection tool
    echo "<select name='form_provider[]' style='width:100%' multiple='multiple' size='5' >";

    while ($urow = sqlFetchArray($ures)) {
        echo "    <option value='" . attr($urow['id']) . "'";

        if ($userid) {
            if (in_array($urow['id'], $providers_array) || ($urow['id'] == $userid)) {
                echo " selected";
            }
        }

        echo ">" . text($urow['lname']);
        if ($urow['fname']) {
            echo ", " . text($urow['fname']);
        }

        echo "</option>\n";
    }

    echo '</select>';

// =======================================
// single provider
// =======================================
} else {

    if ($eid) {
        // get provider from existing event
        $qprov = sqlStatement ("SELECT pc_aid FROM libreehr_postcalendar_events WHERE pc_eid = ?", array($eid) );
        $provider = sqlFetchArray($qprov);
        $defaultProvider = $provider['pc_aid'];
    } else {
      // this is a new event so smartly choose a default provider
    /*****************************************************************
      if ($userid) {
        // Provider already given to us as a GET parameter.
        $defaultProvider = $userid;
      } else {
        // default to the currently logged-in user
        $defaultProvider = $_SESSION['authUserID'];
        // or, if we have chosen a provider in the calendar, default to them
        // choose the first one if multiple have been selected
        if (count($_SESSION['pc_username']) >= 1) {
          // get the numeric ID of the first provider in the array
          $pc_username = $_SESSION['pc_username'];
          $firstProvider = sqlFetchArray(sqlStatement("select id from users where username='".$pc_username[0]."'"));
          $defaultProvider = $firstProvider['id'];
        }
      }
    }

    echo "<select name='form_provider' style='width:100%' />";
    while ($urow = sqlFetchArray($ures)) {
        echo "    <option value='" . $urow['id'] . "'";
        if ($urow['id'] == $defaultProvider) echo " selected";
        echo ">" . $urow['lname'];
        if ($urow['fname']) echo ", " . $urow['fname'];
        echo "</option>\n";
    }
    echo "</select>";
    *****************************************************************/
      // default to the currently logged-in user
      $defaultProvider = $_SESSION['authUserID'];
      // or, if we have chosen a provider in the calendar, default to them
      // choose the first one if multiple have been selected
      if (count($_SESSION['pc_username']) >= 1) {
        // get the numeric ID of the first provider in the array
        $pc_username = $_SESSION['pc_username'];
        $firstProvider = sqlFetchArray(sqlStatement("select id from users where username=?", array($pc_username[0]) ));
        $defaultProvider = $firstProvider['id'];
      }
      // if we clicked on a provider's schedule to add the event, use THAT.
        if ($userid) {
            $defaultProvider = $userid;
        }
    }
    echo "<select name='form_provider' style='width:100%' />";
    while ($urow = sqlFetchArray($ures)) {
      echo "    <option value='" . attr($urow['id']) . "'";
        if ($urow['id'] == $defaultProvider) {
            echo " selected";
        }

      echo ">" . text($urow['lname']);
        if ($urow['fname']) {
            echo ", " . text($urow['fname']);
        }

      echo "</option>\n";
    }
    echo "</select>";
    /****************************************************************/
}

?>

  </td>
  <td nowrap>
   &nbsp;&nbsp;
      <?php
      //Check if repeat is using the new 'days every week' mechanism.
        function isDaysEveryWeek($repeat)
        {
          if($repeat == 3){
              return true;
            } else {
              return false;
          }
      }

      //Check if using the regular repeat mechanism.
        function isRegularRepeat($repeat)
        {
          if($repeat == 1 || $repeat == 2){
              return true;
            } else {
              return false;
          }
      }


      /*
      If the appointment was set with the regular (old) repeat mechanism (using 'every', 'every 2', etc.), then will be
      checked when editing and will select the proper recurrence pattern. If using the new repeat mechanism, then only that box (and the proper set
      days) will be checked. That's why I had to add the functions 'isRegularRepeat' and 'isDaysEveryWeek', to check which
      repeating mechanism is being used, and load settings accordingly.
      */
      ?>
   <input type='checkbox' name='form_repeat' onclick='set_repeat(this)' value='1'<?php if (isRegularRepeat($repeats)) echo " checked" ?>/>
   <input type='hidden' name='form_repeat_exdate' id='form_repeat_exdate' value='<?php echo attr($repeatexdate); ?>' /> <!-- dates excluded from the repeat -->
  </td>
  <td nowrap id='tdrepeat1'><?php echo xlt('Repeats'); ?>
  </td>
  <td nowrap>

   <select name='form_repeat_freq' title='<?php echo xla('Every, every other, every 3rd, etc.'); ?>'>
<?php
 foreach (array(1 => xl('every'), 2 => xl('2nd'), 3 => xl('3rd'), 4 => xl('4th'), 5 => xl('5th'), 6 => xl('6th'))
  as $key => $value)
 {
  echo "    <option value='" . attr($key) . "'";
    if ($key == $repeatfreq && isRegularRepeat($repeats)) {
        echo " selected";
    }

  echo ">" . text($value) . "</option>\n";
 }
?>
   </select>

   <select name='form_repeat_type'>
<?php
 // See common.api.php for these. Options 5 and 6 will be dynamically filled in
 // when the start date is set.
 foreach (array(0 => xl('day') , 4 => xl('workday'), 1 => xl('week'), 2 => xl('month'), 3 => xl('year'),
   5 => '?', 6 => '?') as $key => $value)
 {
  echo "    <option value='" . attr($key) . "'";
    if ($key == $repeattype && isRegularRepeat($repeats)) {
        echo " selected";
    }

  echo ">" . text($value) . "</option>\n";
 }
?>
   </select>

  </td>
 </tr>

    <style>
        #days_every_week_row input[type="checkbox"]{float:right;}
        #days_every_week_row div{display: inline-block; text-align: center; width: 12%;}
        #days_every_week_row div input{width: 100%;}
    </style>

<tr id="days_every_week_row">
    <td></td>
    <td></td>
    <td>
    <input type='checkbox' id='days_every_week' name='days_every_week' onclick='set_days_every_week()'
    <?php
       if (isDaysEveryWeek($repeats)) {
           echo " checked";
       }
    ?>/>
    </td>
    <td id="days_label">
        <?php echo xlt('Days Of Week') . ": "; ?>
    </td>
    <td id="days">
        <?php
        foreach (array(1 => xl('Su{{Sunday}}') , 2 => xl('M{{Monday}}'), 3 => xl('Tu{{Tuesday}}'), 4 => xl('W{{Wednesday}}'),
                     5 => xl('Th{{Thursday}}'), 6 => xl('F{{Friday}}'), 7 => xl('Sa{{Saturday}}')) as $key => $value)
        {
            echo " <div><input type='checkbox' name='day_". attr($key) ."'";
            //Checks appropriate days according to days in recurrence string.
            if (in_array($key, explode(',', $repeatfreq)) && isDaysEveryWeek($repeats)) {
                echo " checked";
            }

            echo " /><label>" . text($value) . "</label></div>\n";
        }
        ?>
    </td>

</tr>

 <tr>
  <td nowrap>
   <span id='title_apptstatus'><b><?php echo xlt('Status'); ?>:</b></span>
   <span id='title_prefcat' style='display:none'><b><?php echo xlt('Pref Cat'); ?>:</b></span>
  </td>
  <td nowrap>

<?php
generate_form_field(array('data_type'=>1,'field_id'=>'apptstatus','list_id'=>'apptstat','empty_title'=>'SKIP'), $row['pc_apptstatus']);
if(($_GET['prov']!=true) ){
    if(strlen($cancelreason)> '4'){
     echo xlt(' Reason: ') . $cancelreason;
    }
}
?>
   <!--
    The following list will be invisible unless this is an In Office
    event, in which case form_apptstatus (above) is to be invisible.
   -->
   <select name='form_prefcat' style='width:100%;display:none' title='<?php echo xla('Preferred Event Category');?>'>
<?php echo $prefcat_options ?>
   </select>

  </td>
  <td nowrap>&nbsp;

  </td>
  <td nowrap id='tdrepeat2'><?php echo xlt('until'); ?>
  </td>
  <td nowrap>
   <input type='text' size='10' name='form_enddate' id='form_enddate'
          value='<?php echo oeFormatShortDate(attr($row['pc_endDate'])) ?>'/>
<?php
if ($repeatexdate != "") {
    $tmptitle = "The following dates are excluded from the repeating series";
    if ($multiple_value) {
        $tmptitle .= " for one or more providers:\n";
    } else {
        $tmptitle .= "\n";
    }
    $exdates = explode(",", $repeatexdate);
    foreach ($exdates as $exdate) {
        $tmptitle .= date("d M Y", strtotime($exdate))."\n";
    }
    echo "<a href='#' title='" . attr($tmptitle) . "' alt='" . attr($tmptitle) . "'><img src='../../interface/pic/warning.gif' title='" . attr($tmptitle) . "' alt='*!*' style='border:none;'/></a>";
}
?>
  </td>
 </tr>
 <?php
 if($_GET['prov']!=true){
 ?>
 <tr>
  <td nowrap>
   <b><?php echo xlt('Room Number'); ?>:</b>
  </td>
  <td colspan='4' nowrap>
<?php
    echo generate_select_list('form_room', 'patient_flow_board_rooms',$pcroom, xl('Room Number'));
?>
  </td>
 </tr>
<?php } ?>
 <tr>
  <td nowrap>
   <b><?php echo xlt('Comments'); ?>:</b>
  </td>
  <td colspan='4' nowrap>
   <input type='text' size='40' name='form_comments' style='width:100%' value='<?php echo attr($hometext); ?>' title='<?php echo xla('Optional information about this event');?>' />
  </td>
 </tr>


<?php
 // DOB is important for the clinic, so if it's missing give them a chance
 // to enter it right here.  We must display or hide this row dynamically
 // in case the patient-select popup is used.
 $patient_dob = trim($prow['DOB']);
 $dobstyle = ($prow && (!$patient_dob || substr($patient_dob, 5) == '00-00')) ?
  '' : 'none';
?>
 <tr id='dob_row' style='display:<?php echo $dobstyle ?>'>
  <td colspan='4' nowrap>
   <b><font color='red'><?php echo xlt('DOB is missing, please enter if possible'); ?>:</font></b>
  </td>
  <td nowrap>
   <input type='text' size='10' name='form_dob' id='form_dob' />
   <input type='hidden' size='10' name='form_reason_to_cancel' id='form_reason_to_cancel' />
  </td>
 </tr>

</table></td></tr>
<tr class='text'><td colspan='10'>
<p>
<div id="reasons_modal">
<?php
$sql = "SELECT * FROM `list_options` WHERE list_id='cancellation_reasons'";
$query = sqlStatement($sql);
while ($r = sqlFetchArray($query)) {
    echo "<input type='radio' value='". $r['title']."' name='form_reason_for_cancellation'>".$r['title']."<br/>";
}
echo "<input type='radio' value='Other' name='form_reason_for_cancellation' id='reason_other'>Other";
?>
<br/><br/>
<div id="reason_textarea" style="display: none;">
    <b style="margin-left: 1em;"><?php echo xlt('Please Add your reason'); ?></b><br/><br/>
    <textarea id="other_textarea" rows="3" cols="50" style="margin-left: 1em;"></textarea>
</div>
<br/><br/>
<input type="button" class="cp-submit" value="Submit" style="margin-left: 20em;" id="close_reason_modal">
</div>
<input type='button' name='form_save' class="cp-submit"  id='form_save' value='<?php echo xla('Save');?>' />
&nbsp;

<?php if (!($GLOBALS['select_multi_providers'])) { //multi providers appt is not supported by check slot avail window, so skip ?>
  <input type='button' class="cp-misc" id='find_available' value='<?php echo xla('Find Available');?>' />
<?php } ?>

&nbsp;
 <?php
 if($_GET['prov']==true){
 ?>
<input type='button' class="cp-negative" name='form_delete' id='form_delete' value='<?php echo xla('Delete');?>'<?php if (!$eid) echo " disabled" ?> />
<?php } ?>
&nbsp;
<input type='button' class="cp-negative" id='cancel' value='<?php echo xla('Cancel');?>' />
&nbsp;
<input type='button' class="cp-positive" name='form_duplicate' id='form_duplicate' value='<?php echo xla('Create Duplicate');?>' />
</p></td></tr></table>
<?php if ($informant) echo "<p class='text'>" . xlt('Last update by') . " " .
  text($informant) . " " . xlt('on') . " " . text(oeFormatDateTime($row['pc_time'])) . "</p>\n"; ?>
</center>


<div id="recurr_popup" style="visibility: hidden; position: absolute; top: 50px; left: 50px; width: 400px; border: 3px outset yellow; background-color: yellow; padding: 5px;">
<?php echo xlt('Apply the changes to the Current event only, to this and all Future occurrences, or to All occurrences?') ?>
<br>
<input type="button" name="all_events" id="all_events" value="  <?php echo xla('All'); ?>  ">
<input type="button" name="future_events" id="future_events" value="<?php echo xla('Future'); ?>">
<input type="button" name="current_event" id="current_event" value="<?php echo xla('Current'); ?>">
<input type="button" name="recurr_cancel" id="recurr_cancel" value="<?php echo xla('Cancel'); ?>">
</div>

</body>
</form>
<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>

<script language='JavaScript'>
<?php if ($eid) { ?>
 set_display();
<?php } else { ?>
 set_category();
<?php } ?>
 set_allday();
 set_repeat();
set_days_every_week();

$(function() {
    $("#form_date, #form_enddate, #form_dob").datetimepicker({
        timepicker: false,
        format: "<?= $DateFormat; ?>"
    });
    $.datetimepicker.setLocale('<?= $DateLocale;?>');
});
</script>
<?php call_required_libraries($library_array); ?>
<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $("#form_save").click(function() {
        var reason = $("input[name='form_reason_for_cancellation']:checked").val();
        if (reason) {
            $('#form_reason_to_cancel').val(reason);
            validate("save");
        }
        else {
            var selected_text = $("#form_apptstatus option:selected").text();
            if (selected_text == "x Canceled") {
                $("#reasons_modal").iziModal('open');
            }
            else {
                validate("save");
            }
        }
         });

    $("#form_duplicate").click(function() { validate("duplicate"); });
    $("#find_available").click(function() { find_available(''); });
    $("#form_delete").click(function() { deleteEvent(); });
    $("#cancel").click(function() { window.close(); });

    // buttons affecting the modification of a repeating event
    $("#all_events").click(function() { $("#recurr_affect").val("all"); EnableForm(); SubmitForm(); });
    $("#future_events").click(function() { $("#recurr_affect").val("future"); EnableForm(); SubmitForm(); });
    $("#current_event").click(function() { $("#recurr_affect").val("current"); EnableForm(); SubmitForm(); });
    $("#recurr_cancel").click(function() { $("#recurr_affect").val(""); EnableForm(); HideRecurrPopup(); });

    // Initialize repeat options.
    dateChanged();
});


function are_days_checked(){
    var days = document.getElementById("days").getElementsByTagName('input');
    var counter = 0;
    for(var i=0; i < days.length; i++){
       if(days[i].checked){
           counter++;
       }
    }
    return counter;
}

// Check for errors when the form is submitted.
function validate(valu) {
    var f = document.getElementById('theform');
    if ((f.form_repeat.checked || f.days_every_week.checked) &&
        (! f.form_enddate.value || f.form_enddate.value < f.form_date.value)) {
        alert('<?php echo addslashes(xl("An end date later than the start date is required for repeated events!")); ?>');
        return false;
    }

    //Make sure if days_every_week is checked that at least one weekday is checked.
    if(f.days_every_week.checked && !are_days_checked()){
        alert('<?php echo xls("Must choose at least one day!"); ?>');
        return false;
    }

    //check if user selected appt. time is within clinic hours
    var checkApptTime = '<?php echo($GLOBALS['check_appt_time']) ?>';
    if (f.form_allday.value < 1 && checkApptTime == 1) {
        //only when Time radio button is selected (value is "0", otherwise "1")
        //and global is checked (= "1")
        var userAMPM = f.form_ampm.value; //"1" for AM, "2" for PM
        var userHour = parseInt(f.form_hour.value);
        var userMinute = parseInt(f.form_minute.value);
        if (userAMPM == "1") {
            //AM, 00:00 midnight to 11:59 noon
            var userTime = userHour + (userMinute/60);
        } else {
            //PM, 12:00 noon to 23:59 midnight
            var userTime = 12 + userHour + (userMinute/60);
        }

        //checked for AM/PM because globals: schedule start and end times are in 24 hour format
        //while user (selected) time is in 12 hour format
        var clinicStartTime = parseInt('<?php echo($GLOBALS['schedule_start']) ?>');
        var clinicEndTime = parseInt('<?php echo($GLOBALS['schedule_end']) ?>');
        if (userTime < clinicStartTime || userTime > clinicEndTime) {
            alert('<?php echo addslashes(xl("Please select time between clinic hours.")); ?>');
            return false;
        }
    }

    <?php
    if($_GET['prov']!=true){
    ?>
     if(f.form_pid.value == ''){
      alert('<?php echo addslashes(xl('Patient Name Required'));?>');
      return false;
     }
    <?php
    }
    ?>
    //checked for errors till here
    //now check if selected appt. status is "Deleted" before SubmitForm()
    //and alert user to make sure to delete patient appt.
    var status_selected = $("#form_apptstatus option:selected").text();
    if (status_selected == "Deleted") {
        //check if user is creating an appt. with status "Deleted"
        var eventId = '<?php echo $_GET['eid']; ?>'; //numeric for existing events
        if (eventId > 0) {
            if (confirm("<?php echo addslashes(xl('Deleting this event cannot be undone. It cannot be recovered once it is gone. Are you sure you wish to delete this event?')); ?>")) {
                //if selected "OK" on alert
                $('#form_action').val(valu);
                <?php if ($repeats): ?>
                    // existing repeating events need additional prompt
                    if ($("#recurr_affect").val() == "") {
                        DisableForm();
                        // show the current/future/all DIV for the user to choose one
                        $("#recurr_popup").css("visibility", "visible");
                        return false;
                    }
                <?php endif; ?>
                return SubmitForm();
            }
            //if selected "Cancel" on alert
            return false;
        }
        //alert user if creating appt. with status "Delete";
        alert('<?php echo addslashes(xl("Cannot create an Appointment with status Deleted")); ?>');
        return false;
    } else {
        //if selected appt. status isn't "Deleted", no need to alert
        $('#form_action').val(valu);
        <?php if ($repeats): ?>
            // existing repeating events need additional prompt
            if ($("#recurr_affect").val() == "") {
                DisableForm();
                // show the current/future/all DIV for the user to choose one
                $("#recurr_popup").css("visibility", "visible");
                return false;
            }
        <?php endif; ?>
        return SubmitForm();
    }
}

// disable all the form elements outside the recurr_popup
function DisableForm() {
    $("#theform").children().attr("disabled", "true");
}
function EnableForm() {
    $("#theform").children().removeAttr("disabled");
}
// hide the recurring popup DIV
function HideRecurrPopup() {
    $("#recurr_popup").css("visibility", "hidden");
}

function deleteEvent() {
    if (confirm("<?php echo addslashes(xl('Deleting this event cannot be undone. It cannot be recovered once it is gone. Are you sure you wish to delete this event?')); ?>")) {
        $('#form_action').val("delete");

        <?php if ($repeats): ?>
        // existing repeating events need additional prompt
        if ($("#recurr_affect").val() == "") {
            DisableForm();
            // show the current/future/all DIV for the user to choose one
            $("#recurr_popup").css("visibility", "visible");
            return false;
        }
        <?php endif; ?>

        return SubmitForm();
    }
    return false;
}

function SubmitForm() {
 var f = document.forms[0];
 <?php if (!($GLOBALS['select_multi_providers'])) { // multi providers appt is not supported by check slot avail window, so skip ?>
  if (f.form_action.value != 'delete') {
    // Check slot availability.
    var mins = parseInt(f.form_hour.value) * 60 + parseInt(f.form_minute.value);
    if (f.form_ampm.value == '2' && mins < 720) mins += 720;
    find_available('&cktime=' + mins);
  } else {
    top.restoreSession();
    f.submit();
  }
 <?php } else { ?>
  top.restoreSession();
  f.submit();
 <?php } ?>

  return true;
}

$('#reasons_modal').iziModal({
               title: "<?php echo xlt('Reason for Cancellation'); ?>",
               subtitle: "<?php echo xlt('Choose the reason for Cancellation. If none are applicable, choose Other'); ?>",
               headerColor: '#eee',
               closeOnEscape: true,
               fullscreen:true,
               overlayClose: false,
               closeButton: true,
               theme: 'light',  // light
           });

$("#form_apptstatus").change(function() {

    var selected_text = $("#form_apptstatus option:selected").text();
    if (selected_text == "x Canceled") {
        $("#reasons_modal").iziModal('open');
    }
})

$('#close_reason_modal').click(function () {
   $("#reasons_modal").iziModal('close');
});

$('#reason_other').click(function () {
    $('#reason_textarea').css("display", "block");
});
$('#other_textarea').keyup(function () {
    var value = $('#other_textarea').val();
    $('#reason_other').val(value);
});
</script>


</html>
