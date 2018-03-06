<?php 
/*
 *  This file suggests the name of patients while adding helping to add patients fastly.
 *
 *
 * @copyright Copyright (C) 2018 Naveen <kmnaveen101@gmail.com>
 *
 * @package LibreHealth EHR
 * @author Naveen <kmnaveen101@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */
include_once("../../interface/globals.php");
include_once("{$GLOBALS['srcdir']}/sql.inc");

function suggest_name($type, $text) {
	$limit = 10;
	$sql = "SELECT * FROM `patient_data` WHERE $type LIKE '$text%' LIMIT 0,$limit";
	$query = sqlStatement($sql);
	while ($row = sqlFetchArray($query)) {
		$data = array();
		if (!in_array($row[$type], $data)) {
			$data[] =  $row[$type];	
		}
	}
	echo json_encode($data);
}

if (isset($_GET['type']) && isset($_GET['text'])) {
	if(!empty($_GET['type']) && !empty($_GET['text'])) {
	//remove form_ from the prefix.
	$allowed_autocomplete_fields = array("fname", "lname", "mname");
	$autocomplete_field = substr($_GET['type'], 5, strlen($_GET['type']));
	$boolean = in_array($autocomplete_field, $allowed_autocomplete_fields);
	if ($boolean) {
	$type = substr(trim($_GET['type']), 5, strlen($_GET['type']));
	$text = trim($_GET['text']);
	suggest_name($type, $text);
	}
 }
}

?>