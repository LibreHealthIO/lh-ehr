<?php
/**
 * library/ajax/claims_viewer_ajax.
 * file adapted to present user activity log
 * Copyright (C) 2012 Medical Information Integration <info@mi-squared.com>
 * Copyright (c) 2018 Growlingflea Software <daniel@growlingflea.com>
 *
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
 *
 */
 $fake_register_globals=false;
 $sanitize_all_escapes=true;
 $testing = false;

require_once("../../interface/globals.php");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/patient.inc");

//make sure to get the dates
if ( ! $_POST['from_date']) {

    $from_date = fixDate(date($DateFormat));

} else {
    $from_date = fixDate($_POST['from_date']);
}

if ( !$_POST['to_date']) {
    // If a specific patient, default to 2 years ago.
    $to_date = fixDate(date($DateFormat));

} else{

    $to_date = fixDate($_POST['to_date']);
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

//This function will find the date-time of the last
function getLastSessionActivityTime($user, $date){
    $query = "Select date from log where user = ? and date < ? order by date desc limit 0,1";
    $result = sqlStatement($query, array($user, $date));
    $row = sqlFetchArray($result);
    return $row['date'];

}

function getSessionTime($user, $date){
	//get the datetime of the next login.
	$query = "Select min(date) as date from log where date > ? and event = 'login' and user = ?";

    $result = sqlStatement($query, array($date, $user));
	$row = sqlFetchArray($result);
    //send the next login time to get the last session activity of the current session
    if($row['date'] == null){

        return 0;

    }else {
        $date2 = getLastSessionActivityTime($user, $row['date']);
        $datetime1 = strtotime($date);
        $datetime2 = strtotime($date2);

        $interval = $datetime2 - $datetime1;

        $interval = round($interval / (60 * 60), 2);

        return $interval;

    }
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

    while ($row = sqlFetchArray($result)) {
        $sum = $sum + getSessionTime($user, $row['date']);

    }

    //return the sum

    return $sum;
}






//To list out records with given criteria
if($_POST['func']=="list_all_users")
{


	$qstring = "Select  *, log.id as logid from log join users on username = user where event like '%login%' and event not like '%attempt%' and users.active = 1";

    $qstring .= " and date > '{$from_date}' ";



    $qstring .= " and date < '{$to_date} 23:59:59' ";


    $qstring .= ifTestingTrue($testing);
	$result = sqlStatement($qstring);

    $gua_string = getUsersArray();

	while ($row = sqlFetchArray($result)) {
		if ($row['event'] == 'login') {
			$diff = getSessionTime($row['user'], $row['date']);

			?>

			<tr id="<?= $row["logid"]; ?>">
				<td align="center"><?= xl($row["date"]); ?></td>
				<td align="center"><?= xl($row["user"]); ?></td>
				<td align="center"><?= xl($row["lname"]); ?></td>
				<td align="center"><?= xl($row["fname"]); ?></td>
				<td align="center"><?php echo xl($diff); ?></td>
			</tr>
			<?php
		}
	}
}

//To list out records with given criteria
if($_POST['func']=="user_summary") {

    $response = array();
    $user_array = getUsersArray();
    foreach($user_array as $user){
        $row = sqlQuery( "Select * from users where username = '$user'");
        $sum = getSumSessionsByUser($user, $from_date, $to_date);
    ?>
        <tr>

            <td></td>
            <td align="center"><?= xl($user); ?></td>
            <td align="center"><?= xl($row['lname']); ?></td>
            <td align="center"><?= xl($row['fname']); ?></td>
            <td align="center"><?= xl("TOTALS: " . $sum); ?></td>
        </tr>
    <?php
    }

}



?>
