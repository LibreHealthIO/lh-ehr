<?php
/**
 * list of updater functions used by updater
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
$updater_general_setting_checkboxes = array("updater_dm"=>0, "updater_status"=>0);
$updater_general_setting_input_fields = array("updater_notification_preference", "updater_frequency", "updater_dm", "updater_status");
/**
* @param $settingName, @param $settingValue
* @return bool 
**/
function setUpdaterSetting($settingName, $settingValue) {
$sql = "SELECT name FROM updater_settings WHERE name=?";
$query = sqlStatement($sql, $bindArray=array($settingName));
$rows = sqlNumRows($query);
if ($rows == 0) {
//INSERT THE UPDATER SETTING
$bindArray = array($settingName, $settingValue);	
	if (sqlStatement("INSERT INTO `updater_settings`(`name`, `value`) VALUES (?,?)", $bindArray)) {
		return 1;
	}
	else {
		return 0;
	}

}
else {
//UPDATE THE UPDATER SETTING
	if (sqlStatement("UPDATE `updater_settings` SET `value`=? WHERE name=?", $bindArray=array($settingValue,$settingName))) {
		return 1;
	}
	else {
		return 0;
	}
}

}


function getUpdaterSetting($settingName){
	$sql = "SELECT * FROM updater_settings WHERE name=?";
	$query = sqlStatement($sql, $bindArray=array($settingName));
	$rows = sqlNumRows($query);
	if ($rows == 0) {
		return "empty_setting";
	}
	else {
		$row = sqlFetchArray($query);
		return $row['value']; 
	}

}

function checkIfUpdaterAdministrator($authUserId) {
	$sql = "SELECT * FROM updater_users WHERE authUserId=?";
	$query = sqlStatement($sql, $bindArray=array($authUserId));
	$rows = sqlNumRows($query);
	if ($rows == 0) {
		return false;
	}
	else {
		return true; 
	}

}

function checkAdmin($userAuthorized, $authUserId) {
	$bool = false;
	if ($userAuthorized == 1 || checkIfUpdaterAdministrator($authUserId)) {
		$bool = true;
	}
	return $bool;
}

function internet_bool()
{
    $connected = @fsockopen("www.github.com", 80); 
                                        
    if ($connected){
        $is_conn = true; 
        fclose($connected);
    }else{
        $is_conn = false; 
    }
    return $is_conn;

}

function curl_bool() {
    if  (in_array  ('curl', get_loaded_extensions())) {
        return true;
    }
    else {
        return false;
    }
}

function file_permissions_bool($directory){
	if (is_writable($directory)) {
		return true;
	}
	else {
		return false;
	}

}
/**
* delete all data from the download table before making a download for another update
*/
function deleteDownloadFileDbEntry() {
	sqlStatement("DELETE FROM `updater_user_mode_download_entry` WHERE 1");
}
/**
*delete all data from the backup table before making a backup
*
*/
function deleteBackupFileDbEntry() {
	sqlStatement("DELETE FROM `updater_user_mode_backup_entry` WHERE 1");
}

/**
*@param $filename - it is combination of file sha, time stamp and the extension
*@param $status - it has four values added,modified,removed,renamed
*@param $original_name - it is location at which the downloaded file must be replaced
*@param $old_name - in case if some file was renamed, it will have the old_name, else it holds the value of empty, for renamed files we need to backup, delete the old file name and copy the new file name to the location
*/
function downloadFileDbEntry($filename, $status, $original_name, $old_name) {
	//Determine the status of the file and make Necessary action according to it
	// also save the deleted file values, which will be used for replacement purposes
	$bindArray = array($filename, $status, $original_name, $old_name);
	sqlStatement("INSERT INTO `updater_user_mode_download_entry`(`filename`, `status`, `original_name`, `old_name`) VALUES (?,?,?,?)", $bindArray);

}


/**
*@param $filename - the filename which consists of sha, time and extension 
*@param $old_name - in case if the status says as renamed we need to copy the old file to the backup
*@param $status - it has four values - added, modified, removed, renamed.
*if status is added then no backup is not added
*@param $original_name is the file at which the changes occured
*depending upon the status the backup is determined
*/
function backupFile($foldername, $filename, $original_name, $status, $old_name) {
	if ($status == "renamed") {
		// since it is renamed there is no file exists at the original file name location
		copy($old_name, $foldername."/".$file_name);
	}
	if ($status == "added") {
		//do nothing since the file does not exists
	}
	if ($status == "modified" || $status == "removed") {
		copy("../".$original_name, $foldername."/".$filename);
	}

}


function backupFileDbEntry($filename, $status, $original_name, $old_name) { 
	//Determine the status of the file and make Necessary action according to it
	// also save the deleted file values, which will be used for replacement purposes
	//everything must be recorded here
	$bindArray = array($filename, $status, $original_name, $old_name);
	sqlStatement("INSERT INTO `updater_user_mode_backup_entry`(`filename`, `status`, `original_name`, `old_name`) VALUES (?,?,?,?)", $bindArray);
}

function isExistInBackupTable($filename) {
	$query = sqlQ("SELECT * FROM `updater_user_mode_download_entry` WHERE filename='?' AND status='added'", array($filename));
	$rows = sqlNumRows($query);
	if ($rows == 0) {
		return true;
	}
	else {
		return false;
	}
}


function replaceFile($filename, $original_name, $status, $old_name) {
	$filename = "downloads/".$filename;
	$old_name = "../".$old_name;
	$original_name = "../".$original_name;

	if ($status == "renamed") {
		// since it is renamed there is no file exists at the original file name location, so unlink it with the old name
		unlink($old_name);
		checkDir($original_name);
		copy($filename, $original_name);
		echo $status;
	}
	if ($status == "added") {
		//since Added there is no need to unlink the old file,it does not exist
		checkDir($original_name);
		echo $original_name;
		echo "<br/><br/>";
		//copy($filename, $original_name);
		
	}
	if ($status == "modified") {
		unlink($original_name);
		copy($filename, $original_name);
		echo $status;
	}
	if ($status == "removed") {
		unlink($original_name);
		echo $status;
	}

}


function restoreBackupFile($filename, $original_name, $status, $old_name) {
	$filename = "backup/".$filename;
	$old_name = "../".$old_name;
	$original_name = "../".$original_name;
	if ($status == "renamed") {
		// since it is renamed there is no file exists at the original file name location, so unlink it with the old name
		unlink($original_name);
		copy($filename, $old_name);
		echo $status;
	}
	if ($status == "added") {
		//since Added there is no need to unlink the old file,it does not exist
		unlink($original_name);
	}
	if ($status == "modified") {
		unlink($original_name);
		copy($filename, $original_name);
		echo $status;
	}
	if ($status == "removed") {
		copy($filename, $original_name);
		echo $status;
	}
}

function clearFilesFolder($foldername){
//used to clear backup and download directory before starting the updater
//Get a list of all of the file names in the folder.
$files = glob($foldername . '/*');

	foreach ($files as $file) {
		if (is_file($file)) {
			if ($file != "readme.md") {
				unlink($file);
			}
			
		}
	}
}

/*
* check if the directory exists or else create it on the file addition
*/
function checkDir($location) {

	$dirname_array = pathinfo($location);

	$dirname = $dirname_array['dirname'];

	$arr  = explode("/", $dirname);

	$dir_struct = "";

	foreach ($arr as $key=>$value) {
		if (isset($value)) {
			if (!empty($value)) {
				if (!empty($dir_struct) ) {
					$dir_struct = $dir_struct.$value."/";
				}
				else {
					$dir_struct = $dir_struct.$value."/";
				}

				if (!is_file($dir_struct)) {

					if (is_dir($dir_struct)) {
						//leave it, make no changes
					}
					else {

						mkdir($dir_struct);		
					}
					

				}
				else {

				}	
				
			}
		}
	}
}
?>
