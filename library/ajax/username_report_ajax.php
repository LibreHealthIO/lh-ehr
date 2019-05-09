<?php
/**
 * library/ajax/user_name_report_ajax.
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
 $testing = false;

require_once("../../interface/globals.php");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/patient.inc");
$DateFormat = DateFormatRead();
//make sure to get the dates
if ( ! isset($_POST['from_date'])) {

    $from_date = fixDate(date($DateFormat));

} else {
    $from_date = fixDate($_POST['from_date']);
}

if ( !isset($_POST['to_date'])) {
    // If a specific patient, default to 2 years ago.
    $to_date = fixDate(date($DateFormat));


} else{

    $to_date = fixDate($_POST['to_date']);
}

$to_date = new DateTime($to_date);
$to_date->modify('+1 day');
$to_date = $to_date->format('Y-m-d');

function getMaxLogIn($user){
    $query = "Select date from log where event = 'login' and user = ? order by date desc limit 0,1";
    $result = sqlQuery($query, array($user));

    return $result['date'];

}

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
        return " Limit 0,10";
    else return "";

}

//This function will find the date-time of the last activity
function getLastSessionActivityTime($user, $date){
    $query = "Select date from log where date <= ? and user = ? order by date desc limit 0,1";
    $result = sqlQuery($query, array( $date, $user));

    return $result['date'];

}

function getNextLogInDate($user, $date){

    //Get the next following logIn date
    $query = "Select date from log where date > ? and event = 'login' and user = ? limit 0,1";
    $result = sqlQuery($query, array($date, $user));
    $nextLogInDateTime = $result['date'];
    return $nextLogInDateTime;

}

function getNextLogOutDate($user, $date){

    $query = "Select date from log where date > ? and event = 'logout' and user = ? limit 0,1";
    $result = sqlQuery($query, array($date, $user));

    $nextLogOutDateTime = $result['date'];

    return $nextLogOutDateTime;
}

function getNextEvent($user, $date){

    $query = "Select event  from log where date > ? and user = ? limit 0,1";
    $result = sqlQuery($query, array($date, $user));

    $nextEvent = $result['event'];

    return $nextEvent;
}



function getSessionTime($user, $date){
    $session = 0;

	//get the datetime of the next login.
    $logInDateTime     = strtotime($date);
    $sessionTimeout    = $GLOBALS['timeout'];
    $logOutDate    = getNextLogOutDate($user, $date);
    $logOutDateTime    = $logOutDate ? strtotime($logOutDate) : 0;
    $nextLogInDate = getNextLogInDate($user, $date );
    $nextLogInDateTime = $nextLogInDate ? strtotime($nextLogInDate) : 0;
    $lastSessionActivityDate= getLastSessionActivityTime($user, $nextLogInDate);
    $lastSessionActivity = $lastSessionActivityDate ? strtotime($lastSessionActivityDate) : 0;
    $nextEvent = getNextEvent($user, $date);
    $maxLogin = getMaxLogIn($user);

    //If there isn't a next event, nothing happens so return 0
    if($nextEvent == null){

        return 0;
    }

    //if the next event is a login, we either return the timeout or the difference to the next
    //login, which ever is shorter.
    if($nextEvent == 'login'){

        if(($nextLogInDateTime - $logInDateTime) > $sessionTimeout){

            $session = round(($sessionTimeout) / (60 * 60), 2);
            return $session;

        }else{

            $session = round(($nextLogInDateTime - $logInDateTime) / (60 * 60), 2);
            return $session;

        }
    }


    //if the next event is a logout, we either return the timeout or the difference to the next
    //logout, which ever is shorter.
    if($nextEvent == 'logout'){

        if(($logOutDateTime - $logInDateTime) > $sessionTimeout){

            $session = round(($sessionTimeout) / (60 * 60), 2);
            return $session;

        }else{

            $session = round(($logOutDateTime - $logInDateTime) / (60 * 60), 2);
            return $session;
        }
    }

    //There are events that exist.  We get the last activity before the next login.
    //This can be a logout or an activity.

    if($lastSessionActivity > 0  && (($lastSessionActivity - $logInDateTime) < $sessionTimeout)){

       $session = round(($lastSessionActivity - $logInDateTime) / (60 * 60), 2);
        return $session;

    }else {

        if($logInDateTime == strtotime($maxLogin)){
            return 0;
        }else return round(($sessionTimeout) / (60 * 60), 2);
    }

    return 0;
}

//takes in the user, and date range and returns the sum of sessions
function getSumSessionsByUser($user, $from_date = '', $to_date = ''){
    $sum = 0;
    //get the list of dates and put it in an array
    $query = "select date from log where event ='login' and user = ? ";
        if($to_date != '')
            $query .= " and date >= ? ";
        if($from_date != '')
            $query .= " and date <= DATE_ADD( ? , INTERVAL 1 DAY) ";

        $result = sqlStatement($query, array($user, $from_date, $to_date));

    //iterate through each of the dates. get the session time, and sum them
    $oldDate = '';
    $newDate = '';
    while ($row = sqlFetchArray($result)) {
        $newDate = $row['date'];
        if($newDate === $oldDate)continue;
        $sum = $sum + getSessionTime($user, $row['date']);
        $oldDate = $newDate;
    }

    //return the sum

    return $sum;
}








//To list out records with given criteria
//To list out records with given criteria
if($_POST['func']=="show_session_sums") {
    $response['data'] = array();
    $user_array = getUsersArray();
    $index = 0;
    foreach($user_array as $user){
        $row = sqlQuery( "Select username, lname, fname from users where username = '$user'");
        $row['sum'] = getSumSessionsByUser($user, $from_date, $to_date);
        $row['date'] = "Date Range: " . date($DateFormat, strtotime($from_date)) . " - " . date($DateFormat, strtotime($to_date));
        array_push($response['data'], $row);
    }
    echo json_encode($response);

}

if($_POST['func']=="show_session_times")
{
    $response['data'] = array();


    $qstring = "Select  date, event, username as user, fname, lname  from log join users on username = user where event like '%login%' and event not like '%attempt%' and users.active = 1";

    $qstring .= " and date > ? ";



    $qstring .= " and date < ? ";
    ini_set('memory_limit', '1000M');

    $result = sqlStatement($qstring, array($from_date, $to_date));

    $gua_string = getUsersArray();
    $newLogIn = '';
    $oldLogIn = '';
    while ($row = sqlFetchArray($result)) {

        if ($row['event'] == 'login') {
            $newLogIn = $row['date'];
            if($newLogIn === $oldLogIn) continue;
            $row['session_time'] = getSessionTime($row['user'], $row['date']);
            array_push($response['data'], $row);
            $oldLogIn = $newLogIn;
        }
    }

    echo json_encode($response);
}

if($_POST['func']=="show_session_details") {
    $response['data'] = array();
    $user_array = getUsersArray();
    $index = 0;
    $qstring = "Select  date, user, event, comments from log join users on username = user where event not like '%admin%' and event not like '%attempt%' and users.active = 1";
    $qstring .= " and date > ? ";
    $qstring .= " and date < ? ";
    ini_set('memory_limit', '1000M');
    $result = sqlStatement($qstring, array($from_date, $to_date));


    while ($row = sqlFetchArray($result)) {
       array_push($response['data'], $row);
    }

    echo json_encode($response);

}







?>
