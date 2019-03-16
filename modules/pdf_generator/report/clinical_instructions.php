<?php
/**
 * The purpose of this code is to get the patient insurance data
 * in a formatted manner and store it in a single variable $content_instructions.
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

foreach($_POST as $instr_name => $instr_value){
    if(substr($instr_name, 0, 21) == "clinical_instructions")
    {
        pdfClinicalInstructinsData($pid, $instr_value, $_SESSION['authUser']);
    }
}
function pdfClinicalInstructinsData($pid, $encounter, $user){
    global $content_instructions;

    $instructions_query = sqlStatement("SELECT * FROM form_clinical_instructions WHERE pid='$pid' AND encounter='$encounter' AND user='$user'");
    while($instructions_data = sqlFetchArray($instructions_query))
    {
        $content_instructions .= '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Clinical Instructions") . ':</span>';
        $instructions_date = explode(' ', $instructions_data['date']);
        $content_instructions .= '<br style="line-height:15px;">(' . $instructions_date[0] . ')<br style="line-height:15px;">';
        $content_instructions .= '<table border="0.5">';
        $content_instructions .= '<tr><td align="center" style="height:15px;">' . xlt("Instructions") . '</td></tr>';
        $content_instructions .= '<tr><td align="center" style="height:15px;">' . $instructions_data['instruction'] . '</td></tr>';
        $content_instructions .= "</table><br><br>";
    }
}
if($content_instructions)
{
    $pdf->Ln(2);
    $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());
    $pdf->Ln(2);
    $pdf->WriteHTML($instructions_content, true, false, false, false, '');
}

?>
