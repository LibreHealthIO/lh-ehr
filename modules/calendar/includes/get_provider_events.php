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

$events = array();

$fetchedEvents = fetchAllEvents($_POST['start'], $_POST['end']);

foreach($fetchedEvents as $event) {
  
  // skip cancelled appointments
  if ($GLOBALS['display_canceled_appointments'] != 1) {
     if ($event['pc_apptstatus'] == "x") { continue; }
  }
  
  $e = array();
  $e = $event;
  $e['id'] = $event['pc_eid'];
  $e['resourceId'] = $event['pc_aid'];
  $e['title'] = $event['pc_title'];
  $e['start'] = $event['pc_eventDate'] . " " . $event['pc_startTime'];
  $e['end'] = $event['pc_eventDate'] . " " . $event['pc_endTime'];
  $e['allDay'] = ($e['pc_alldayevent'] == 1) ? true : false;
  $e['color'] = $event['pc_catcolor'];
  
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
