<?php
/*
 * Copyright (C) 2015 - 2017      Suncoast Connection
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */


require_once("../../interface/globals.php");
include_once("../../library/sql.inc");
require_once("../../library/acl.inc");

// 45 CFR 164.312(a)(1) Access Control: Require authenticated user session
if (empty($_SESSION['authUserID'])) {
    header('HTTP/1.1 401 Unauthorized');
    die(xlt('Authentication required.'));
}

// Check authorization to add/update encounters and billing
if (!acl_check('encounters', 'auth_a') && !acl_check('encounters', 'auth_write') && !acl_check('admin', 'super')) {
    header('HTTP/1.1 403 Forbidden');
    die(xlt('Access denied: insufficient privileges.'));
}

function AddCPT2CodeBilling($pid,$date,$ourCode,$encounter,$userID)
{
    $result = false;
    if ( $ourCode != "" ) {
        $codesplit=explode(":",$ourCode);
        $codeBase=$codesplit[0];
        $codeModifier = ( count($codesplit) > 1 ) ? $codesplit[1] : "";

        // Parameterized query to prevent SQL injection (45 CFR 164.312(a)(1))
        $query = "INSERT INTO `billing` ".
            " ( `date`, `code_type`, `code`, `pid`, ".
            " `provider_id`, `user`, `groupname`, `authorized`, ".
            " `encounter`, `billed`, `activity`, ".
            " `payer_id`, `bill_process`, `modifier`) ".
            " VALUES (?, 'CPT2', ?, ?, ?, ?, 'Default', '1', ?, '0', '1', '1', '0', ?)";
        $binds = array($date, $codeBase, (int)$pid, (int)$userID, (int)$userID, (int)$encounter, $codeModifier);
        $result = sqlStatement($query, $binds);
    }

    return $result;
}

function AddCPT2CodeEncounter($pid,$date,$passedCodes)
{
    $myuserID = (int)$_SESSION["authUserID"];
    $pid = (int)$pid;
    $myfacility = 1;
    $myprovider_id = (int)findProvider( $pid );

    // Is there already an encounter on our date? (parameterized query)
    $query = "SELECT COUNT(*) AS count FROM form_encounter WHERE pid = ? AND date = ?";
    $result = sqlQuery($query, array($pid, $date));

    if (!empty($result) && $result['count'] > 0 ) {
        $query = "SELECT encounter AS enc FROM form_encounter WHERE pid = ? AND date = ? LIMIT 1";
        $res = sqlQuery($query, array($pid, $date));
        $myencounterNumber = (int)$res['enc'];
    } else {
        // Find the next encounter number to use
        $query = "SELECT MAX(encounter) as enc FROM form_encounter";
        $tmpEncounterNumber = sqlQuery($query);
        $myencounterNumber = 1 + (int)$tmpEncounterNumber['enc'];

        $query = "INSERT INTO `form_encounter` ".
            " ( `date`, `reason`, `facility`, `facility_id`, ".
            " `pid`, `encounter`, `onset_date`, `sensitivity`, ".
            " `billing_note`, `pc_catid`, `provider_id`, `supervisor_id`, `billing_facility`) ".
            " VALUES (?, 'MIPS Direct Entry Input: See Fee Sheet', ?, '1', ?, ?, '0000-00-00 00:00:00', 'normal', 'MIPS CPT2 Entries', '9', ?, '0', '1')";
        sqlStatement($query, array($date, $myfacility, $pid, $myencounterNumber, $myprovider_id));

        // Now query and find out the form_id we just created
        $query = "SELECT id FROM form_encounter WHERE encounter = ? AND pid = ? LIMIT 1";
        $res = sqlQuery($query, array($myencounterNumber, $pid));
        $myFormID = (int)$res['id'];

        $query = "INSERT INTO `forms` ".
            " ( `date`, `encounter`, `form_name`, `form_id`, ".
            " `pid`, `user`, `groupname`, `authorized`, `deleted`, `formdir`) ".
            " VALUES (?, ?, 'Patient Encounter', ?, ?, ?, 'Default', '1', '0', 'patient_encounter')";
        sqlStatement($query, array($date, $myencounterNumber, $myFormID, $pid, $myuserID));
    }

    foreach (explode(" ", $passedCodes) as $ourCode ) {
        if ($ourCode !== '') {
            AddCPT2CodeBilling($pid, $date, $ourCode, $myencounterNumber, $myuserID);
        }
    }

    return "'pid=".$pid." / date=".$date." / codes=".$passedCodes." / provider_id=".$myprovider_id." / user=".$myuserID."'" ;
}

function findProvider($pid) {
    $find_provider = sqlQuery("SELECT providerID FROM patient_data WHERE pid = ? LIMIT 1", array((int)$pid) );
    return isset($find_provider['providerID']) ? $find_provider['providerID'] : 0;
}

function update_itemized_report( $myperformance,$myreport_id, $myitemized_test_id, $mypid) {
    // Parameterized update to prevent SQL injection
    $query = "UPDATE report_itemized SET pass = ? WHERE report_id = ? AND itemized_test_id = ? AND pid = ? AND numerator_label = 'Numerator'";
    sqlStatement($query, array($myperformance, $myreport_id, $myitemized_test_id, (int)$mypid));
}

// Begin Main
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mypid = isset($_POST['pid']) ? (int)$_POST['pid'] : 0;
    $mydate = isset($_POST['date']) ? trim($_POST['date']) : '';
    $mycodeRaw = isset($_POST['CPT2codevalue']) ? trim($_POST['CPT2codevalue']) : '';
    $mycode = (strlen($mycodeRaw) > 2) ? substr($mycodeRaw, 2) : '';
    $myperformance = (strlen($mycodeRaw) > 0) ? substr($mycodeRaw, 0, 1) : '';
    $myreport_id = isset($_POST['report_id']) ? trim($_POST['report_id']) : '';
    $myitemized_test_id = isset($_POST['itemized_test_id']) ? trim($_POST['itemized_test_id']) : '';

    if ($mypid > 0 && !empty($mydate) && !empty($mycode)) {
        $result = AddCPT2CodeEncounter($mypid, $mydate, $mycode);
        $result2 = update_itemized_report($myperformance, $myreport_id, $myitemized_test_id, $mypid);
        echo 'SUCCESS';
    } else {
        header('HTTP/1.1 400 Bad Request');
        echo 'ERROR: Missing required parameters.';
    }
}
?>
