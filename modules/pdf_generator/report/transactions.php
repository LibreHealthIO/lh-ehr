<?php
/**
 * The purpose of this code is to get the patient transactions data
 * in a formatted manner and store it in a single variable $content_trans.
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

function getPatientTransactions($pid) {
    global $content_trans;
    $content_trans .= '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Patient Transactions") . ':</span>';
    $res = sqlStatement("SELECT * FROM transactions WHERE pid = ? ORDER BY date", array($pid));
    while($row = sqlFetchArray($res)) {
      $content_trans .= "<br>";
      $content_trans .= "<u>" . text(oeFormatSDFT(strtotime($row['date'])) . " (" .
                        generate_display_field(array('data_type'=>'1','list_id'=>'transactions'), $row['title']) . ")") . "</u><br>";
      data_to_pdf_transactions($row['id'], $row['title'], $content_trans);
    }
}
function data_to_pdf_transactions($id, $formname, $content_trans) {
  global $content_trans;
    $arr = array();
    $fres = sqlStatement("SELECT * FROM layout_options " .
      "WHERE form_id = ? AND uor > 0 " .
      "ORDER BY group_name, seq", array($formname));
      $j = 0;
      $data = array();
    while ($frow = sqlFetchArray($fres)) {
      $field_id  = $frow['field_id'];
      $currvalue = lbt_current_value($frow, $id);
      if ($currvalue === '') continue;
      $arr[$field_id] = $currvalue;
      if(($frow['list_id'] == 'boolean' && $currvalue != 0)||($frow['list_id'] != 'boolean'))
      {
        if(($field_id == 'refer_to')||($field_id == 'refer_from'))
        {
          $user_name = sqlStatement("SELECT * FROM users WHERE id = $currvalue");
          $extract_name = sqlFetchArray($user_name);
          $data[$j] .= $frow['title'] . " - ";
          $data[$j] .= $extract_name['fname'] . " " .  $extract_name['mname'] . " " . $extract_name['lname'];
        }
        else
        {
            $data[$j] .= $frow['title'] . " - " . $currvalue;
        }
        $j++;
      }
    }
    $length = count($data);
    if($length%2 != 0)  $data[$length] = ' ';
    $content_trans .= "<table>";
    $i = 0; $j = 1;
    while($data[$j] != NULL){
      $content_trans .= '<tr style="line-height: 2">'
                        ."<td>" . $data[$i] . "</td>"
                        ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                        ."<td>" . $data[$j] . "</td></tr>";
      $i = $i + 2;
      $j = $j + 2;
    }
    $content_trans .= "</table>";
}

getPatientTransactions($pid);

$pdf->WriteHTML($content_trans, true, false, true, false, '');

?>
