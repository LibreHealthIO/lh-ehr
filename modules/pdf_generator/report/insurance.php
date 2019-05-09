<?php
/**
 * The purpose of this code is to get the patient insurance data
 * in a formatted manner and store it in a single variable $content_insu.
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

function pdfInsuranceData($pid, $insurance_data_array_custom, $insurance_data_array){
    global $content_insu;
    $content_insu = '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Insurance Data") . ':</span>';
    if ($GLOBALS['insurance_address_demographics_report'] =='1') {
        $content_insu .= "<h3>" . xlt("Primary Insurance Data") . ":</h3>";
        $content_insu .= "<table>";
        insuranceData1($insurance_data_array_custom, getRecInsuranceData ($pid,"primary"), $content_insu);
        $content_insu .= "</table>";
        $content_insu .= "<h3>" . xlt("Secondary Insurance Data") . ":</h3>";
        $content_insu .= "<table>";
        insuranceData1($insurance_data_array_custom, getRecInsuranceData ($pid,"secondary"), $content_insu);
        $content_insu .= "</table>";
        $content_insu .= "<h3>" . xlt("Tertiary Insurance Data") . ":</h3>";
        $content_insu .= "<table>";
        insuranceData1($insurance_data_array_custom, getRecInsuranceData ($pid,"tertiary"), $content_insu);
        $content_insu .= "</table>";
    }
    else{
        $content_insu .= "<h3>" . xlt("Primary Insurance Data") . ":</h3>";
        $content_insu .= "<table>";
        insuranceData1($insurance_data_array, getRecInsuranceData ($pid,"primary"), $content_insu);
        $content_insu .= "</table>";
        $content_insu .= "<h3>" . xlt("Secondary Insurance Data") . ":</h3>";
        $content_insu .= "<table>";
        insuranceData1($insurance_data_array, getRecInsuranceData ($pid,"secondary"), $content_insu);
        $content_insu .= "</table>";
        $content_insu .= "<h3>" . xlt("Tertiary Insurance Data") . ":</h3>";
        $content_insu .= "<table>";
        insuranceData1($insurance_data_array, getRecInsuranceData ($pid,"tertiary"), $content_insu);
        $content_insu .= "</table>";
    }
}

function insuranceData1($data_array, $recres, $content_insu) {
    global $content_insu;
    $content_insu .= "<table>";
    $i = 1;
    foreach ($data_array as $akey => $aval) {
        if (sizeof($recres{$akey})>0 && ($recres{$akey}[1]{"value"}!="0000-00-00 00:00:00") && ($recres{$akey}[1]{"value"}!=NULL)) {
            if($i%2 != 0)   $content_insu .= "<tr><td><b>" . $aval . "</b>";
            else    $content_insu .= "<td><b>" . $aval . "</b>";
            insuranceData2($recres, $akey, "Y-m-d", $content_insu, $i);
            $i++;
        }
    }
    if($data_array%2 != 0)  $content_insu .= "</tr>";
}

function insuranceData2 ($retar, $key, $date_format, $content_insu, $i) {
    global $content_insu;
    if (@array_key_exists($key,$retar)) {
        $length = sizeof($retar{$key});
        if (($retar{$key}[$length]{"value"} != "0000-00-00 00:00:00")&&($retar{$key}[$length]{"value"} != NULL)) {
        $tmp = $retar{$key}[$length]{"value"};
        if (strstr($key, 'DOB')) $tmp = oeFormatShortDate($tmp);
            if($i%2 != 0)   $content_insu .= $tmp . "</td>";
            else    $content_insu .= " : " . $tmp . "</td></tr>";
        }
    }
}

pdfInsuranceData($pid, $insurance_data_array_custom, $insurance_data_array);

$pdf->WriteHTML($content_insu, true, false, false, false, '');

?>
