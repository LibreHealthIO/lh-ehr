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
require '../library/user.inc';
require 'template_handler.php';
call_required_libraries(array("jquery-min-3-1-1","bootstrap", "font-awesome", "iziModalToast", "jquery-ui"));
if (isset($_POST)) {
	if (!empty($_POST)) {
		foreach ($_POST as $key => $value) {
			echo "$key => $value";
		}
	}
}

//every section is displayed as accordion for ui friendly approach
echo "<div id='accordion-1'>";

//load general settings template in accordion
$loader->set_template_file("general_settings");
$loader->assign("SOME_VALUE", "ss");
$loader->output();

//load updater administration in accordion
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
	echo '</div>';
	
}
else {
	//dont show addition form
}

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
				$toast_type = "success";
				$toast_title = "User Added";
				$toast_message = "The user is added to updater administration"; 
			}
			elseif ($mode == "remove") {
				sqlQ("DELETE FROM `updater_users` WHERE authUserId='$authUserId'");
				$toast_type = "error";
				$toast_title = "User Removed";
				$toast_message = "The user was removed from updater administration"; 
			}
			header("location: index.php?accordion_index=1&toast_type=$toast_type&toast_title=$toast_title&toast_message=$toast_message");
		}
	}
}


?>

<script>
   $(function() {
       $( "#accordion-1" ).accordion({
       	collapsible: true,
       	heightStyle: "content"
       });
    });
<?php
	//if accordion index specified
	if (isset($_GET['accordion_index']) && isset($_GET['toast_type']) && isset($_GET['toast_message']) && isset($_GET['toast_title'])) {
		if (!empty($_GET['accordion_index']) && !empty($_GET['toast_title']) && !empty($_GET['toast_type']) && !empty($_GET['toast_message'])) {
			$index = $_GET['accordion_index'];
			$title = $_GET['toast_title'];
			$type = $_GET['toast_type'];
			$message = $_GET['toast_message'];
			echo "$('#accordion-1').accordion({active: $index});";
			echo "parent.showUpdaterNotifications('$type', '$title', '$message');";
		}
	}

?>
$('.updater_general_settings_parent').click(function () {
	if ($('.updater_general_settings_parent').is(":checked")) {
		$('.updater_general_settings_children').prop('disabled', true);
		$('.updater_general_settings_children').css('opacity', '0.2');
	}
});
</script>

<style type="text/css">
	/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 24px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #F69600;
}

input:focus + .slider {
  box-shadow: 0 0 1px #F69600;
}

input:checked + .slider:before {
  -webkit-transform: translateX(16px);
  -ms-transform: translateX(16px);
  transform: translateX(16px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 17px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>
