<?php
/**
 * The purpose of this code is to get the patient billing data
 * in a formatted manner and store it in a single variable $content_billing.
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

$content_billing = '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Billing Information") . ':<br></span>';
if (count($ar['patient_encounter']) > 0) {
    $billings = array();
    $content_billing .= "<table>";
    $content_billing .= '<tr><td width="400"><b>' . xlt("Code") . '</b></td><td><b>' . xlt("Fee") . '</b></td></tr>';
    $total = 0.00;
    $copays = 0.00;
    foreach ($ar['patient_encounter'] as $be) {
        $ta = explode(":",$be);
        $billing = getPatientBillingEncounter($pid,$ta[1]);
        $billings[] = $billing;
        foreach ($billing as $b) {
            $content_billing .= "<tr>";
            $content_billing .= "<td>";
            $content_billing .= $b['code_type'] . ": " . $b['code'] . "&nbsp;". $b['modifier'] . "&nbsp;&nbsp;&nbsp;" . $b['code_text'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            $content_billing .= "</td>";
            $content_billing .= "<td>";
            $content_billing .= oeFormatMoney($b['fee']);
            $content_billing .= "</td>";
            $content_billing .= "</tr>";
            $total += $b['fee'];
            if ($b['code_type'] == "COPAY") {
                $copays += $b['fee'];
            }
        }
    }
    $content_billing .= "<tr><td>&nbsp;</td></tr>";
    $content_billing .= "<tr><td><b>" . xlt("Sub") . "-" . xlt("Total") . "</b></td><td>" . oeFormatMoney($total + abs($copays)) . "</td></tr>";
    $content_billing .= "<tr><td><b>" . xlt("Pid") . "</b></td><td>" . oeFormatMoney(abs($copays)) . "</td></tr>";
    $content_billing .= "<tr><td><b>" . xlt("Total") . "</b></td><td>" . oeFormatMoney($total) . "</td></tr>";
    $content_billing .= "</table>";
}
else {
    pdfPatientBilling($pid);
}
function pdfPatientBilling($pid) {
    global $content_billing;
    $res = sqlStatement("SELECT * FROM billing WHERE pid='$pid' ORDER BY date");
    while($result = sqlFetchArray($res)) {
      $content_billing .= "<span><b>" . oeFormatSDFT(strtotime($result{"date"})) . " : </b></span>";
      $content_billing .= "<span>(".$result{"code_type"}.") ";
      $content_billing .= $result['code_type'] == 'COPAY' ? oeFormatMoney($result['code']) : $result['code'];
      $content_billing .= " - ". wordwrap($result['code_text'], 70, "\n", true)."</span>";
      $content_billing .= "<br>";
      }
  }

$pdf->WriteHTML($content_billing, true, false, false, false, '');

?>
