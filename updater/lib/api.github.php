<?php


/**
*@param $access_token - user_access_token of github
*@param $owner - owner of thr github repo
*@param $repo_name - repository name
*@return $array holding list of all merged pull requests
*/
function getAllMergedPullRequests($access_token, $owner, $repo_name){
	$curl = curl_init();
	$url = "https://api.github.com/repos/".$owner.$repo_name."/pulls?state=merged&access_token=$access_token";
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
function getSinglePullRequestFileChanges($access_token, $owner, $repo_name, $pull_request_number) {
	$curl = curl_init();
	$url = "https://api.github.com/repos/".$owner.$repo_name."/pulls/".$pull_request_number."/files?access_token=$access_token";
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


?>

