<?php 
/**
 * Contains all ajax mode functions
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
//run the updater, even if the browser get closed.
ignore_user_abort(true);
require '../interface/globals.php';
require '../library/user.inc';
require 'template_handler.php';
require 'lib/updater_functions.php';
$userAuthorized = $_SESSION['userauthorized'];
$authUserId = $_SESSION['authUserID'];
if (checkAdmin($userAuthorized, $authUserId)) {
	//allow to access this page
}
else {
	die("You are not allowed to handle updater administration");
}
/*MODULES NEED TO BE WRITTEN HERE
1. DOWNLOAD FILES
2. BACKUP AND REPLACE FILES
3. CREATE RESUME POINT
*/

/** VALIDATIONS NEEDED TO BE MADE HERE
* 1. VALIDATE DEVELOPER MODE IS ON
* 2. VALIDATE TOKEN IS IN GOOD CONDITION
* 3. VALIDATE OTHER NECESSARY CONDITIONS
*/
if (getUpdaterSetting("updater_requirements") == "empty_setting") { 
	die("unable to start updater - requirements not fulfilled");
}
if (!curl_bool() OR !internet_bool() && !file_permissions_bool($webserver_root)) {
	die("unable to start updater - requirements not fulfilled");
}
$settings_json = file_get_contents("settings.json");
$settings_array = json_decode($settings_json, true);
$updater_host = $settings_array['host'];
$repository_owner = $settings_array['owner'];
$repository_name = $settings_array['repository_name'];
$issue_number = $settings_array['feedback_issue_number'];

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
$files_need_to_be_downloaded = array();
if (isset($_GET)) {
	if (isset($_GET['start_updater'])) {
		if (!empty($_GET['start_updater'])) {
			clearFilesFolder($foldername = "backup");
			clearFilesFolder($foldername = "downloads");
			$updater_token = getUpdaterSetting("updater_token");
			$merged_requests_array = getAllMergedPullRequests($updater_token, $repository_owner, $repository_name,  $pull_request_number);
			//since updating started save it for backup
			setUpdaterSetting("github_backup", $pull_request_number);
			//get only single merge request after that PR
			$merged_requests_key = array_keys($merged_requests_array);
			$merged_request_value = array_values($merged_requests_array);
			$merged_requests_array = array($merged_requests_key[0]=>$merged_request_value[0]);
			$next_pr_value = $merged_request_value[1];
			foreach ($merged_requests_array as $key => $value) {
				$pr_number = $value;
				$arr = getSinglePullRequestFileChanges($updater_token, $repository_owner, $repository_name,  $pr_number);
				//clear the tables to feed the fresh data to backup and download entry tables
				deleteDownloadFileDbEntry();
				deleteBackupFileDbEntry();
				foreach ($arr as $ke) {
					$original_name = $ke['filename'];
					$url = $ke['raw_url'];
					$sha = $ke['sha'];
					$time = time();
					$extension = pathinfo($ke['filename'], PATHINFO_EXTENSION);
					$filename = $sha."_".$time.".".$extension;
					$status = $ke['status'];
					if (isset($ke['previous_filename'])) {
						$old_name = $ke['previous_filename'];
					}
					else {
						//it means the file is not renamed
						$old_name = "empty";
					}
					downloadFile($url, "downloads", $filename, $status);
					//Make Downloaded File DB entry
					downloadFileDbEntry($filename, $status, $original_name, $old_name);
					if (isExistInBackupTable($filename)) {
						backupFile("backup", $filename, $original_name, $status, $old_name);
					}
					else {
					  echo $filename;
					  echo "<br/><br/>";
					}

					backupFileDbEntry($filename, $status, $original_name, $old_name);
					replaceFile($filename, $original_name, $status, $old_name);
				}
			}
			//prepare the updater for showing next PR
			setUpdaterSetting("github_current", $next_pr_value);
		}
	}
	
}

if (isset($_GET['count_files'])) {
	if (!empty($_GET['count_files'])) {
		$files =  getUpdaterSetting("files_downloaded");
		$arr =  array('files' => $files);
		echo json_encode($arr);
	}
}


if (isset($_GET['start_recovery'])) {
	if (!empty($_GET['start_recovery'])) {
		$sql = sqlStatement('SELECT * FROM `updater_user_mode_backup_entry`');
		while ($r = sqlFetchArray($sql)) {
			$filename = $r['filename'];
			$original_name = $r['original_name'];
			$status = $r['status'];
			$old_name = $r['old_name'];
			restoreBackupFile($filename, $original_name, $status, $old_name);
		}
		$pr_backup_number = getUpdaterSetting("github_backup");
		setUpdaterSetting("github_current", $pr_backup_number);
	}
}


if (isset($_GET['send_review']) && isset($_GET['pr_number']) && isset($_GET['comment'])) {
	if (!empty($_GET['send_review']) && !empty($_GET['pr_number']) && !empty($_GET['comment'])) {
		$pr_number = $_GET['pr_number'];
		$comment = $_GET['comment'];
		//mention the pull request number in comment
		$comment = "Regarding pull request #".$pr_number." \n".$comment;
		$updater_token = getUpdaterSetting("updater_token");
		sendReview($updater_token, $repository_owner, $repository_name, $comment, $issue_number);
	}
}



?>
