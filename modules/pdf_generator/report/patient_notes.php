<?php
/* the purpose of this code is to
   get data about patient notes in a formatted manner
   and store in a variable $content_notes*/


$pdf->SetFont('dejavusans', '', 10);
$content_notes = '<span style="font-size:25px;font-family: Arial, sans-serif;">Patient Notes:<br></span>';
pdfPatientNotes($pid);

function pdfPatientNotes($pid) {
    global $content_notes;
    // exclude ALL deleted notes
    $res = sqlStatement("SELECT * FROM pnotes WHERE pid = '$pid' AND deleted != 1 AND activity = 1 ORDER BY date");
    while($result = sqlFetchArray($res)) {
      $content_notes .= "<b>" . oeFormatSDFT(strtotime($result{"date"})) .
        ":</b>" .
        stripslashes(oeFormatPatientNote($result['body'])) . "</b><br>";
    }
}

$pdf->WriteHTML($content_notes, true, false, true, false, '');

?>
