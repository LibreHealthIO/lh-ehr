<?php 

require_once('../../../globals.php');

$query = "SELECT a.*, b.*, u.*, pd.*, pd.lname as plname, pd.fname as pfname, a.pc_pid as e_pid FROM
  libreehr_postcalendar_events AS a
  LEFT JOIN libreehr_postcalendar_categories AS b ON b.pc_catid = a.pc_catid 
  LEFT JOIN users as u ON a.pc_aid = u.id 
  LEFT JOIN patient_data as pd ON a.pc_pid = pd.pid
  WHERE  a.pc_eventstatus = 1 AND u.username IN('" . implode("','", $_SESSION['pc_username']) . "')";

$res = sqlStatement($query);

$events = array();

while ($row = sqlFetchArray($res)) {
  
  // skip cancelled appointments
  if ($GLOBALS['display_canceled_appointments'] != 1) {
     if ($row['pc_apptstatus'] == "x") { continue; }
  }
  
  $e = array();
  $e = $row;
  $e['id'] = $row['pc_eid'];
  $e['resourceId'] = $row['pc_aid'];
  $e['title'] = $row['pc_title'];
  $e['start'] = $row['pc_eventDate'] . " " . $row['pc_startTime'];
  $e['end'] = $row['pc_eventDate'] . " " . $row['pc_endTime'];
  $e['allDay'] = ($e['pc_alldayevent'] == 1) ? true : false;
  $e['color'] = $row['pc_catcolor'];
  
  if($row["pc_pid"] > 0) {
    $e['description'] = $row['pc_apptstatus'] . " " . $row['plname'] . ", " . $row['pfname'] . " (" . $row['pc_title'];
    if(!empty($row["pc_hometext"])) {
      $e['description'] = $e['description'] . ": " . $row["pc_hometext"];
    }
    $e['description'] = $e['description'] . ")";
    switch($GLOBALS['calendar_appt_style']) {
      case 1:
        $e['title'] = $row['pc_apptstatus'] . " " . $row['plname'];
        break;
      case 2:
        $e['title'] = $row['pc_apptstatus'] . " " . $row['plname'] . ", " . $row['pfname'];
        break;
      case 3:
        $e['title'] = $row['pc_apptstatus'] . " " . $row['plname'] . ", " . $row['pfname'] . " (" . $row['pc_title'] . ")";
        break;
      case 4:
        $e['title'] = $e['description'];  // Case 4 is exactly the same as the event tooltip
        break;
      default:
        $e['title'] = $row['pc_apptstatus'] . " " . $row['plname'] . ", " . $row['pfname'];
    }
  } else {
    $e['description'] = $row['pc_title'];
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
