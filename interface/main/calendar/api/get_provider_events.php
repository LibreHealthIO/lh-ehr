<?php 

require_once('../../../globals.php');

$query = "SELECT a.*, b.*, u.*, pd.* FROM
  libreehr_postcalendar_events AS a
  LEFT JOIN libreehr_postcalendar_categories AS b ON b.pc_catid = a.pc_catid 
  LEFT JOIN users as u ON a.pc_aid = u.id 
  LEFT JOIN patient_data as pd ON a.pc_pid = pd.pid
  WHERE  a.pc_eventstatus = 1";
 
$res = sqlStatement($query);

$events = array();

while ($row = sqlFetchArray($res)) {
  $e = array();
  $e = $row;
  $e['id'] = $row['pc_eid'];
  $e['resourceId'] = $row['pc_aid'];
  $e['title'] = $row['pc_title'];
  $e['start'] = $row['pc_eventDate'] . " " . $row['pc_startTime'];
  $e['end'] = $row['pc_eventDate'] . " " . $row['pc_endTime'];
  // if it's In Office event, get the corresponding out of office; TODO
  $e['allDay'] = false;
  $e['rendering'] = 'background';
  
  // Merge the event array into the return array
  array_push($events, $e);
}

// Output json for our calendar
echo json_encode($events);
exit();

  
?>
