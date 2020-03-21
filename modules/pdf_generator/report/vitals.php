<?php
/**
 * The purpose of this code is to get the patient issues data
 * in a formatted manner and store it in a single variable $content_vitals.
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

foreach($_POST as $vitals_name => $vitals_value){
    if(substr($vitals_name, 0, 6) == "vitals"){
        $content_vitals = pdf_vitals_data($pid, substr($vitals_name, 7));
        if($content_vitals){
            $pdf->Ln(2);
            $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());
            $pdf->Ln(2);
            $pdf->WriteHTML($content_vitals, true, false, false, false, '');
        }
    }
}

function pdf_vitals_data($pid, $vitals_id){
    $vitals_query = sqlStatement("SELECT * FROM form_vitals WHERE pid='$pid' AND id='$vitals_id' AND activity=1");
    $vitals_data = sqlFetchArray($vitals_query);
    if($vitals_data){
        $i = 0;
        $content_vitals_temp2 = array();
        if((($vitals_data['bps'])&&($vitals_data['bpd']))&&(($vitals_data['bps'] != 0.00)&&($vitals_data['bpd'] != 0.00))){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("Blood Pressure") . " : </b>" . $vitals_data['bps'] . "/" . $vitals_data['bpd'] . "</td>";
            $i++;
        }
        if(($vitals_data['weight'])&&($vitals_data['weight'] != 0.00)){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("Weight") . " : </b>" . $vitals_data['weight'] . " " . xlt("lb") . " (" . round($vitals_data['weight']*0.453592, 2) . " " . xlt("kg") . ")</td>";
            $i++;
        }
        if(($vitals_data['height'])&&($vitals_data['height'] != 0.00)){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("Height") . " : </b>" . $vitals_data['height'] . " " . xlt("in") . " (" . round($vitals_data['height']*2.54, 2) . " " . xlt("cm") . ")</td>";
            $i++;
        }
        if(($vitals_data['temperature'])&&($vitals_data['temperature'] != 0.00)){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("Temperature") . " : </b>" . $vitals_data['temperature'] . " " . xlt("F") . " (" . round(($vitals_data['temperature'] - 32)*5/9, 2) . " " . xlt("C") . ")</td>";
            $i++;
        }
        if($vitals_data['temp_method']){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("Temp Method") . " : </b>" . $vitals_data['temp_method'] . "</td>";
            $i++;
        }
        if(($vitals_data['pulse'])&&($vitals_data['pulse'] != 0.00)){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("Pulse") . " : </b>" . (int) $vitals_data['pulse'] . " " . xlt("per min") . "</td>";
            $i++;
        }
        if(($vitals_data['respiration'])&&($vitals_data['respiration'] != 0.00)){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("Respiration") . " : </b>" . (int) $vitals_data['respiration'] . " " . xlt("per min") . "</td>";
            $i++;
        }
        if($vitals_data['note']){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("Note") . " : </b>" . $vitals_data['note'] . "</td>";
            $i++;
        }
        if(($vitals_data['BMI'])&&($vitals_data['BMI'] != 0.00)){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("BMI") . " : </b>" . (int) $vitals_data['BMI'] . " " . xlt("kg") . "/" . xlt("m") . "^2" . "</td>";
            $i++;
        }
        if($vitals_data['BMI_status']){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("BMI Status") . " : </b>" . xlt("See Growth-Chart") . "</td>";
            $i++;
        }
        if(($vitals_data['waist_circ'])&&($vitals_data['waist_circ'] != 0.00)){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("Waist Circ") . " : </b>" . $vitals_data['waist_circ'] . " " . xlt("in") . " (" . round($vitals_data['waist_circ']*2.54, 2) . " " . xlt("cm") . ")</td>";
            $i++;
        }
        if(($vitals_data['head_circ'])&&($vitals_data['head_circ'] != 0.00)){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("Head Circ") . " : </b>" . $vitals_data['head_circ'] . " " . xlt("in") . " (" . round($vitals_data['head_circ']*2.54, 2) . " " . xlt("cm") . ")</td>";
            $i++;
        }
        if(($vitals_data['oxygen_saturation'])&&($vitals_data['oxygen_saturation'] != 0.00)){
            $content_vitals_temp2[$i] .= "<td><b>" . xlt("Oxygen Saturation") . " : </b>" . (int) $vitals_data['oxygen_saturation'] . " %" . "</td>";
            $i++;
        }
        
        $content_vitals_temp .= '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Vitals") . ':</span><br style="line-height:1.5;">';
        $content_vitals_temp .= "&nbsp;&nbsp;&nbsp;(" . oeFormatSDFT(strtotime($vitals_data['date'])) . ')<br style="line-height:1.5;">';
        $content_vitals_temp .= "<table>";

        if(count($content_vitals_temp2)%3 != 0){
            $empty_cells = 3 - count($content_vitals_temp2)%3;                                                      //if the number of elements
            $len = count($content_vitals_temp2);                                                                    //are not multiple of 3
            for($i = $len; $i < $len + $empty_cells; $i++){                                                         //then this loop will
                $content_vitals_temp2[$i] = "<td></td>";                                                            //fill the required number
            }                                                                                                       //of empty cells to make
        }                                                                                                           //them multiple of 3
        $i = 0; $j = 1; $k = 2;
        while($content_vitals_temp2[$k] != NULL){                                                                   //this loop will insert
            $content_vitals_temp .= '&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5">' . $content_vitals_temp2[$i]   //data in 3 columns in
                                    . $content_vitals_temp2[$j] . $content_vitals_temp2[$k] . "</tr>\n";           //the PDF
                
            $i = $i + 3;
            $j = $j + 3;
            $k = $k + 3;
        }
        $content_vitals_temp .= "</table>\n";
    }

    return $content_vitals_temp;
}

?>
