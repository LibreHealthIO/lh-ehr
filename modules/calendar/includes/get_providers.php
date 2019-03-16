<?php 
/*
 *  get_providers.php Gathering the providers for the Calendar
 *
 * Copyright (C) 2017 Ujjwal Arora <arora.ujjwal@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Ujjwal Arora <arora.ujjwal@gmail.com >
 * @author Terry Hill <teryhill@librehealth.io >
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */

require_once('../../../interface/globals.php');

if ($GLOBALS['calendar_provider_view_type'] =='resource') {
#Calendar Resources
$query = "SELECT id, title, billname FROM users
  WHERE authorized = 1 AND username != '' AND active = 1 
  AND username IN('" . implode("','", $_SESSION['pc_username']) . "')
  ORDER BY billname";
} elseif ($GLOBALS['calendar_provider_view_type'] =='full') {

$query = "SELECT id, lname, mname, fname, suffix FROM users
  WHERE authorized = 1 AND username != '' AND active = 1 
  AND username IN('" . implode("','", $_SESSION['pc_username']) . "')
  ORDER BY fname, lname";
} elseif ($GLOBALS['calendar_provider_view_type'] =='last') {
$query = "SELECT id, lname, mname, fname, suffix FROM users
  WHERE authorized = 1 AND username != '' AND active = 1 
  AND username IN('" . implode("','", $_SESSION['pc_username']) . "')
  ORDER BY lname";
} elseif ($GLOBALS['calendar_provider_view_type'] =='last_first') {
$query = "SELECT id, lname, mname, fname , suffix FROM users
  WHERE authorized = 1 AND username != '' AND active = 1 
  AND username IN('" . implode("','", $_SESSION['pc_username']) . "')
  ORDER BY lname,fname";
}
 
$res = sqlStatement($query);

$resources = array();

while ($row = sqlFetchArray($res)) {
  $r = array();
  $r = $row;
  $r['id'] = $row['id'];
  if ($GLOBALS['calendar_provider_view_type'] =='resource') {
    $r['title'] = $row['title'] . " " .$row['billname']; 
  } elseif ($GLOBALS['calendar_provider_view_type'] =='full') {
  $r['title'] = $row['fname'] . " " . $row['lname'];
  } elseif ($GLOBALS['calendar_provider_view_type'] =='last') {
    $r['title'] = $row['title'] . " " .$row['lname']; 
  } elseif ($GLOBALS['calendar_provider_view_type'] =='last_first') {
    $r['title'] = $row['lname'] . " " .substr($row['fname'],0,1); 
  }
  
  // Merge the provider array into the return array
  array_push($resources, $r);
}

// Output json for our calendar
echo json_encode($resources);
exit();

  
?>
