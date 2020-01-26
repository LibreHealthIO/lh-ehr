<?php 
/** 
 * function to apply selected color in the color picker
 * 
 * Copyright (C) 2018 Naveen Muthusamy
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @package Librehealth EHR 
 * @author Naveen(kmnaveen101@gmail.com)
 * @link http://librehealth.io
 *  
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 * 
 */

/**
* Determines whether the supplied function is the valid html color code
* returns html color code
* @author Naveen
*/

require_once("../globals.php");
require_once("$srcdir/report.inc");
require_once("$srcdir/patient.inc");

function validate_html_color_code($arg) {
	$color_code = "";
	if (strlen($arg) == 6) {
		$color_code = $arg;
	}
	return $color_code;
}

$primary_color = validate_html_color_code($_GET['pc']);
$primary_font_color = validate_html_color_code($_GET['pfontcolor']);
$secondary_color = validate_html_color_code($_GET['sc']);
$secondary_font_color = validate_html_color_code($_GET['sfontcolor']);

//if no color given display default color
//This condition may also come if the color code supplied is not a valid color code.

if(empty($primary_color)) {
	$primary_color = "#ffffff";
}

if(empty($primary_font_color)) {
	$primary_font_color = "#000000";
}

if (empty($secondary_color)) {
	$secondary_color = "#ffffff";
}

if (empty($secondary_font_color)) {
	$secondary_font_color = "#000000";
}

//for first time installation

if ($secondary_color == "#ffffff") {
	$button_color = "#000000";
	$button_font_color = "#ffffff";
}
else {
	$button_color = $secondary_color;
	$button_font_color = $secondary_font_color;
}

$css_values = array();
$res = sqlStatement("SELECT * FROM user_settings WHERE setting_user = ?", $_SESSION['authId']);
while($row = sqlFetchArray($res)){
    switch($row['setting_label']){
        case "pos-bt" :
            $css_values['pos-bt'] =  $row['setting_value'];
            break;
        case "pos-txt" :
            $css_values['pos-txt'] = $row['setting_value'];
            break;
        case "pos-bor" :
            $css_values['pos-bor'] = $row['setting_value'];
            break;
        case "pos-bor-col" :
            $css_values['pos-bor-col'] = $row['setting_value'];
            break;
        case "neg-bt" :
            $css_values['neg-bt'] = $row['setting_value'];
            break;
        case "neg-txt" :
            $css_values['neg-txt'] = $row['setting_value'];
            break;
        case "neg-bor" :
            $css_values['neg-bor'] = $row['setting_value'];
            break;
        case "neg-bor-col" :
            $css_values['neg-bor-col'] = $row['setting_value'];
            break;
        case "sub-bt" :
            $css_values['sub-bt'] = $row['setting_value'];
            break;
        case "sub-txt" :
            $css_values['sub-txt'] = $row['setting_value'];
            break;
        case "sub-bor" :
            $css_values['sub-bor'] = $row['setting_value'];
            break;
        case "sub-bor-col" :
            $css_values['sub-bor-col'] = $row['setting_value'];
            break;
        case "misc-bt" :
            $css_values['misc-bt'] = $row['setting_value'];
            break;
        case "misc-txt" :
            $css_values['misc-txt'] = $row['setting_value'];
            break;
        case "misc-bor" :
            $css_values['misc-bor'] = $row['setting_value'];
            break;
        case "misc-bor-col" :
            $css_values['misc-bor-col'] = $row['setting_value'];
            break;
        case "out-bt" :
            $css_values['out-bt'] = $row['setting_value'];
            break;
        case "out-txt" :
            $css_values['out-txt'] = $row['setting_value'];
            break;
        case "out-bor" :
            $css_values['out-bor'] = $row['setting_value'];
            break;
        case "out-bor-col" :
            $css_values['out-bor-col'] = $row['setting_value'];
            break;
    }
}

echo " .body_title, .body_top, .body_nav, .body_filler, .body_login, .table_bg, .bgcolor2, .textcolor1, .highlightcolor, .logobar, .dropdown-menu>li>a, .dropdown-toggle, #menu, .dropdown, .nav>li>a, .glyphicon, #userdata .dropdown-menu>li, #userdata{
  background-color:  #$primary_color;
  color: #$primary_font_color;

}

.table, .bgcolor1,  ul.tabNav, .navbar, .nav, .dropdown, .navbar-header, ul.tabNav a, .navbar-collapse{

  background-color: #$secondary_color;
  color: #$secondary_font_color;
}

.cp-positive{
	background-color: " . $css_values['pos-bt']. ";" .
	"color: " . $css_values['pos-txt']. ";" .
	"border: " . $css_values['pos-bor']. ";" .
	"border-color: " . $css_values['pos-bor-col']. ";" .
"}
.cp-negative{
	background-color: " . $css_values['neg-bt']. ";" .
	"color: " . $css_values['neg-txt']. ";" .
	"border: " . $css_values['neg-bor']. ";" .
	"border-color: " . $css_values['neg-bor-col']. ";" .
"}
.cp-submit{
	background-color: " . $css_values['sub-bt']. ";" .
	"color: " . $css_values['sub-txt']. ";" .
	"border: " . $css_values['sub-bor']. ";" .
	"border-color: " . $css_values['sub-bor-col']. ";" .
"}
.cp-misc{
	background-color: " . $css_values['misc-bt']. ";" .
	"color: " . $css_values['misc-txt']. ";" .
	"border: " . $css_values['misc-bor']. ";" .
	"border-color: " . $css_values['misc-bor-col']. ";" .
"}
.cp-output{
	background-color: " . $css_values['out-bt']. ";" .
	"color: " . $css_values['out-txt']. ";" .
	"border: " . $css_values['out-bor']. ";" .
	"border-color: " . $css_values['out-bor-col']. ";" .
"}

";
?>
