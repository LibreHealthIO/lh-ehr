<?php 

require_once('../../../globals.php');

$query = "SELECT id, lname, mname, fname, suffix FROM users
  WHERE authorized = 1 AND username != '' AND active = 1
  ORDER BY fname, lname";
 
$res = sqlStatement($query);

$resources = array();

while ($row = sqlFetchArray($res)) {
  $r = array();
  $r = $row;
  $r['id'] = $row['id'];
  $r['title'] = $row['fname'] . " " . $row['lname'];
  
  // Merge the provider array into the return array
  array_push($resources, $r);
}

// Output json for our calendar
echo json_encode($resources);
exit();

  
?>
