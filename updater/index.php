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
require 'lib/updater_functions.php';
call_required_libraries(array("jquery-min-3-1-1","bootstrap", "font-awesome", "iziModalToast", "jquery-ui"));
$userAuthorized = $_SESSION['userauthorized'];
$authUserId = $_SESSION['authUserID'];
if (checkAdmin($userAuthorized, $authUserId)) {
	//allow to access this page
}
else {
	die("You are not allowed to handle updater administration");
}
?>
<link rel="stylesheet" type="text/css" href="css/index.css">
<?php
//POST HANDLER FOR THE GENERAL SETTINGS SAVE
if (isset($_POST['from_updater_general_settings'])) {
	if (!empty($_POST['from_updater_general_settings'])) {
		$from_updater_general_settings = $_POST['from_updater_general_settings'];
		if ($from_updater_general_settings) {
				$boolean_array = array();

				// TO HANDLE CHECKBOXES IN THE UPDATER GENERAL SETTINGS
				foreach ($updater_general_setting_checkboxes as $key => $value) {
						$settingName = $key;
						$settingValue = $value;
						$bool = setUpdaterSetting($settingName, $settingValue);
						array_push($boolean_array, $bool);

				}
				//TO HANDLE ALL OTHER INPUT BOXES IN THE UPDATER FIELD
				foreach ($_POST as $key => $value) {
					if ($key == "from_updater_general_settings") {
						//it is used only in loops to identify post request, so dont save in db
					}
					else {
						$settingName = $key;
						$settingValue = $value;
						if ($settingValue == "on") {
							$settingValue = 1;
						}
						$bool = setUpdaterSetting($settingName, $settingValue);
						array_push($boolean_array, $bool);
					}
				}
				if (in_array("0", $boolean_array)) {
					$toast_type = "error";
					$toast_title = xlt("ERROR");
					$toast_message = xlt("There is an error in saving settings");
				}
				else {
					$toast_type = "success";
					$toast_title = "SUCCESS";
					$toast_message = "Settings have been successfully saved";

				}
				header("location: index.php?accordion_index=&toast_type=$toast_type&toast_title=$toast_title&toast_message=$toast_message");
		}

	}
}

//every section is displayed as accordion for ui friendly approach
echo "<div id='accordion-1'>";

//load general settings template in accordion
$sql = "SELECT * FROM updater_settings";
$query = sqlQ($sql);
$loader->set_template_file("general_settings");
$rows = sqlNumRows($query);
if ($rows > 0) {
while ($r = sqlFetchArray($query)) {
	$setting_name = $r['name'];
	$setting_value = getUpdaterSetting($setting_name);
	if ($setting_value == "empty_setting") {
		$setting_value = xlt("Not Set");
	}
	if ($setting_value == "0") {
		$setting_value = "off";
		$setting_checkbox_name = $setting_name."_checked";
		$loader->assign($setting_checkbox_name, " ");
	}
	if ($setting_value == "1") {
		$setting_value = "on";
		$setting_checkbox_name = $setting_name."_checked";
		$loader->assign($setting_checkbox_name, "checked");
	}
	$loader->assign($setting_name, $setting_value);

}
}
else {
	foreach ($updater_general_setting_input_fields as $key => $value) {
		$loader->assign($value, "not set");
	}
}

$loader->output();

//load updater administration in accordion
if($userAuthorized == 1) {
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
		$sql = sqlStatement("SELECT * FROM `updater_users` WHERE authUserId=?", $bindarray = array($authUserId));
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
	echo '</div>';
	//dont show addition form for other users
}

//Modes and Actions
//add => adds a user to manage updater
//remove => removes a user by authUserId
if (isset($_GET['mode']) && isset($_GET['id'])) {
	if (!empty($_GET['mode']) && !empty($_GET['id'])) {
			$mode = $_GET['mode'];
			$authUserId = $_GET['id'];
			if ($mode == "add") {
				//add a user id to the table
				sqlStatement("INSERT INTO `updater_users`(`authUserId`, `date`) VALUES (?, NOW())", array($authUserId));
				$toast_type = "success";
				$toast_title = "User Added";
				$toast_message = "The user is added to updater administration"; 
			}
			elseif ($mode == "remove") {
				sqlStatement("DELETE FROM `updater_users` WHERE authUserId=?", $bindarray=array($authUserId));
				$toast_type = "error";
				$toast_title = "User Removed";
				$toast_message = "The user was removed from updater administration"; 
			}
			header("location: index.php?accordion_index=1&toast_type=$toast_type&toast_title=$toast_title&toast_message=$toast_message");
		}
}


?>

<script>

   $("#tabs-1").tabs({
  // loading spinner
  heightStyle: "fill",
  active: 0
});
   $(function() {
       $( "#accordion-1" ).accordion({
       	collapsible: true,
       	heightStyle: "content"
       });
    });
<?php
	//MODULE TO SHOW WHICH ACCORDION TO BE OPENED AFTER A FORM SAVE AND ALSO SHOW TOAST
	if (isset($_GET['accordion_index']) && isset($_GET['toast_type']) && isset($_GET['toast_message']) && isset($_GET['toast_title'])) {
		if (!empty($_GET['toast_title']) && !empty($_GET['toast_type']) && !empty($_GET['toast_message'])) {
			$index = $_GET['accordion_index'];
			$title = $_GET['toast_title'];
			$type = $_GET['toast_type'];
			$message = $_GET['toast_message'];
			if (empty($_GET['accordion_index'])) {

			}
			else {
			echo "$('#accordion-1').accordion({active: $index});";
			}
			echo "parent.showUpdaterNotifications('$type', '$title', '$message');";
		}
	}

?>
$('.updater_general_settings_parent').change(function () {
	if ($('.updater_general_settings_parent').is(":checked")) {
		$('.updater_general_settings_children').prop('disabled', true);
		$('.updater_general_settings_children').css('opacity', '0.2');
	}
	else {
		$('.updater_general_settings_children').prop('disabled', false);
		$('.updater_general_settings_children').css('opacity', '1');

	}
});

$(document).ready(function () {
		if ($('.updater_general_settings_parent').is(":checked")) {
		$('.updater_general_settings_children').prop('disabled', true);
		$('.updater_general_settings_children').css('opacity', '0.2');
	}
	else {
		$('.updater_general_settings_children').prop('disabled', false);
		$('.updater_general_settings_children').css('opacity', '1');

	}
});

</script>
<style type="text/css">
	  #loading {
   width: 100%;
   height: 100%;
   position: fixed;
   z-index: 999;
   text-align:center;
   letter-spacing: 3px;
   top: 50%;
}
#iframe-holder {
  background:url(updater_loading.gif) center center no-repeat;

}
</style>
