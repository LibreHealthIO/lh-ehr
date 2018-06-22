<?php
/**
 * all github api functions used by the updater
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

/**
*@param $access_token - user_access_token of github
*@param $owner - owner of thr github repo
*@param $repo_name - repository name
*@return $array holding list of all merged pull requests
*/
function getAllMergedPullRequests($access_token, $owner, $repo_name, $pull_request_number){
	$pr_number = array();
	$i = 1;
	$classifier = getSinglePullRequestClassifier($access_token, $owner, $repo_name, $pull_request_number);
	while (1) {
		$curl = curl_init();
		$url = "https://api.github.com/repos/".$owner."/".$repo_name."/pulls?per_page=100&page=$i&state=all&access_token=$access_token";
		$options = array(CURLOPT_URL=>$url, CURLOPT_RETURNTRANSFER=>1);
		curl_setopt($curl, CURLOPT_USERAGENT, "LibreUpdater");
		curl_setopt_array($curl, $options);
		$json = curl_exec($curl);
		curl_close($curl);
		$array = json_decode($json, true);
		foreach ($array as $key) {
		$number = $key['number'];
		$merged_at = $key['merged_at'];
		$bool = strtotime($merged_at);
			if ($merged_at != null && $bool > $classifier) {
				if ($number != $pull_request_number) {
					$pr_number[$bool] = $number;
				}
			}
			elseif($bool == $classifier) {
				break 2;
			}
		}
		$i = $i + 1;
	}
	ksort($pr_number);
	return $pr_number;
}


/**
*@param $access_token - user_access_token of github
*@param $owner - owner of thr github repo
*@param $repo_name - repository name
*@return $array holding list of all open pull requests
*/
function getAllOpenPullRequests($access_token, $owner, $repo_name){

		$curl = curl_init();
		$url = "https://api.github.com/repos/".$owner."/".$repo_name."/pulls?per_page=100&state=open&access_token=$access_token";
		$options = array(CURLOPT_URL=>$url, CURLOPT_RETURNTRANSFER=>1);
		curl_setopt($curl, CURLOPT_USERAGENT, "LibreUpdater");
		curl_setopt_array($curl, $options);
		$json = curl_exec($curl);
		curl_close($curl);
		$array = json_decode($json, true);
		return $array;
}

function getSinglePullRequestClassifier($access_token, $owner, $repo_name, $pull_request_number) {
	$curl = curl_init();
	$url = "https://api.github.com/repos/".$owner."/".$repo_name."/pulls/".$pull_request_number."?access_token=$access_token";
	$options = array(CURLOPT_URL=>$url, CURLOPT_RETURNTRANSFER=>1);
	curl_setopt($curl, CURLOPT_USERAGENT, "LibreUpdater");
	curl_setopt_array($curl, $options);
	$json = curl_exec($curl);
	$array = json_decode($json, true);
	return strtotime($array['merged_at']);

}

/**
*@param $access_token - user_access_token of github
*@param $owner - owner of the github repo
*@param $repo_name - repository name
*@param $pull_request_number - number of the pull request or id of the pull request
*@return $array holding the file changes in that pull request
*/
function getSinglePullRequestFileChanges($access_token, $owner, $repo_name, $pull_request_number) {
	$curl = curl_init();
	$url = "https://api.github.com/repos/".$owner."/".$repo_name."/pulls/".$pull_request_number."/files?access_token=$access_token";
	$options = array(CURLOPT_URL=>$url, CURLOPT_RETURNTRANSFER=>1);
	curl_setopt($curl, CURLOPT_USERAGENT, "LibreUpdater");
	curl_setopt_array($curl, $options);
	$json = curl_exec($curl);
	$array = json_decode($json, true);
	return $array;
}

/**
*@param $access_token - user_access_token of github
*@param $owner - owner of the github repo
*@param $repo_name - repository name
*@param $pull_request_number - number of the pull request or id of the pull request
*@return $array holding the file changes in that pull request
*/
function getSinglePullRequestInfo($access_token, $owner, $repo_name, $pull_request_number) {
	$curl = curl_init();
	$url = "https://api.github.com/repos/".$owner."/".$repo_name."/pulls/".$pull_request_number."?access_token=$access_token";
	$options = array(CURLOPT_URL=>$url, CURLOPT_RETURNTRANSFER=>1);
	curl_setopt($curl, CURLOPT_USERAGENT, "LibreUpdater");
	curl_setopt_array($curl, $options);
	$json = curl_exec($curl);
	$array = json_decode($json, true);
	return $array;
}

/**
*@param $access_token - user_access_token of github
*@return $array holding the user info
*/
function getOwnerInfo($access_token) {
	$curl = curl_init();
	$url = "https://api.github.com/user?access_token=$access_token";
	curl_setopt_array($curl, array(CURLOPT_URL=>$url, CURLOPT_RETURNTRANSFER=>1));
	curl_setopt($curl, CURLOPT_USERAGENT, "LibreUpdater");
	$json = curl_exec($curl);
	return $array = json_decode($json, true);
}



/**
*@param $access_token - user_access_token of github
*@return bool - check token is valid or not, called after checking for internet_bool
*/
function isTokenValid($access_token) {
	$curl = curl_init();
	$url = "https://api.github.com/user?access_token=$access_token";
	curl_setopt_array($curl, array(CURLOPT_URL=>$url, CURLOPT_RETURNTRANSFER=>1));
	curl_setopt($curl, CURLOPT_USERAGENT, "LibreUpdater");
	$json = curl_exec($curl);
	$array = json_decode($json, true);
	if (isset($array['login'])) {
		return true;
	}
	else {
		return false;
	}
}

/**
*@param $url - raw url of the file to be downloaded
*@param $directory - directory at which the downloaded files must be saved
*@param $filename - usually a name combined of its sha, and time stamp, can be adjusted according to API, but filename must be registered to the db and non repeating
*@return bool - whether the action has been performed or not
*/
function downloadFile($url, $directory, $filename, $status) {

	if ($status != "removed") {
		
		$url = str_replace("/raw", "", $url);
		$url = str_replace("github.com", "raw.githubusercontent.com", $url);
		$curl = curl_init();
		curl_setopt_array($curl, array(CURLOPT_URL=>$url, CURLOPT_RETURNTRANSFER=>1));
		curl_setopt($curl, CURLOPT_USERAGENT, "LibreUpdater");
		$json = curl_exec($curl);
		$fp = fopen($directory."/".$filename, "w");
		fwrite($fp, $json);
		fclose($fp);
	}
}

/*
* sends the review on the pull request which has been applied
*/

function sendReview($access_token, $owner, $repo_name, $comment, $issue_number) {
	$curl = curl_init();
	$url = "https://api.github.com/repos/".$owner."/".$repo_name."/issues/".$issue_number."/comments?access_token=$access_token";
	$array =  array('body' => $comment);
	$payload = json_encode($array);
	$options = array(CURLOPT_URL=>$url, CURLOPT_RETURNTRANSFER=>1, CURLOPT_POSTFIELDS=>$payload);
	curl_setopt($curl, CURLOPT_USERAGENT, "LibreUpdater");
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt_array($curl, $options);
	$json = curl_exec($curl);
}

?>

