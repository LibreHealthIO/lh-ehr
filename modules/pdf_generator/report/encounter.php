<?php
/**
 * The purpose of this code is to get the patient billing data
 * in a formatted manner and store it in a single variable $content_enc.
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

require_once("$srcdir/forms.inc");

foreach($_POST as $encounter_name => $encounter_value){
    if(substr($encounter_name, 0, 17) == "patient_encounter"){
        pdf_encounter_data($encounter_value, $pid);
    }
}

function pdf_encounter_data($encounter, $pid){
    global $content_enc;
    $encounter_query = sqlStatement("SELECT * FROM form_encounter WHERE pid='$pid' AND encounter='$encounter'");
    while($encounter_data = sqlFetchArray($encounter_query)){
        $content_enc .= '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Patient Encounter") . ':</span><br style="line-height: 1.5;">';
        $content_enc .= "&nbsp;&nbsp;&nbsp;(" . oeFormatSDFT(strtotime($encounter_data["date"])) . ") " . xlt("Provider") . ': ' . getProviderName(getProviderIdOfEncounter($encounter)) . "<br>";
        $content_enc .= "&nbsp;&nbsp;&nbsp;<b>" . xlt("Facility") . ":</b> " . $encounter_data['facility'] . "<br>";
        $content_enc .= "&nbsp;&nbsp;&nbsp;<b>" . xlt("Reason") . ":</b> " . $encounter_data['reason'];

        $bres = sqlStatement("SELECT b.date, b.code, b.code_text " .
                            "FROM billing AS b, code_types AS ct WHERE " .
                            "b.pid = '$pid' AND " .
                            "b.encounter = '$encounter' AND " .
                            "b.activity = 1 AND " .
                            "b.code_type = ct.ct_key AND " .
                            "ct.ct_diag = 0 " .
                            "ORDER BY b.date");
        while ($brow=sqlFetchArray($bres)) {
            $content_enc .= "<br>&nbsp;&nbsp;&nbsp;<b>" . xl("Procedure") . "</b>: " .
                            $brow['code'] . " " . $brow['code_text'];
        }
        $content_enc .= '<br><br>';
    }
}

if($content_enc){
    $pdf->Ln(2);
    $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());
    $pdf->Ln(2);
    $pdf->WriteHTML($content_enc, true, false, false, false, '');
}
