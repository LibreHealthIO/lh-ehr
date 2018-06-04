<?php
/*the purpose of this code is to get the 
  patient immunizations data in a formatted manner 
  and store it in a single variable $content_immu*/

$pdf->SetFont('dejavusans', '', 10);

if (acl_check('patients', 'med')) {
    $content_immu = '<span style="font-size:25px;font-family: Arial, sans-serif;">Patient Immunizations:<br></span>';;
    $sql = "select i1.immunization_id, i1.administered_date, substring(i1.note,1,20) as immunization_note, c.code_text_short ".
       " from immunizations i1 ".
       " left join code_types ct on ct.ct_key = 'CVX' ".
       " left join codes c on c.code_type = ct.ct_id AND i1.cvx_code = c.code ".
       " where i1.patient_id = '$pid' and i1.added_erroneously = 0 ".
       " order by administered_date desc";
    $result = sqlStatement($sql);
    while ($row=sqlFetchArray($result)) {
      // Figure out which name to use (ie. from cvx list or from the custom list)
      if ($GLOBALS['use_custom_immun_list']) {
         $vaccine_display = generate_display_field(array('data_type'=>'1','list_id'=>'immunizations'), $row['immunization_id']);
      }
      else {
         if (!empty($row['code_text_short'])) {
            $vaccine_display = htmlspecialchars( xl($row['code_text_short']), ENT_NOQUOTES);
         }
         else {
            $vaccine_display = generate_display_field(array('data_type'=>'1','list_id'=>'immunizations'), $row['immunization_id']);
         }
      }
      $content_immu .= $row['administered_date'] . " - " . $vaccine_display;
        if ($row['immunization_note']) {
            $content_immu .= " - " . $row['immunization_note'];
        }
    }
}

$pdf->WriteHTML($content_immu, true, false, true, false, '');

?>
