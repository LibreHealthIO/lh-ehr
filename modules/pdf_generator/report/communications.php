<?php
/**
 * The purpose of this code is to get the patient communication data
 * in a formatted manner and store it in a single variable $content_comu.
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

$content_comu .= '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Patient Communication sent") . ':<br></span>';
$sql="SELECT concat( 'Messsage Type: ', batchcom.msg_type, ', Message Subject: ', batchcom.msg_subject, ', Sent on:', batchcom.msg_date_sent ) AS batchcom_data, batchcom.msg_text, concat( users.fname, users.lname ) AS'user_name' FROM `batchcom` JOIN `users` ON users.id = batchcom.sent_by WHERE batchcom.patient_id='$pid'";

$result = sqlStatement($sql);
while ($row=sqlFetchArray($result)) {
    $content_comu .= $row{'batchcom_data'}.", ".xlt("By").": ".$row{'user_name'}."<br>".xlt("Text").":<br> ".$row{'msg_txt'}."<br>";
}

$pdf->WriteHTML($content_comu, true, false, true, false, '');

?>
