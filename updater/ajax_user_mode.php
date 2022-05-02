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
require "../sites/".$_SESSION['site_id']."/sqlconf.php";

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
$database_file = $settings_array['db_backup_file'];

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
		$refresh_pr_list_bool  = getUpdaterSetting("upcoming_prs");
		
		
		if (getUpdaterSetting("upcoming_prs") == "empty_setting") {
				$merged_requests_array = getAllMergedPullRequests($updater_token, $repository_owner, $repository_name,  $pull_request_number);
				$values = array_values($merged_requests_array);
				$values = json_encode($values);
				setUpdaterSetting("upcoming_prs", $values);
		
		}
		elseif (count(json_decode($refresh_pr_list_bool, true)) == 0) {
				$merged_requests_array = getAllMergedPullRequests($updater_token, $repository_owner, $repository_name,  $pull_request_number);
				$values = array_values($merged_requests_array);
				$values = json_encode($values);
				setUpdaterSetting("upcoming_prs", $values);
		}
		else 
		{
				//let the program feed the data from db
		}




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
			//since updating started save it for backup
			setUpdaterSetting("github_backup", $pull_request_number);
			//get only single merge request after that PR
			//TODO: get all the upcoming Prs and take the first one and update it, second one to show in updater screen
			$upcoming_prs_json = json_decode(getUpdaterSetting("upcoming_prs"), true);
			$merged_request_value[0] = $upcoming_prs_json[0];

			foreach ($merged_request_value as $value) {
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

					if ($original_name != $database_file) {
						downloadFile($url, "downloads", $filename, $status);
						//Make Downloaded File DB entry
						downloadFileDbEntry($filename, $status, $original_name, $old_name);
						
						if (isExistInBackupTable($filename)) {
							backupFile("backup", $filename, $original_name, $status, $old_name);
						}

						backupFileDbEntry($filename, $status, $original_name, $old_name);
						replaceFile($filename, $original_name, $status, $old_name);
					}
					else {

						downloadFile($url, "downloads", $filename, $status);

						replaceFile($filename, $original_name, $status, $old_name);
						$list_of_tables = getTableNamesFromFile($originalname);

						$sitename = $_SESSION['site_id'];
						//Initiate db backup
						backupDB($host, $login, $pass, $dbase, $list_of_tables, $sitename);

						//upgrade the database
					}


				}
			}
			//prepare the updater for showing next PR
			setUpdaterSetting("github_current", $upcoming_prs_json[0]);

			//unset the current PR from the list
			unset($upcoming_prs_json[0]);
			$upcoming_prs_json = array_values($upcoming_prs_json);
			$upcoming_prs_json = json_encode($upcoming_prs_json);
			setUpdaterSetting("upcoming_prs", $upcoming_prs_json);

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
		//RESTORE THE sitename.sql file from the backup
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
