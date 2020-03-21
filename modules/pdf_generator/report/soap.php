<?php
/**
 * The purpose of this code is to get the patient issues data
 * in a formatted manner and store it in a single variable $content_soap.
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

foreach($_POST as $soap_name => $soap_value){
    if(substr($soap_name, 0, 4) == "soap"){
        $soap_data = pdf_soap_data($pid, substr($soap_name, 5));

        if($soap_data){
            $content_soap = '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("SOAP") . ':</span><br style="line-height:1.5;">';
            $content_soap .= $soap_data;
            $pdf->Ln(2);
            $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());
            $pdf->Ln(2);
            $pdf->WriteHTML($content_soap, true, false, false, false, '');
        }
    }
}

function pdf_soap_data($pid, $soap_id){
    $soap_query = sqlStatement("SELECT * FROM form_soap WHERE pid='$pid' AND id='$soap_id' AND activity=1");
    $soap_data = sqlFetchArray($soap_query);
    //print_r($soap_data);
    //echo "<br>";
    if($soap_data){
        $content_temp_soap = "&nbsp;&nbsp;&nbsp;(" . oeFormatSDFT(strtotime($soap_data['date'])) . ')<br style="line-height:1.5;">';
        if($soap_data['subjective']){
            $content_temp_soap .= "&nbsp;&nbsp;&nbsp;<b>" . xlt("Subjective") . " :</b> " . $soap_data['subjective'] . '<br style="line-height:1.5;">';
        }
        if($soap_data['objective']){
            $content_temp_soap .= "&nbsp;&nbsp;&nbsp;<b>" . xlt("Objective") . " :</b> " . $soap_data['objective'] . '<br style="line-height:1.5;">';
        }
        if($soap_data['assessment']){
            $content_temp_soap .= "&nbsp;&nbsp;&nbsp;<b>" . xlt("Assessment") . " :</b> " . $soap_data['assessment'] . '<br style="line-height:1.5;">';
        }
        if($soap_data['plan']){
            $content_temp_soap .= "&nbsp;&nbsp;&nbsp;<b>" . xlt("Plan") . " :</b> " . $soap_data['plan'] . '<br style="line-height:1.5;">';
        }
    }

    return $content_temp_soap;
}

?>
