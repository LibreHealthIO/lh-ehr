<?php 
/**
 * Contains developer mode functions and operations
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
$access_token = getUpdaterSetting("updater_token");
$userAuthorized = $_SESSION['userauthorized'];
$authUserId = $_SESSION['authUserID'];
if (checkAdmin($userAuthorized, $authUserId)) {
	//allow to access this page
}
else {
	die("You are not allowed to handle updater administration");
}
if ($updater_host == "github") {
	//if host=github then load
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
if (getUpdaterSetting("updater_dm") == "empty_setting" || getUpdaterSetting("updater_dm")== "0") {
	echo "<p style='color: red;'>Developer Mode is not Enabled</p>";
}
elseif (getUpdaterSetting("updater_token") == "empty_setting") {
	echo "<p style='color: red;'>You didnt add the token to updater yet, first setup the updater and access developer mode</p>";

}
elseif (!isTokenValid(getUpdaterSetting("updater_token"))) {
		echo "<p style='color: red;'>The token was expired, run the updater to add the new token</p>";
}
elseif (getUpdaterSetting("updater_dm") == "1") {
	if (getUpdaterSetting("github_developer_current") == "empty_setting"){

	}
	else {
		$pr = getUpdaterSetting("github_developer_current");
		echo "<div class='alert alert-info'><table class='table table-striped'><tr><td>#$pr has already applied.</td><td><button id='restore_button' class='css-button cp-submit'><i class='fa fa-refresh'></i> Restore</button></td></div>";
		// show a backup and sync button so the user can go back to state before PR applied
	}
	//Means Developer Mode is Enabled for Updater
	//Show a list of pull requests which are open
	$pr_array = getAllOpenPullRequests($access_token, $repository_owner, $repository_name);
	echo "<table class='table table-striped' id='pr_interface'>";
	foreach ($pr_array as $key) {
		$avatar_url = $key['user']['avatar_url'];
		$pr_number = $key['number'];
		$pr_title = $key['title']."(#".$pr_number.")";
		$pr_body = substr($key['body'], 0,80);
		$user_name = $key['user']['login'];
		$loader->set_template_file("developer_mode_pr");
		$loader->assign("AVATAR_URL", $avatar_url);
		$loader->assign("TITLE", $pr_title);
		$loader->assign("BODY", $pr_body);
		$loader->assign("USERNAME", $user_name);
		$loader->assign("PR_NUMBER", $pr_number);
		$loader->output();
	}
	echo "</table>";
	$loader->set_template_file("developer_mode_progress");
	$loader->assign("AVATAR_URL", $avatar_url);
	$loader->output();
}


?>
<script type="text/javascript">
	    parent.$('.iziModal-content').css("background-image", "none");
	        $('.test').click(function() {
	        	var id = $(this).attr('id');
    	var bool = prompt("Previous Testing of the pr will be returned to original state.enter confirm to test the pull request.");
    	
		    if (bool == "confirm") {
		    	$('#pr_interface').hide();
    			$('#developer_screen').show();
				 $.ajax({url: "ajax_developer_mode.php?developer_mode_start=1&pr_number=" + id , success: function(result){
				        $(".dv_progress_loader").html("<i class='fa fa-check' style='color:green; font-size:32px;'></i>");
				    	var type = 'success';
				    	var title = 'Restored Successfully';
				    	var message = 'PR has been applied Successfully';
				    	parent.showUpdaterNotifications(type, title, message);
				    }

				});
		    }
		    
    });

	    $('#restore_button').click(function() {
    			var bool = prompt("do you want to restore to a state where this PR is not applied?, type confirm to continue");
    	
		    if (bool == "confirm") {
				 $.ajax({url: "ajax_developer_mode.php?start_restore=1", success: function(result){
				    	var type = 'success';
				    	var title = 'Restored Successfully';
				    	var message = 'Local Repo has been brought to old state before the PR was applied';
				    	parent.showUpdaterNotifications(type, title, message);
				    }

				});
				window.location = "developer_mode.php";
		    }
		    
    });

	$('#dv_return').click(function () {
    	window.location = "developer_mode.php";
    });
</script>