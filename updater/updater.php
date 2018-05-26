<div class="loader"></div>
<Style>
input[type="submit"]:disabled {
	opacity: 0.5;
}

.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #f69e00;
  border-bottom: 16px solid #f69e00;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
    width:100px;
    height: 100px;
    position: fixed;
    top: 30%;
    left: 45%;

}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(300deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
body {
	text-transform: capitalize;
}
</Style>
<?php 
/**
 * Contains all updater functions
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

// LOAD API FUNCTIONS DEPENDS ON WEBSITE IT IS HOSTED
$settings_json = file_get_contents("settings.json");
$settings_array = json_decode($settings_json, true);
$updater_host = $settings_array['host'];

//LOADING API FUNCTIONS ACCORDING TO THE HOST VALUE
	require 'lib/api.github.php';


if (getUpdaterSetting("updater_requirements") == "empty_setting") {
	//show screen one
	if (internet_bool()) {
		$internet_bool = "<i class='fa fa-check' style='color: green;'></i>";
	}
	else {
		$internet_bool = "<i class='fa fa-warning' style='color: red;'></i>";
	}

	if (curl_bool()) {
		$curl_bool = "<i class='fa fa-check' style='color: green;'></i>";
	}
	else {
		$curl_bool = "<i class='fa fa-warning' style='color: red;'></i>";
	}

	if (file_permissions_bool($webserver_root)) {
		$file_permissions_bool = "<i class='fa fa-check' style='color: green;'></i>";
	}
	else {
		$file_permissions_bool = "<i class='fa fa-warning' style='color: red;'></i>";
	}

	if (curl_bool() && file_permissions_bool($webserver_root) && internet_bool()) {
		$requirements_bool = "enabled";
	}
	else {
		$requirements_bool = "disabled";
	}

	$loader->set_template_file("updater_screen_one");
	$loader->assign("CURL_BOOL",$curl_bool);
	$loader->assign("INTERNET_BOOL",$internet_bool);
	$loader->assign("FILE_PERMISSIONS_BOOL",$file_permissions_bool);
	$loader->assign("REQUIREMENTS_BOOL", $requirements_bool);
	$loader->output();
}

elseif (getUpdaterSetting("updater_token") == "empty_setting") {
	//show screen two
	$loader->set_template_file("updater_screen_two");
	$loader->assign("HOST",$updater_host);
	$loader->output();
}

else {
	$updater_token  = getUpdaterSetting("updater_token");
	$owner_arr = getOwnerInfo($updater_token);
	if (isset($owner_arr['message']) OR count($owner_arr) == 0){
		//it means the token is expired, so ask for re-entry
		$loader->set_template_file("updater_screen_three");
		$loader->assign("HOST",$updater_host);
		$loader->output();
	}
	elseif (isset($owner_arr['login'])) {
		$avatar_url = $owner_arr['avatar_url'];
		$user_name = $owner_arr['login'];
		//token is valid, so show the user profile
		$loader->set_template_file("updater_screen_four");
		$loader->assign("AVATAR_URL", $avatar_url);
		$loader->assign("USER_NAME", $user_name);
		$loader->output();
	}

}
//HANDLE ALL THE POST REQUESTS MADE VIA THE $_POST
if (isset($_POST)) {
	
	//Handler for first step in the updater > step 1 of 3
	if (isset($_POST['updater_requirements'])) {
		if (!empty($_POST['updater_requirements'])) {
			$updater_requirments = $_POST['updater_requirements'];
			if ($updater_requirments != "0" && $updater_requirments != "1") {
				$updater_requirments = 0;
			}
			else {
				$updater_requirements = $_POST['updater_requirements'];
			}
			if (internet_bool() && curl_bool() && file_permissions_bool($webserver_root)) {
				setUpdaterSetting("updater_requirements", $updater_requirements);
				header("location: updater.php");
			}
			else {
				//dont update or create the setting
			}
		}
	}

	if (isset($_POST['updater_token'])) {
		if (!empty($_POST['updater_token'])) {
			$updater_token = $_POST['updater_token'];
			$owner_arr = getOwnerInfo($updater_token);
			if (isset($owner_arr['message']) OR count($owner_arr) == 0){
				//it means the token is not valid
				$toast_type = "danger";
				$toast_title = "Token Not Valid";
				$toast_message = "The Token you entered seems to be invalid"; 

			}
			elseif (isset($owner_arr['login'])) {
				$toast_type = "success";
				$toast_title = "Token Added";
				$toast_message = "The Token is valid and added to the updater"; 
				setUpdaterSetting("updater_token", $updater_token);
			}

			header("location: updater.php?toast_type=$toast_type&toast_title=$toast_title&toast_message=$toast_message");
		}
	}


}
?>

<?php
//MODULE TO SHOW WHICH ACCORDION TO BE OPENED AFTER A FORM SAVE AND ALSO SHOW TOAST
	if (isset($_GET['toast_type']) && isset($_GET['toast_message']) && isset($_GET['toast_title'])) {
		if (!empty($_GET['toast_title']) && !empty($_GET['toast_type']) && !empty($_GET['toast_message'])) {
			$title = $_GET['toast_title'];
			$type = $_GET['toast_type'];
			$message = $_GET['toast_message'];
			echo "<div class='col-xs-12'><br/></div><div class='alert alert-$type col-xs-12 text-left'><h4>$title</h4><p>$message</p></div>";
		}
	}
?>	


<script type="text/javascript">
	$(document).ready(function(){
     $('.loader').fadeOut();
});
</script>