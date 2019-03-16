<?php
/**
 * library/ajax/clinical_stats_and_lab_stats_by_demographics_ajax.php: handles ajax calls from demographic lab/procedure reports
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
    $queryArray = array();
    $query = " SELECT type, l.title, diagnosis, l.activity, l.pid, dob, status, sex, list_options.title as ethnicity, monthly_income " .
            " FROM `lists` l join patient_data pd on pd.pid = l.pid " .
            " join list_options on option_id = ethnicity and list_id = 'ethnicity' where type = 'medical_problem'  ";

     if(! empty($_POST['diag'])){
       array_push($queryArray, $_POST['diag'] );
       $query .= ' AND diagnosis = ?';
     }


    if(! empty($_POST['ethnicity'])){
        array_push($queryArray, $_POST['ethnicity'] );
        $query .= ' AND ethnicity = ?';
    }

    if(! empty($_POST['min_age'])){
        $min = $_POST['min_age'];
        $date = strtotime("-$min year");
        $date = date('Y-m-d', $date);
        array_push($queryArray, $date);
        $query .= ' AND dob <= ?';
    }

    if(! empty($_POST['max_age'])){
        $max = $_POST['max_age'];
        $date = strtotime("-$max year");
        $date = date('Y-m-d', $date);
        array_push($queryArray, $date );
        $query .= ' AND dob >= ?';
    }

    $query .= " AND ? "; //handles the case where nothing is being queried
    array_push($queryArray, 1 );
    $result = sqlStatement($query, $queryArray);

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
    $queryArray = array();
    
    $query  = "select pd.pid as pid, pd.sex as gender, pd.dob, list_options.title as ethnicity, pres.result_text, pres.result, pres.abnormal from procedure_result pres ";
    $query .= "join procedure_report prep on pres.procedure_report_id = prep.procedure_report_id " ;
    $query .= "join procedure_order prord on prep.procedure_order_id = prord.procedure_order_id " ;
    $query .= "join patient_data pd on pd.pid = prord.patient_id ";
    $query .= "join list_options on option_id = pd.ethnicity and list_id = 'ethnicity' ";
    $query .= "where  pres.result_text != '' and pres.abnormal != '' ";

    if(! empty($_POST['min_age'])){
        $min = $_POST['min_age'];
        $date = strtotime("-$min year");
        $date = date('Y-m-d', $date);
        array_push($queryArray, $date);
        $query .= ' AND dob <= ?';
    }

    if(! empty($_POST['max_age'])){
        $max = $_POST['max_age'];
        $date = strtotime("-$max year");
        $date = date('Y-m-d', $date);
        array_push($queryArray, $date );
        $query .= ' AND dob >= ?';
    }



    ini_set('memory_limit', '1000M');
    $result = sqlStatement($query, $queryArray);

    while ($row = sqlFetchArray($result)) {
        $row['gender'] = ($row['gender'] == 'Male') ? 'M' : 'F';
        array_push($response['data'], $row);
    }
    $test = json_encode($response);
    echo json_encode($response);


}



?>
