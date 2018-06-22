<?php
/*
 *  get_provider_events.php Gathering the provider events for the Calendar
 *
 * Copyright (C) 2017 Ujjwal Arora <arora.ujjwal@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Ujjwal Arora <arora.ujjwal@gmail.com >
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */

require_once('../../../interface/globals.php');
require_once('../../../library/appointments.inc.php');
require_once("$srcdir/patient_tracker.inc.php");

$events = array();

$fetchedEvents = fetchAllEvents($_POST['start'], $_POST['end']);

// facility ACL check
if ( $GLOBALS['facility_acl'] == 1 ) {
  $patient_filter = do_action('filter_patient_select_pnuserapi', $_SESSION['authUser']); // returns allowed facility ids in a SQL string
  $patients_allowed_to_user = array();  // array containing patient ids which logged in user is allowed to see

  // making of $patients_allowed_to_user
  if ($patient_filter) {
    $sql = "SELECT pd.pid
            FROM patient_data pd
            WHERE" . $patient_filter; // " pd.facility IN ( user_schedule_facility_id_string ) "
    $result = sqlStatement($sql);
    while ($row = sqlFetchArray($result)) {
      $patients_allowed_to_user[] = $row['pid'];
    }
  }
}

foreach($fetchedEvents as $event) {
  // event - patient check
  if ( $GLOBALS['facility_acl'] == 1 ) {
    // check if event's patient id is in array
    if (in_array($event['pc_pid'], $patients_allowed_to_user) === false) {
      // if not, continue to next event without merging this event with $events
      continue;
    }
  }

  // skip cancelled appointments
  if ($GLOBALS['display_canceled_appointments'] != 1) {
     if ($event['pc_apptstatus'] == "x") { continue; }
  }
  $status = $event['pc_apptstatus'];
  $colorevents = (collectApptStatusSettings($status));

  $e = array();
  $e = $event;
  $e['id'] = $event['pc_eid'];
  $e['resourceId'] = $event['pc_aid'];
  $e['title'] = $event['pc_title'];
  $e['start'] = $event['pc_eventDate'] . " " . $event['pc_startTime'];
  $e['end'] = $event['pc_eventDate'] . " " . $event['pc_endTime'];
  $e['allDay'] = ($e['pc_alldayevent'] == 1) ? true : false;
  if ($GLOBALS['use_appt_status_colors'] == 1) {
    $e['color'] = $colorevents['color'];
      if ($event['pc_apptstatus'] == "-") {
       $e['color'] = $event['pc_catcolor'];
      }
  }else{
  $e['color'] = $event['pc_catcolor'];
  }
  $e['e_info'] = " (" . $event['pc_title'] . ")";
  if($event["pc_pid"] > 0) {
    $e['picture_url'] = getPatientPictureUrl($event["pc_pid"]);
    $e['description'] = $event['pc_apptstatus'] . " " . $event['lname'] . ", " . $event['fname'] . " (" . $event['pc_title'];
    if(!empty($event["pc_hometext"])) {
      $e['description'] = $e['description'] . ": " . $event["pc_hometext"];
    }
    $e['description'] = $e['description'] . ")";
    switch($GLOBALS['calendar_appt_style']) {
      case 1:
        $e['title'] = $event['pc_apptstatus'] . " " . $event['lname'];
        break;
      case 2:
        $e['title'] = $event['pc_apptstatus'] . " " . $event['lname'] . ", " . $event['fname'];
        break;
      case 3:
        $e['title'] = $event['pc_apptstatus'] . " " . $event['lname'] . ", " . $event['fname'] . " (" . $event['pc_title'] . ")";
        break;
      case 4:
        $e['title'] = $e['description'];  // Case 4 is exactly the same as the event tooltip
        break;
      default:
        $e['title'] = $event['pc_apptstatus'] . " " . $event['lname'] . ", " . $event['fname'];
    }
  } else {
    $e['description'] = $event['pc_title'];
  }
  // Merge the event array into the return array
  array_push($events, $e);
}

// Set a background event to indicate provider shifts
foreach($events as $eStart) {
  if($eStart['pc_catid'] == 2) {
    foreach($events as $eEnd) {
      if($eStart['pc_aid'] == $eEnd['pc_aid'] && $eEnd['pc_catid'] == 3 && $eStart['pc_eventDate'] == $eEnd['pc_eventDate']) {
        $e = array();
        $e['start'] = $eStart['start'];
        $e['end'] = $eEnd['start'];
        $e['resourceId'] = $eStart['resourceId'];
        // $e['color'] = 'gray';
        // $e['className'] = 'fc-business';
        $e['rendering'] = 'background';
        array_push($events, $e);
      }
    }
  }
}

// Output json for our calendar
echo json_encode($events);
exit();


?>
