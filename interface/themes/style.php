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
	$secondary_color = "#000000";
}

if (empty($secondary_font_color)) {
	$secondary_font_color = "#ffffff";
}

echo " .body_title, .body_top, .body_nav, .body_filler, .body_login, .table_bg, .bgcolor2, .textcolor1, .highlightcolor, .logobar {
  background-color:  #$primary_color;
  color: #$primary_font_color;

}

td, tr, .table, .bgcolor1,  ul.tabNav, .navbar, .nav, .dropdown, .navbar-header, input[type='submit'], ul.tabNav a, .navbar-collapse{

  background-color: #$secondary_color;
  color: #$secondary_font_color;
}";
?>