<?php

$updater_general_setting_checkboxes = array("updater_dm"=>0, "updater_status"=>0);
$updater_general_setting_input_fields = array("updater_notification_preference", "updater_frequency", "updater_dm", "updater_status");
/**
* @param $settingName, @param $settingValue
* @return bool 
**/
function setUpdaterSetting($settingName, $settingValue) {
$sql = "SELECT name FROM updater_settings WHERE name='$settingName'";
$query = sqlQ($sql);
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
	if (sqlStatement("UPDATE `updater_settings` SET `value`='$settingValue' WHERE name='$settingName'")) {
		return 1;
	}
	else {
		return 0;
	}
}

}


function getUpdaterSetting($settingName){
	$sql = "SELECT * FROM updater_settings WHERE name='$settingName'";
	$query = sqlQ($sql);
	$rows = sqlNumRows($query);
	if ($rows == 0) {
		return "empty_setting";
	}
	else {
		$row = sqlFetchArray($query);
		return $row['value']; 
	}

}

?>