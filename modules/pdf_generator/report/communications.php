<?php
/*the purpose of this code is to get the 
  patient communication data in a formatted manner 
  and store it in a single variable $content_comu*/

$pdf->SetFont('dejavusans', '', 10);

$content_comu .= '<span style="font-size:25px;font-family: Arial, sans-serif;">Patient Communication sent:<br></span>';
$sql="SELECT concat( 'Messsage Type: ', batchcom.msg_type, ', Message Subject: ', batchcom.msg_subject, ', Sent on:', batchcom.msg_date_sent ) AS batchcom_data, batchcom.msg_text, concat( users.fname, users.lname ) AS'user_name' FROM `batchcom` JOIN `users` ON users.id = batchcom.sent_by WHERE batchcom.patient_id='$pid'";

$result = sqlStatement($sql);
while ($row=sqlFetchArray($result)) {
    $content_comu .= $row{'batchcom_data'}.", By: ".$row{'user_name'}."<br>Text:<br> ".$row{'msg_txt'}."<br>";
}

$pdf->WriteHTML($content_comu, true, false, true, false, '');

?>
