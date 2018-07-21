<?php
/*
 *  track_appointments_ajax.php - Processing file of Track Appointments tab - data gathering, paging, sorting, filtering.
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

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;

require_once("../../globals.php");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/formatting.inc.php");

$popup = empty($_REQUEST['popup']) ? 0 : 1;

// $_GET['sColumns'] gets column (reference) names as a comma-separated string,
// which is "pc_eventDate,pc_startTime,pc_aid,pc_apptstatus,pc_title"
$aColumns = explode(',', $_GET['sColumns']);

// Server side will handle 3 features - paging, sorting, filtering
// $limit decides paging
// $orderby decides sorting
// $where decides filtering

// 1. paging parameters
$iDisplayStart  = isset($_GET['iDisplayStart']) ? 0 + $_GET['iDisplayStart'] : -1;  // display start point in the current data set
$iDisplayLength = isset($_GET['iDisplayLength']) ? 0 + $_GET['iDisplayLength'] : -1;  // number of records that table can display in the current draw
$limit = '';
if ($iDisplayStart >= 0 && $iDisplayLength >= 0) {
  // no LIMIT if value == -1 (when value is not set)
  // escape_limit() escapes a sql limit variable to prepare for a sql query
  $limit = "LIMIT " . escape_limit($iDisplayStart) . ", " . escape_limit($iDisplayLength);
}

// 2. sorting (column) parameters
$orderby = '';
if (isset($_GET['iSortCol_0'])) {  // iSortCol_(int) refers to the column being sorted on
  for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {  // iSortingCols is the number of columns to sort on
    $iSortCol = intval($_GET["iSortCol_$i"]);
    if ($_GET["bSortable_$iSortCol"] == "true" ) {  // bSortable_(int) indicates if a column is flagged as sortable or not on client-side
      // escape_sort_order() escapes a sql sort order keyword variable to prepare for a sql query
      $sSortDir = escape_sort_order($_GET["sSortDir_$i"]);  // sSortDir_(int) specifies direction to be sorted - "desc" or "asc"
      // sorting on column number $iSortCol in direction $sSortDir
      $orderby .= $orderby ? ', ' : 'ORDER BY ';
      if ($aColumns[$iSortCol] == "pc_aid") {
        $orderby .= "users.fname $sSortDir, users.lname $sSortDir";
      } elseif ($aColumns[$iSortCol] == "pc_apptstatus") {
        $orderby .= "options.title $sSortDir";
      } else {
        // escape_sql_column_name() escapes a sql column name variable to prepare for a sql query
        $orderby .= "`" . escape_sql_column_name($aColumns[$iSortCol],array('libreehr_postcalendar_events')) . "` $sSortDir";
      }
    }
  }
}

// 3. filtering (on all columns)
$where = "";
$flag = false;
// range filtering values for appointment dates
$from_date = $_GET['fromDate'];
$to_date = $_GET['toDate'];
if (isset($_SESSION['selected_pat_id']) && $_SESSION['selected_pat_id'] !== "") {
  if (isset($from_date) && $from_date !== "") {
    if (isset($to_date) && $to_date !== "") {
      // if both from & to dates are set
      $where .= " WHERE pc_pid = '" . $_SESSION['selected_pat_id'] . "' AND ((events.pc_endDate >= '$from_date' AND events.pc_eventDate <= '$to_date' AND events.pc_recurrtype > '0') OR (events.pc_eventDate >= '$from_date' AND events.pc_eventDate <= '$to_date'))";
    } else {
      // if only from date is set
      $where .= " WHERE pc_pid = '" . $_SESSION['selected_pat_id'] . "' AND ((events.pc_endDate >= '$from_date' AND events.pc_recurrtype > '0') OR (events.pc_eventDate >= '$from_date'))";
    }
  } else {
    if (isset($to_date) && $to_date !== "") {
      // if only to date is set
      $where .= " WHERE pc_pid = '" . $_SESSION['selected_pat_id'] . "' AND ((events.pc_eventDate <= '$to_date' AND events.pc_recurrtype > '0') OR (events.pc_eventDate <= '$to_date'))";
    } else {
      // if neither of from & to dates are set
      $where .= " WHERE pc_pid = '" . $_SESSION['selected_pat_id'] . "'";
    }
  }
  $flag= true;
}
if (isset($_GET['sSearch']) && $_GET['sSearch'] !== "") {  // sSearch gets string out of Global search field
  // add_escape_custom() escapes a parameter (typed string in this case) to prepare for a sql query
  $sSearch = add_escape_custom($_GET['sSearch']);
  if ($flag) {
    $where .= " AND ( ";
  } else {
    $where .= " WHERE ( ";
  }
  foreach ($aColumns as $colname) {
    if ($colname == "pc_aid") {
      $where .= "users.fname LIKE '$sSearch%' OR users.lname LIKE '$sSearch%'";
    } elseif ($colname == "pc_apptstatus") {
      $where .= "options.title LIKE '$sSearch%'";
    } else {
      // escape_sql_column_name() escapes a sql column name variable to prepare for a sql query
      $where .= "`" . escape_sql_column_name($colname, array('libreehr_postcalendar_events')) . "` LIKE '$sSearch%'";
    }
    $where .= " OR ";
  }
  if ($where) {
    $where .= ")";
    $where = substr($where, 0, strpos($where, ")") - 4);
    $where .= " )";
  }
}

// $select_list is a string of column names to select in main query:
// "pc_eid, `pc_eventDate`, `pc_startTime`, users.fname, users.lname, options.title, `pc_title`"
// for appt. eid, date, time, provider first name, last name, status, category respectively
$select_list = "pc_eid";
foreach ($aColumns as $colname) {
  if ($colname == "pc_eid") {
    continue;
  }
  $select_list .= ", ";
  if ($colname == "pc_aid") {
    $select_list .= "users.fname, users.lname";
  } elseif ($colname == "pc_apptstatus") {
    $select_list .= "options.title";
  } else {
    // escape_sql_column_name() escapes a sql column name variable to prepare for a sql query
    $select_list .= "`" . escape_sql_column_name($colname, array('libreehr_postcalendar_events')) . "`";
  }
}

// Get total number of rows in the table
$row = sqlQuery("SELECT COUNT(pc_eid) AS count FROM libreehr_postcalendar_events");
$iTotal = $row['count'];

// Get total number of rows in the table after filtering
$row = sqlQuery("SELECT COUNT(pc_eid) AS count
                 FROM libreehr_postcalendar_events as events
                 JOIN users ON users.id = events.pc_aid
                 JOIN list_options as options ON options.option_id = events.pc_apptstatus
                 $where");
$iFilteredTotal = $row['count'];

// specifying output data array
$output_array = array(
  "sEcho"                => intval($_GET['sEcho']),
  "iTotalRecords"        => $iTotal,
  "iTotalDisplayRecords" => $iFilteredTotal,
  "aaData"               => array()
);

// main query to get appointments of a patient
$query = "SELECT $select_list
          FROM libreehr_postcalendar_events as events
          JOIN users ON users.id = events.pc_aid
          JOIN list_options as options ON options.option_id = events.pc_apptstatus
          $where $orderby $limit";
$result = sqlStatement($query);
while ($row = sqlFetchArray($result)) {
  // each data row (appointment) <tr></tr> will have corresponding eid as ID
  $arow = array('DT_RowId' => 'eid_' . $row['pc_eid'], 'DT_RowClass' => 'data-row');
  foreach ($aColumns as $colname) {
    if ($colname == "pc_eventDate") {
      // oeFormatShortDate() converts yyyy-mm-dd to mm/dd/yyyy or dd/mm/yyyy as per Admin > Locale > Date Display Format
      $arow[] = oeFormatShortDate($row[$colname]);
    } elseif ($colname == "pc_startTime") {
      // oeFormatTime() converts HH:mm:ss to 12 hour or 24 hour format as per Admin > Locale > Time Display Format
      $arow[] = oeFormatTime($row[$colname]);
    } elseif ($colname == "pc_aid") {
      $provider_name = "{$row['fname']} {$row['lname']}";
      $arow[] = $provider_name;
    } elseif ($colname == "pc_apptstatus") {
      $status_name = "{$row['title']}";
      $arow[] = $status_name;
    } else {
      $arow[] = $row[$colname];
    }
  }
  $output_array['aaData'][] = $arow;
}

// ouput the data array as a JSON string
echo json_encode($output_array);
?>
