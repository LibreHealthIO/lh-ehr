<?php 
/**
 * Contains all ajax developer mode functions
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
require '../library/user.inc';
require 'template_handler.php';
require 'lib/updater_functions.php';
//run the updater, even if the browser get closed.
ignore_user_abort(true);
/*MODULES NEED TO BE WRITTEN HERE
1. SYNC WITH MASTER REPO
2.APPLY THE PR
3.BACKUP THE PR CHANGES
4.RESTORE TO MASTER AND SYNC WHEN ANOTHER PR IS APPLIED
*/
$authUserId = $_SESSION['authUserID'];
$sql = sqlStatement("SELECT * FROM `updater_users` WHERE authUserId=?", $bindArray=array($authUserId));
$rows = sqlNumRows($sql);
$userAuthorized = $_SESSION['userauthorized'];
$authUserId = $_SESSION['authUserID'];
if (checkAdmin($userAuthorized, $authUserId)) {
	//allow to access this page
	$auth_boolean = true;
}
else {
	die("You are not allowed to handle updater administration");
}
if (getUpdaterSetting("updater_requirements") == "empty_setting") { 
	die("unable to start updater - requirements not fulfilled");
}
if (!curl_bool() || !internet_bool() || !file_permissions_bool($webserver_root) || !$auth_boolean) {
	die("unable to start updater - requirements not fulfilled");
}
$settings_json = file_get_contents("settings.json");
$settings_array = json_decode($settings_json, true);
$updater_host = $settings_array['host'];
$repository_owner = $settings_array['owner'];
$repository_name = $settings_array['repository_name'];

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

//TOKEN CHECK
if (isTokenValid(getUpdaterSetting("updater_token"))) {
	// do nothing, keep the script running
}
else {
	die("The token is not valid");
}

if (isset($_GET['developer_mode_start']) && isset($_GET['pr_number'])) {
	if (!empty($_GET['developer_mode_start']) && !empty($_GET['pr_number'])) {
		
		if (getUpdaterSetting('github_developer_current') != "empty_setting") {
			//if there is pr is under testing then restore it to original state
			$sql = sqlStatement('SELECT * FROM `updater_user_mode_backup_entry`');
			while ($r = sqlFetchArray($sql)) {
				$filename = $r['filename'];
				$original_name = $r['original_name'];
				$status = $r['status'];
				$old_name = $r['old_name'];
				restoreBackupFile($filename, $original_name, $status, $old_name);
			}
		}
		$test_pr_number = $_GET['pr_number'];
		clearFilesFolder($foldername = "backup");
		clearFilesFolder($foldername = "downloads");
		$updater_token = getUpdaterSetting("updater_token");
		//sync master with local repo
		$merged_requests_array = getAllMergedPullRequests($updater_token, $repository_owner, $repository_name,  $pull_request_number);
		$count = count($merged_requests_array);
		$i = 0;
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
					replaceFile($filename, $original_name, $status, $old_name);
				}
				$i = $i + 1;
				if ($count == $i) {
					setUpdaterSetting("github_current", $pr_number);
				}
			}
		//sync over


		//start testing the pr_number
		$arr = getSinglePullRequestFileChanges($updater_token, $repository_owner, $repository_name,  $test_pr_number);
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

						backupFile("backup", $filename, $original_name, $status, $old_name);

					backupFileDbEntry($filename, $status, $original_name, $old_name);
					replaceFile($filename, $original_name, $status, $old_name);
				}
			$pr_backup_number = $_GET['pr_number'];
			setUpdaterSetting("github_developer_current", $pr_backup_number);
	}
}

if (isset($_GET['start_restore'])) {
	if (!empty($_GET['start_restore'])) {
			//if there is pr is under testing then restore it to original state
			$sql = sqlStatement('SELECT * FROM `updater_user_mode_backup_entry`');
			while ($r = sqlFetchArray($sql)) {
				$filename = $r['filename'];
				$original_name = $r['original_name'];
				$status = $r['status'];
				$old_name = $r['old_name'];
				restoreBackupFile($filename, $original_name, $status, $old_name);
			}
			//change the PR entry
			//remove the github_developer_current from the table
			$sql = sqlStatement("DELETE FROM `updater_settings` WHERE name='github_developer_current'");			

	}
}











