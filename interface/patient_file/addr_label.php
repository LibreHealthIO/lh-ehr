<?php
/** 
* interface/patient_file/addr_label.php Displaying a PDF file of Labels for printing. 
* 
* Program for displaying Address Labels 
* via the popups on the left nav screen
* 
* Copyright (C) 2014-2017 Terry Hill <teryhill@librehealth.io> 
* 
* LICENSE: This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 3 
* of the License, or (at your option) any later version. 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details. 
* You should have received a copy of the GNU General Public License 
* along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;. 
* 
* @package LibreEHR 
* @author Terry Hill <teryhill@librehealth.io>
* @link http://www.libreehr.org 
*
* this is from the barcode-coder and FPDF website I used the examples and code snippets listed on the sites
* to create this program
 *
 *
 * Ability to format printed addresses on envelopes improved / modified by Daniel Pflieger
 * daniel@mi-squared or growlingflea@gmail.com
*/



$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../globals.php");
require_once("$srcdir/classes/PDF_Label.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/classes/php-barcode.php");


//Get the data to place on labels
//

$patdata = sqlQuery("SELECT " .
  "p.fname, p.mname, p.lname, p.pid, p.DOB, " .
  "p.street, p.city, p.state, p.postal_code, p.pid " .
  "FROM patient_data AS p " .
  "WHERE p.pid = ? LIMIT 1", array($pid));

// re-order the dates
//
$today = oeFormatShortDate($date='today');
$dob = oeFormatShortDate($patdata['DOB']);


//Format envelope settings.  Changes made by Daniel Pflieger
//Pull the settings from globals.  If the Global settings have not been determined in Globals,
//the default is to print on a 4 1/8 by 9 1/2 envelope.
//todo: put the option in settings to set envelope size.


//Keep in mind the envelope is shifted by 90 degrees.
// Changes made by Daniel Pflieger, daniel@mi-squared.com growlingflea@gmail.com


$x_width =  $GLOBALS['env_x_width'];
$y_height = $GLOBALS['env_y_height'];

//printed text details
$font_size = $GLOBALS['env_font_size'];
$x         = $GLOBALS['env_x_dist'];  // Distance from the 'top' of the envelope in portrait position
$y         = $GLOBALS['env_y_dist']; // Distance from the right most edge of the envelope in portrait position
$angle    = 90;   // rotation in degrees
$black    = '000000'; // color in hexa

//Format of the address
//This number increases the spacing between the line printed on the envelope
$xt       = .2*$font_size;

//ymargin of printed text. The smaller the number, the further from the left edge edge the address is printed
$yt       = 0;




$text1 = sprintf("%s %s\n", $patdata['fname'], $patdata['lname']);
$text2 = sprintf("%s \n", $patdata['street']);
$text3 = sprintf("%s , %s %s", $patdata['city'], $patdata['state'], $patdata['postal_code']);


$pdf = new eFPDF('P', 'mm',array($x_width, $y_height)); // set the orentation, unit of measure and size of the page
$pdf->AddPage();
$pdf->SetFont('Arial','',$font_size);
$pdf->TextWithRotation($x, $y + $yt, $text1, $angle);
$xt += $xt;
$pdf->TextWithRotation($x + $xt, $y + $yt, $text2, $angle);
$xt +=$xt;
$pdf->TextWithRotation($x + $xt, $y + $yt, $text3, $angle);
$xt +=$xt;


$pdf->Output();
?>
