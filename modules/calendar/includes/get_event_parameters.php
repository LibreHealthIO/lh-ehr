<?php
require_once("../../../interface/globals.php");
require_once("$srcdir/htmlspecialchars.inc.php");

// when changing category in event panel for a new event,
// send corresponding default recurring parameters values
if (!empty($_POST['action']) && $_POST['action'] == "getRecurringParameters") {
  $query = "SELECT pc_recurrtype, pc_recurrspec, pc_enddate
            FROM libreehr_postcalendar_categories
            WHERE pc_catid = ?";
  $res = sqlStatement($query, array($_POST['catId']));
  $def_parameters = array();  // associative array to be sent back
  while ($row = sqlFetchArray($res)) {
    $def_parameters['repeats'] = $row['pc_recurrtype'];
    $rspecs = unserialize($row['pc_recurrspec']); // extract recurring data
    $def_parameters['repeattype'] = $rspecs['event_repeat_freq_type'];
    $def_parameters['repeatfreq'] = $rspecs['event_repeat_freq'];
    $def_parameters['repeatexdate'] = $rspecs['exdate'];
    $def_parameters['repeatenddate'] = $row['pc_enddate'];
  }

  $jsonString = json_encode($def_parameters);
  if ($jsonString !== false) {
    echo $jsonString;  // send parameters as a JSON string to add_edit_event.php
  } else {
    echo "encode error";
  }
}
?>
