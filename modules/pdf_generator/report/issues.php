<?php
/**
 * The purpose of this code is to get the patient issues data
 * in a formatted manner and store it in a single variable $content_issues.
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

foreach($_POST as $issue_name => $issue_value){
    if(substr($issue_name, 0, 5) == "issue")
    {
        pdfIssuesData($pid, substr($issue_name, 6), "1");
    }
}

function pdfIssuesData($pid, $issue_id, $activity = "%")
{
    global $issue_allergy, $issue_medical_problem, $issue_medication, $issue_surgery, $issue_dental;

    $issues_query = sqlStatement("SELECT * FROM lists WHERE pid='$pid' AND id='$issue_id' AND activity LIKE '$activity'");
    while($issues_data = sqlFetchArray($issues_query))
    {
        if($issues_data['type'] == "allergy")
        {
            if(!$issue_allergy) $issue_allergy .= '<br style="line-height:20px;"> <b>' . xlt("Allergies:") . "</b>";
            $issue_allergy .= '<br style="line-height:15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $issues_data['title'] . ": " . $issues_data['comments'];
        }

        if($issues_data['type'] == "medical_problem")
        {
            if(!$issue_medical_problem) $issue_medical_problem .= '<br style="line-height:20px;"> <b>' . xlt("Medical Problems:") . "</b>";
            $issue_medical_problem .= '<br style="line-height:15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $issues_data['title'] . ": " . $issues_data['comments'];
        }
        if($issues_data['type'] == "medication")
        {
            if(!$issue_medication) $issue_medication .= '<br style="line-height:20px;"> <b>' . xlt("Medications:") . "</b>";
            $issue_medication .= '<br style="line-height:15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $issues_data['title'] . ": " . $issues_data['comments'];
        }
        if($issues_data['type'] == "surgery")
        {
            if(!$issue_surgery) $issue_surgery .= '<br style="line-height:20px;"> <b>' . xlt("Surgeries:") . "</b>";
            $issue_surgery .= '<br style="line-height:15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $issues_data['title'] . ": " . $issues_data['comments'];
        }
        if($issues_data['type'] == "dental")
        {
            if(!$issue_dental) $issue_dental .= '<br style="line-height:20px;"> <b>' . xlt("Dental Problems:") . "</b>";
            $issue_dental .= '<br style="line-height:15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $issues_data['title'] . ": " . $issues_data['comments'];
        }
    }
}

if($issue_allergy||$issue_medical_problem||$issue_medication||$issue_surgery||$issue_dental)
{
    $content_issues = '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Issues") . ':</span>';
    $content_issues .= $issue_allergy . $issue_medical_problem . $issue_medication . $issue_surgery . $issue_dental;
    $pdf->Ln(2);
    $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());
    $pdf->Ln(2);
    $pdf->WriteHTML($content_issues, true, false, false, false, '');
}

?>
