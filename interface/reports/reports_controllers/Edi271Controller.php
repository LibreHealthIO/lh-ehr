<?php
/*
 * These functions are common functions used in Eligibility Response (EDI_271 File Upload) report.
 * They have been pulled out and placed in this file. This is done to prepare
 * the for building a report generator.
 *
 * Copyright (C) 2018 Tigpezeghe Rodrige <tigrodrige@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Tigpezeghe Rodrige <tigrodrige@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

//	START - INCLUDE STATEMENTS
include_once("../globals.php");
include_once("$srcdir/forms.inc");
include_once("$srcdir/billing.inc");
include_once("$srcdir/patient.inc");
include_once("$srcdir/report.inc");
include_once("$srcdir/calendar.inc");
require_once("$srcdir/headers.inc.php");
include_once("$srcdir/classes/Document.class.php");
include_once("$srcdir/classes/Note.class.php");
include_once("$srcdir/sqlconf.php");
include_once("$srcdir/edi.inc");

// END - INCLUDE STATEMENTS


//  File location (URL or server path)

$target			= $GLOBALS['edi_271_file_path'];

if(isset($_FILES) && !empty($_FILES)) {

	$target		= $target .time().basename( $_FILES['uploaded']['name']);

	$FilePath	= $target;

	if ($_FILES['uploaded']['size'] > 350000) {
		$message .= htmlspecialchars( xl('Your file is too large'), ENT_NOQUOTES)."<br>";

	}

	if ($_FILES['uploaded']['type']!="text/plain") {
		$message .= htmlspecialchars( xl('You may only upload .txt files'), ENT_NOQUOTES)."<br>";
	}

	if(!isset($message)) {
		if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) {
			$message	= htmlspecialchars( xl('The following EDI file has been uploaded').': "'. basename( $_FILES['uploaded']['name']).'"', ENT_NOQUOTES);

			// Stores the content of the file
			$Response271= file($FilePath);

			// Counts the number of lines
			$LineCount	= count($Lines);

			//This will be a two dimensional array
			//that holds the content nicely organized

			$DataSegment271 = array();
			$Segments271	= array();

			// We will use this as an index
			$i			=	0;
			$j			=	0;
			$patientId	= "";

			// Loop through each line
			foreach($Response271 as $Value) {
				// In the array store this line
				// with values delimited by ^ (tilt)
				// as separate array values

				$DataSegment271[$i] = explode("^", $Value);

				if(count($DataSegment271[$i])<6) {
								$messageEDI	= true;
								$message = "";
								if(file_exists($target))
								{
									unlink($target);
								}
				} else {
					foreach ($DataSegment271[$i] as $datastrings) {
						$Segments271[$j] = explode("*", $datastrings);

						$segment		 = $Segments271[$j][0];

						// Switch Case for Segment
						switch ($segment)
						{
							case 'ISA':

								$j = 0;

								foreach($Segments271[$j] as $segmentVal){
									if($j == 6) {
										$x12PartnerId = $segmentVal;
									}

									$j	=	$j + 1;
								}

								break;

							case 'REF':

								foreach($Segments271[$j] as $segmentVal){

									if($segmentVal == "EJ") {
										$patientId = $Segments271[$j][2];
									}
								}

								break;

							case 'EB':

								foreach($Segments271[$j] as $segmentVal){

								}

								break;

							case 'MSG':

								foreach($Segments271[$j] as $segmentVal){

									if($segment != $segmentVal) {
										eligibility_response_save($segmentVal,$x12PartnerId);
										eligibility_verification_save($segmentVal,$x12PartnerId,$patientId);
									}
								}

								break;

						}

						// Increase the line index
						$j++;
					}
				}
					  //Increase the line index
					   $i++;
			}
		}
	} else {
		$message .= htmlspecialchars( xl('Sorry, there was a problem uploading your file'), ENT_NOQUOTES). "<br><br>";
	}
}

?>
