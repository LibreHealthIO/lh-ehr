<?php

require_once("../../interface/globals.php");
require_once("$srcdir/htmlspecialchars.inc.php");

if ($_POST['action'] == "drag") {
  // event is dragged - update date, start time, end time and provider id
  sqlStatement("UPDAT libreehr_postcalendar_events
                SET pc_eventDate = ?, pc_startTime = ?, pc_endTime = ?, pc_aid = ?
                WHERE pc_eid = ?", array($_POST['date'], $_POST['startTime'], $_POST['endTime'], $_POST['providerId'], $_POST['id']));
  echo "query executed";
} elseif ($_POST['action'] == "resize") {
  // event is resized - update start time, end time
  sqlStatement("UPDATE libreehr_postcalendar_events
                SET pc_startTime = ?, pc_endTime = ?
                WHERE pc_eid = ?", array($_POST['startTime'], $_POST['endTime'], $_POST['id']));
  echo "query executed";
}

?>
