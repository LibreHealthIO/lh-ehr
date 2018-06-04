<?php
/*the purpose of this code is to get the 
  patient history data in a formatted manner 
  and store it in a single variable $content_trans*/

$pdf->SetFont('dejavusans', '', 10);

if (acl_check('patients', 'med')) {
    $content_his = '<span style="font-size:25px;font-family: Arial, sans-serif;">History Data:<br></span>';
    $result1 = getHistoryData($pid);
    $content_his .= "<table>";
    pdf_layout_rows_history('HIS', $result1);
    $content_his .= "</table>";
}

$item_count = 0;
$cell_count = 0;
$last_group = '';
$CPR = 4; // cells per row of generic data


function pdf_layout_rows_history($formtype, $result1, $result2='') {
    global $item_count, $cell_count, $last_group, $CPR, $content_his;
  
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
        pdf_end_group_history();
        $last_group = $this_group;
      }
  
      // filter out all the empty field data from the patient report.
      if (!empty($currvalue) && !($currvalue == '0000-00-00 00:00:00')) {
      // Handle starting of a new row.
      if (($titlecols > 0 && $cell_count >= $CPR) || $cell_count == 0) {
        pdf_end_row_history();
        $content_his .= "<tr>";
        if ($group_name) {
          $content_his .= '<td valign="top" ' . 'style="color:green">';
  
          // Added 5-09 by BM - Translate label if applicable
          $content_his .= htmlspecialchars(xl_layout_label($group_name),ENT_NOQUOTES);
  
          $group_name = '';
        } else {
          $content_his .= '<td valign="top">&nbsp;';
        }
        $content_his .= "</td>";
      }
  
      if ($item_count == 0 && $titlecols == 0) $titlecols = 1;
  
      // Handle starting of a new label cell.
      if ($titlecols > 0) {
        pdf_end_cell_history();
        $titlecols_esc = htmlspecialchars( $titlecols, ENT_QUOTES);
        $content_his .= "<td class='label' colspan='$titlecols_esc' ";
        $content_his .= ">";
        $cell_count += $titlecols;
      }
      ++$item_count;
  
      // Added 5-09 by BM - Translate label if applicable
      if ($frow['title']) $content_his .= htmlspecialchars(xl_layout_label($frow['title']).":",ENT_NOQUOTES); else "&nbsp;";
  
      // Handle starting of a new data cell.
      if ($datacols > 0) {
        pdf_end_cell_history();
        $datacols_esc = htmlspecialchars( $datacols, ENT_QUOTES);
        $content_his .= "<td class='text data' colspan='$datacols_esc'";
        $content_his .= ">";
        $cell_count += $datacols;
      }
  
      ++$item_count;
      $content_his .= generate_display_field($frow, $currvalue);
      }
    }
  
    pdf_end_group_history();
  }

  function pdf_end_group_history() {
    global $last_group, $content_his;
    if (strlen($last_group) > 0) {
      pdf_end_row_history();
    }
  }

  function pdf_end_row_history() {
    global $cell_count, $CPR, $content_his;
    pdf_end_cell_history();
    if ($cell_count > 0) {
      for (; $cell_count < $CPR; ++$cell_count) echo "<td></td>";
      $content_his .= "</tr>\n";
      $cell_count = 0;
    }
  }

  function pdf_end_cell_history() {
    global $item_count, $cell_count, $content_his;
    if ($item_count > 0) {
      $content_his .= "</td>";
      $item_count = 0;
    }
  }

$pdf->WriteHTML($content_his, true, false, false, false, '');

?>
