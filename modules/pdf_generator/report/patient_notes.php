<?php
/**
 * The purpose of this code is to get the patient notes data
 * in a formatted manner and store it in a single variable $content_notes.
 * The content of this variable will be printed in the PDF if required.
 *
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package Librehealth EHR
 * @author Abhinav(abhinavsingh22@hotmail.com)
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */

$content_notes = '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Patient Notes") . ':<br></span>';
pdfPatientNotes($pid);

function pdfPatientNotes($pid) {
    global $content_notes;
    // exclude ALL deleted notes
    $res = sqlStatement("SELECT * FROM pnotes WHERE pid = '$pid' AND deleted != 1 AND activity = 1 ORDER BY date");
    while($result = sqlFetchArray($res)) {
      $content_notes .= "<b>" . oeFormatSDFT(strtotime($result{"date"})) .
        ":</b>" .
        stripslashes(oeFormatPatientNote($result['body'])) . "</b><br>";
    }
}

$pdf->WriteHTML($content_notes, true, false, true, false, '');

?>
