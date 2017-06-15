<?php 

require_once('../../../globals.php');

$query = "SELECT a.*, b.*, u.*, pd.* FROM
  libreehr_postcalendar_events AS a
  LEFT JOIN libreehr_postcalendar_categories AS b ON b.pc_catid = a.pc_catid 
  LEFT JOIN users as u ON a.pc_aid = u.id 
  LEFT JOIN patient_data as pd ON a.pc_pid = pd.pid
  WHERE  a.pc_eventstatus = 1 AND u.username IN('" . implode("','", $_SESSION['pc_username']) . "')";

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
  $e['allDay'] = ($e['pc_alldayevent'] == 1) ? true : false;
  
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
