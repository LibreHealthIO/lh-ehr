<?php
/*
 *  get_event_parameters.php - processing file which gets default recurring parameters values from Database
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
