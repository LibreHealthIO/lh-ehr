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
$repository_owner = $settings_array['owner'];
$repository_name = $settings_array['repository_name'];

if ($updater_host == "github") {
	//if host=github then load rhwm
	if (getUpdaterSetting("github_current") != "empty_setting") {
		$pull_request_number = getUpdaterSetting("github_current");
	}
	else {
		//load settings from json file, this pr number refers to the pr number at which the updater gets merged
		//replace # since it is not fit for api
		$pull_request_number = $settings_array['github_current'];
		$pull_request_number = str_replace("#", "", $pull_request_number);
	}
//LOADING API FUNCTIONS ACCORDING TO THE HOST VALUE

}

	require "lib/api.$updater_host.php";
	
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

	if (isTokenValid($updater_token) != true){
		//it means the token is expired, so ask for re-entry
		$loader->set_template_file("updater_screen_three");
		$loader->assign("HOST",$updater_host);
		$loader->output();
	}
	elseif (isTokenValid($updater_token)) {
		$owner_arr = getOwnerInfo($updater_token);
		$avatar_url = $owner_arr['avatar_url'];
		$user_name = $owner_arr['login'];
		//all parameters to this function are determined before
		$merged_requests_array = getAllMergedPullRequests($updater_token, $repository_owner, $repository_name,  $pull_request_number);
		$updates_behind = count($merged_requests_array);
		if ($updates_behind > 0) {
			$cpr_number = array_values($merged_requests_array);
			$cpr_number = $cpr_number[0];
			$current_pull_request_info = getSinglePullRequestInfo($updater_token, $repository_owner, $repository_name,  $cpr_number);
			$cpr_title = $current_pull_request_info['title'];
			$cpr_body = $current_pull_request_info['body'];
			//token is valid, so show the user profile
			$loader->set_template_file("updater_screen_four");
			$loader->assign("AVATAR_URL", $avatar_url);
			$loader->assign("USER_NAME", $user_name);
			$files_need_to_be_downloaded = array();
			foreach ($merged_requests_array as $key => $value) {
				$pr_number = $value;
				$arr = getSinglePullRequestFileChanges($updater_token, $repository_owner, $repository_name,  $pr_number);
				foreach ($arr as $ke) {
					$sha = $ke['sha'];
					$time = time();
					$extension = pathinfo($ke['filename'], PATHINFO_EXTENSION);	
					array_push($files_need_to_be_downloaded, $sha);	
				}
			}
			$count_files = count($files_need_to_be_downloaded);
			$loader->assign("UPDATER_START", "<b>$cpr_title</b><br/><h6>$cpr_body</h6>");
			$loader->assign("COUNT_FILES", $count_files);
			$loader->output();
		}
		else {
			//the repo is already up to date, show only the backup button
			$loader->set_template_file("updater_screen_five");
			$loader->assign("AVATAR_URL", $avatar_url);
			$loader->assign("USER_NAME", $user_name);
			$loader->output();
		}
	}
	elseif (!curl_bool() OR !internet_bool() OR !file_permissions_bool()) {
		//check permission and show validation screen
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
		$loader->set_template_file("validations_screen");
		$loader->assign("CURL_BOOL",$curl_bool);
		$loader->assign("INTERNET_BOOL",$internet_bool);
		$loader->assign("FILE_PERMISSIONS_BOOL",$file_permissions_bool);
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
			if (isTokenValid($updater_token) != true){
				//it means the token is not valid
				$toast_type = "danger";
				$toast_title = "Token Not Valid";
				$toast_message = "The Token you entered seems to be invalid"; 

			}
			elseif (isTokenValid($updater_token)) {
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
   <Style>
input[type="submit"]:disabled {
	opacity: 0.5;
}


body {
	text-transform: capitalize;
}
</Style>
<script type="text/javascript">
    $(window).on("load",function(){
        jQuery('#loading').hide();
    });
    $.ajaxSetup({
    async: true
	});
    $('#start_updater_user_mode').click(function () {
    		var count = $('#count_files').val();
    		var downloadFilesComplete = false;
    		$('#before_start').hide();
    		$('#after_start').show();
    		//first start the updater
		    var start_updater = "ok";
		    var bool = prompt("If you update the previous backups will be deleted.Type confirm in the below box to start the updater");
		    if (bool == "confirm") {
		    	    $.ajax({url: "ajax_user_mode.php?start_updater=1", success: function(result){
				        $(".progress_loader").html("<i class='fa fa-check' style='color:green; font-size:32px;'></i>");
				        var type = 'success';
				    	var title = 'Updated Successfully';
				    	var message = 'Update has been complete';
				    	parent.showUpdaterNotifications(type, title, message);
				    }});
		    }
    });


    $('#start_backup').click(function() {
    	var bool = prompt("If you return to backup the previous update will be cancelled, are you sure,type confirm to restore backup");
    	    	$('#before_start').hide();
    			$('#after_start').hide();
    			$('#backup_screen').show();
		    if (bool == "confirm") {
				 $.ajax({url: "ajax_user_mode.php?start_recovery=1", success: function(result){
				        $(".bk_progress_loader").html("<i class='fa fa-check' style='color:green; font-size:32px;'></i>");
				    	var type = 'success';
				    	var title = 'Restored Successfully';
				    	var message = 'Updater has restored the initial state Successfully';
				    	parent.showUpdaterNotifications(type, title, message);
				    }

				});
		    }
    });

    $('#up_return').click(function () {
    	parent.$('#updater-iframe').iziModal('close');
    });

    $('#bk_return').click(function () {
		parent.$('#updater-iframe').iziModal('close');
    });
    parent.$('.iziModal-content').css("background-image", "none");
</script>
