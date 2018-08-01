<?php
/*
 *  drag_copy_event.php - processing file which handles Database changes for event drag-resize-copy feature in Calendar
 *
 * Copyright (C) 2018 Apoorv Choubey < theapoorvs1@gmail.com >
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Apoorv Choubey < theapoorvs1@gmail.com >
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */

require_once("../../interface/globals.php");
require_once("$srcdir/htmlspecialchars.inc.php");
require_once("$srcdir/patient_tracker.inc.php");
require_once("$srcdir/encounter_events.inc.php");

if ($_POST['action'] == "drag") {
  // event is dragged - update date, start time, end time and provider id
  sqlStatement("UPDATE libreehr_postcalendar_events
                SET pc_eventDate = ?, pc_startTime = ?, pc_endTime = ?, pc_aid = ?
                WHERE pc_eid = ?", array($_POST['date'], $_POST['startTime'], $_POST['endTime'], $_POST['providerId'], $_POST['id']));
  if (!empty($_POST['patientId'])) {
    // if event is a patient event
    // insert changes done (in date or start time) in patient_tracker and patient_tracker_element tables
    manage_tracker_status($_POST['date'], $_POST['startTime'], $_POST['id'], $_POST['patientId'], $_SESSION["authUser"], $_POST['apptStatus']);
  }
  echo "query executed";
} elseif ($_POST['action'] == "resize") {
  // event is resized - update end time and duration
  sqlStatement("UPDATE libreehr_postcalendar_events
                SET pc_endTime = ?, pc_duration = ?
                WHERE pc_eid = ?", array($_POST['endTime'], $_POST['duration'], $_POST['id']));
  echo "query executed";
} elseif ($_POST['action'] == "paste") {
  $args = array();
      // specify values needed for the INSERT into table - libreehr_postcalendar_events - columns
      $args['event_date'] = $_POST['date'];
      $args['starttime'] = $_POST['startTime'];
      $args['endtime'] = $_POST['endTime'];
      $args['form_provider'] = $_POST['providerId'];
      $args['new_multiple_value'] = $_POST['multipleProvider'];
      $args['form_pid'] = $_POST['patientId'];
      $args['form_category'] = $_POST['categoryId'];
      $args['form_title'] = $_POST['title'];
      $args['form_comments'] = $_POST['comment'];
      $args['form_enddate'] = $_POST['endDate'];
      $args['duration'] = $_POST['duration'];
      $args['form_repeat'] = "0";  // Repeats box not checked
      $args['days_every_week'] = "0"; // Days every week box not checked
      $args['recurrspec'] = unserialize($_POST['recurrspec']);
      $args['form_allday'] = $_POST['eventAllDay'];
      $args['form_apptstatus'] = $_POST['apptStatus'];
      $args['form_prefcat'] = $_POST['prefCategory'];
      $args['locationspec'] = $_POST['locationspec'];
      $args['facility'] = $_POST['facility'];
      $args['billing_facility'] = $_POST['billingFacility'];
      $args['form_room'] = $_POST['room'];
  // Insert event
  InsertEvent($args);  // it also takes care of tracker status
  echo "query executed";
}

?>
