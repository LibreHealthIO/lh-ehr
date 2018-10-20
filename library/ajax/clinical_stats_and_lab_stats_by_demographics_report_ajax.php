<?php
/**
 * library/ajax/clinical_stats_and_lab_stats_by_demographics.php: handles ajax calls from demographic lab/procedure reports
 * file adapted to present user activity log
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/mpl-license.php>;.
 * Copyright (c) 2018 Growlingflea Software <daniel@growlingflea.com>
 * File adapted for user activity log.
 * @package LibreEHR
 * @author  Daniel Pflieger daniel@growlingflea.com daniel@mi-squared.com
 */
 $fake_register_globals=false;
 $sanitize_all_escapes=true;
 $testing = true;


require_once("../../interface/globals.php");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/patient.inc");



//Enter false if searching for users that aren't active
function getUsersArray($active = true){

    $users = array();
    $query = "Select distinct(username) from users ";
    $query .= $active = true ? "where active = 1" : " ";
    $query .= " AND username != ''";
    $result = sqlStatement($query);
    while ($row = sqlFetchArray($result)) {
        array_push($users, $row['username']);

    }
    return $users;
}

function ifTestingTrue($testing){

    if($testing)
        return " Limit 0,100";
    else return "";

}


if($_POST['func']=="get_all_diags_data")
{
    $response['data'] = array();

    $query = "SELECT patient_data.pid, issue_encounter.encounter, form_encounter.date, type, lists.title, lists.diagnosis, activity, dob, city, state, status, sex, race, ethnicity ";
    $query .= "FROM `issue_encounter` ";
    $query .= "join lists on list_id = lists.id ";
    $query .= "join patient_data on lists.pid = patient_data.pid ";
    $query .= "join form_encounter on issue_encounter.encounter = form_encounter.encounter";


    ini_set('memory_limit', '1000M');
    $result = sqlStatement($query);

    while ($row = sqlFetchArray($result)) {
        $row['sex'] = ($row['sex'] == 'Male') ? 'M' : 'F';
        array_push($response['data'], $row);



    }

    $test = json_encode($response);
    echo json_encode($response);



}


if($_POST['func']=="get_all_lab_data")
{
    $response['data'] = array();

    $query  = "select pd.pid as pid, pd.sex as gender, pd.dob, pd.ethnicity, pres.result_text, pres.result, pres.abnormal from procedure_result pres ";
    $query .= "join procedure_report prep on pres.procedure_report_id = prep.procedure_report_id " ;
    $query .= "join procedure_order prord on prep.procedure_order_id = prord.procedure_order_id " ;
    $query .= "join patient_data pd on pd.pid = prord.patient_id ";
    $query .= "where  pres.result_text != '' and pres.abnormal != '' ";




    ini_set('memory_limit', '1000M');
    $result = sqlStatement($query);

    while ($row = sqlFetchArray($result)) {
        $row['gender'] = ($row['gender'] == 'Male') ? 'M' : 'F';
        array_push($response['data'], $row);
    }
    $test = json_encode($response);
    echo json_encode($response);


}



?>
