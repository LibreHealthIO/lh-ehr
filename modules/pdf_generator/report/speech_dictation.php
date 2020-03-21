<?php
/**
 * The purpose of this code is to get the patient issues data
 * in a formatted manner and store it in a single variable $content_dictation.
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

foreach($_POST as $dictation_name => $dictation_value){
    if(substr($dictation_name, 0, 9) == "dictation"){
        $dictation_data = pdf_dictation_data($pid, substr($dictation_name, 10));

        if($dictation_data){
            $content_dictation = '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Speech Dictation") . ':</span><br style="line-height:1.5;">';
            $content_dictation .= $dictation_data;
            $pdf->Ln(2);
            $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());
            $pdf->Ln(2);
            $pdf->WriteHTML($content_dictation, true, false, false, false, '');
        }
    }
}

function pdf_dictation_data($pid, $dictation_id){
    $dictation_query = sqlStatement("SELECT * FROM form_dictation WHERE pid='$pid' AND id='$dictation_id' AND activity=1");
    $dictation_data = sqlFetchArray($dictation_query);
    if($dictation_data){
        $content_dictation_temp = "&nbsp;&nbsp;&nbsp;(" . oeFormatSDFT(strtotime($dictation_data['date'])) . ')<br style="line-height:1.5;">';
        if($dictation_data['dictation']){
            $content_dictation_temp .= "&nbsp;&nbsp;&nbsp;<b>" . xlt("Dictation") . " :</b> " . $dictation_data['dictation'];
        }
        if($dictation_data['additional_notes']){
            $content_dictation_temp .= "&nbsp;&nbsp;&nbsp;<b>" . xlt("Additional Notes") . " :</b> " . $dictation_data['additional_notes'];
        }
    }

    return $content_dictation_temp;
}
