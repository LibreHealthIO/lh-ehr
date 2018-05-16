<?php 
/**
 * Administration of users who have access to the updater
 *
 *
 * Copyright (C) 2018 Naveen Muthusamy <kmnaveen101@gmail.com>
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Naveen Muthusamy <kmnaveen101@gmail.com>
 * @link    http://librehealth.io
 */
require '../interface/globals.php';
require '../library/headers.inc.php';
call_required_libraries(array("jquery-min-3-1-1","bootstrap", "font-awesome", "iziModalToast", "jquery-ui"));
echo "<div id='accordion-1'>";
if($_SESSION['authUser'] == "admin" && !(isset($_GET['id']) && isset($_GET['mode']))) {
	echo "<h3><i class='fa fa-users'></i> ".xlt("Updater Administration")."</h3><div>";
	//only admin can add, edit, delete users who can update the application
	$query = sqlQ("SELECT * FROM `users` WHERE authorized != '1'");
	echo "<table class='table table-striped'>";
	echo "<th>username</th>";
	echo "<th>Authorized</th>";
	echo "<th>Action</th>";
	echo "<th>Added On</th>";
	while($array = sqlFetchArray($query)) {
		$authUserId = $array['id'];
		$sql = sqlQ("SELECT * FROM `updater_users` WHERE authUserId='$authUserId'");
		$r = sqlFetchArray($sql);
		$rows = sqlNumRows($sql);
		if ($rows == 1) {
			$authorised = "yes";
			$class = "color: green";
			$action = "<i class='fa fa-trash'></i> Remove User";
			$action_class = "color: red";
			$mode = "remove";
			$added_on = $r['date'];
		}
		else {
			$authorised = "no";
			$class = "color: red";
			$action = "<i class='fa fa-plus'></i> Add User";
			$action_class = "color: green";
			$mode = "add";
			$added_on = "-";
			//it means he is not authorised
		}
		$arr = sqlFetchArray($sql);
		$username = $array['username'];
		echo "<tr>
				<td>$username</td>
				<td><i style='$class'>$authorised</i></td>
				<td class='btn'><a href='index.php?id=$authUserId&mode=$mode' style='$action_class'>$action</a></td>
				<td>$added_on</td>
			</tr>";

	}
	echo "</table>
		  </div>";
	//	  
	
}
else {
	//dont show addition form
}
echo '</div>';
//Modes and Actions
//add => adds a user to manage updater
//remove => removes a user by authUserId
if (isset($_GET['mode']) && isset($_GET['id'])) {
	if (!empty($_GET['mode']) && !empty($_GET['id'])) {
		if($_SESSION['authUser'] == "admin") {
			// only perform actions if he is a admin
			$mode = $_GET['mode'];
			$authUserId = $_GET['id'];
			if ($mode == "add") {
				//add a user id to the table
				sqlQ("INSERT INTO `updater_users`(`authUserId`, `date`) VALUES ($authUserId, NOW())");
			}
			elseif ($mode == "remove") {
				sqlQ("DELETE FROM `updater_users` WHERE authUserId='$authUserId'");
			}
			header("location: index.php");
		}
	}
}


?>

<script>
   $(function() {
       $( "#accordion-1" ).accordion({
       	collapsible: true
       });
    });
</script>