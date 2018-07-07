<?php
/**
 * The purpose of this code is to get the patient demographics data
 * in a formatted manner and store it in a single variable $content_demo.
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

$content_demo = '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Patient Data") . ':<br></span>';
$result1 = getPatientData($pid);
$result2 = getEmployerData($pid);

$content_demo .= "<table>";
pdf_layout_rows_demo('DEM', $result1, $result2);
$content_demo .= "</table>";

$item_count = 0;
$cell_count = 0;
$last_group = '';
$CPR = 4; // cells per row of generic data


function pdf_layout_rows_demo($formtype, $result1, $result2='') {
    global $item_count, $cell_count, $last_group, $CPR, $content_demo;

    $fres = sqlStatement("SELECT * FROM layout_options " .
      "WHERE form_id = ? AND uor > 0 " .
      "ORDER BY group_name, seq", array($formtype) );

    while ($frow = sqlFetchArray($fres)) {
      $this_group = $frow['group_name'];
      $titlecols  = $frow['titlecols'];
      $datacols   = $frow['datacols'];
      $data_type  = $frow['data_type'];
      $field_id   = $frow['field_id'];
      $list_id    = $frow['list_id'];
      $currvalue  = '';

      if ($formtype == 'DEM') {
        if (strpos($field_id, 'em_') === 0) {
          // Skip employer related fields, if it's disabled.
          if ($GLOBALS['omit_employers']) continue;
          $tmp = substr($field_id, 3);
          if (isset($result2[$tmp])) $currvalue = $result2[$tmp];
        }
        else {
          if (isset($result1[$field_id])) $currvalue = $result1[$field_id];
        }
      }
      else {
        if (isset($result1[$field_id])) $currvalue = $result1[$field_id];
      }

      // Handle a data category (group) change.
      if (strcmp($this_group, $last_group) != 0) {
        $group_name = substr($this_group, 1);
        // totally skip generating the employer category, if it's disabled.
        if ($group_name === 'Employer' && $GLOBALS['omit_employers']) continue;
        pdf_end_group_demo();
        $last_group = $this_group;
      }

      // filter out all the empty field data from the patient report.
      if (!empty($currvalue) && !($currvalue == '0000-00-00 00:00:00')) {
      // Handle starting of a new row.
      if (($titlecols > 0 && $cell_count >= $CPR) || $cell_count == 0) {
        pdf_end_row_demo();
        $content_demo .= "<tr " . 'style="line-height: 2">';
        if ($group_name) {
          $content_demo .= '<td valign="top" ' . 'style="color:green">';

          // Added 5-09 by BM - Translate label if applicable
          $content_demo .= htmlspecialchars(xl_layout_label($group_name),ENT_NOQUOTES);

          $group_name = '';
        } else {
          $content_demo .= '<td valign="top">&nbsp;';
        }
        $content_demo .= "</td>";
      }

      if ($item_count == 0 && $titlecols == 0) $titlecols = 1;

      // Handle starting of a new label cell.
      if ($titlecols > 0) {
        pdf_end_cell_demo();
        $titlecols_esc = htmlspecialchars( $titlecols, ENT_QUOTES);
        $content_demo .= '<td colspan="' . $titlecols_esc . '"';
        $content_demo .= ">";
        $cell_count += $titlecols;
      }
      ++$item_count;

      // Added 5-09 by BM - Translate label if applicable
      if ($frow['title']) $content_demo .= htmlspecialchars(xl_layout_label($frow['title']).":",ENT_NOQUOTES); else "&nbsp;";

      // Handle starting of a new data cell.
      if ($datacols > 0) {
        pdf_end_cell_demo();
        $datacols_esc = htmlspecialchars( $datacols, ENT_QUOTES);
        $content_demo .= '<td colspan="' . $datacols_esc . '"';
        $content_demo .= ">";
        $cell_count += $datacols;
      }

      ++$item_count;
      $content_demo .= generate_display_field($frow, $currvalue);
      }
    }

    pdf_end_group_demo();
  }

  function pdf_end_group_demo() {
    global $last_group, $content_demo;
    if (strlen($last_group) > 0) {
      pdf_end_row_demo();
    }
  }

  function pdf_end_row_demo() {
    global $cell_count, $CPR, $content_demo;
    pdf_end_cell_demo();
    if ($cell_count > 0) {
      for (; $cell_count < $CPR; ++$cell_count) $content_demo .= "<td></td>";
      $content_demo .= "</tr>\n";
      $cell_count = 0;
    }
  }

  function pdf_end_cell_demo() {
    global $item_count, $cell_count, $content_demo;
    if ($item_count > 0) {
      $content_demo .= "</td>";
      $item_count = 0;
    }
  }

$pdf->WriteHTML($content_demo, true, false, false, false, '');

?>
