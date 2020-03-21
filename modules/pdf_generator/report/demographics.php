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

$content_demo = '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("Patient Data") . ':</span>';

$demographics_query = sqlStatement("SELECT * FROM patient_data WHERE pid='$pid'");
$demographics_data = sqlFetchArray($demographics_query);
$demographics_query_employer = sqlStatement("SELECT * FROM employer_data WHERE pid='$pid' ORDER BY id DESC");
$demographics_data_employer = sqlFetchArray($demographics_query_employer);

$content_demo .= demo_data_face_sheet($pid, $demographics_data);
$content_demo .= demo_data_contact($pid, $demographics_data);
$content_demo .= demo_data_privacy($pid, $demographics_data);
$content_demo .= demo_data_employer($pid, $demographics_data, $demographics_data_employer);
$content_demo .= demo_data_social_statistics($demographics_data);

function demo_data_face_sheet($pid, $demographics_data){
  $i = 0;
  $content_demo_face_sheet_temp = array();
  if(($demographics_data['fname'])||($demographics_data['mname'])||($demographics_data['lname'])){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("NAME") . " : </b></td><td>" . $demographics_data['fname'] . " "
                                    . $demographics_data['mname'] . " " .$demographics_data['lname'] . "</td>";
    $i++;
  }
  if($demographics_data['sex']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("Sex") . " : </b></td><td>" . $demographics_data['sex'] . "</td>";
    $i++;
  }
  if($demographics_data['DOB']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("DOB") . " : </b></td><td>" . $demographics_data['DOB'] . "</td>";
    $i++;
  }
  if($demographics_data['status']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("Martial Status") . " : </b></td><td>" . ucwords($demographics_data['status']) . "</td>";
    $i++;
  }
  if($demographics_data['street']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("Address") . " : </b></td><td>" . $demographics_data['street'] . "</td>";
    $i++;
  }
  if($demographics_data['city']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("City") . " : </b></td><td>" . $demographics_data['city'] . "</td>";
    $i++;
  }
  if($demographics_data['state']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("State") . " : </b></td><td>" . state_name($demographics_data['state']) . "</td>";
    $i++;
  }
  if($demographics_data['postal_code']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("Postal Code") . " : </b></td><td>" . $demographics_data['postal_code'] . "</td>";
    $i++;
  }
  if($demographics_data['ss']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("S.S.N.") . " : </b></td><td>" . $demographics_data['ss'] . "</td>";
    $i++;
  }
  if($demographics_data['drivers_license']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("License"). "/" . xlt("ID") . " : </b></td><td>" . $demographics_data['drivers_license'] . "</td>";
    $i++;
  }
  if($demographics_data['phone_cell']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("Mobile Phone") . " : </b></td><td>" . $demographics_data['phone_cell'] . "</td>";
    $i++;
  }
  if($demographics_data['email']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("Contact Email") . " : </b></td><td>" . $demographics_data['email'] . "</td>";
    $i++;
  }
  if($demographics_data['billing_note']){
    $content_demo_face_sheet_temp[$i] .= "<td><b>" . xlt("Billing Note") . " : </b></td><td>" . $demographics_data['billing_note'] . "</td>";
    $i++;
  }
  
  if(count($content_demo_face_sheet_temp) > 0){
    $content_demo_face_sheet .= '<br><br><span style="color:green;font-size:12px;">' . xlt("Face Sheet") . '</span><br style="line-height: 2;">';
    $content_demo_face_sheet .= "<table>";
    if(count($content_demo_face_sheet_temp)%2 != 0)  $content_demo_face_sheet_temp[count($content_demo_face_sheet_temp)] = '<td></td>';

    $i = 0; $j = 1;
    while($content_demo_face_sheet_temp[$j] != NULL){
      $content_demo_face_sheet .= '&nbsp;&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5">'
                               . $content_demo_face_sheet_temp[$i] . $content_demo_face_sheet_temp[$j] . "</tr>\n";

      $i = $i + 2;
      $j = $j + 2;
    }
    $content_demo_face_sheet .= "</table>\n";
  }

  return $content_demo_face_sheet;
}
function state_name($state_code){
  $state_query = sqlStatement("SELECT zone_name FROM geo_zone_reference WHERE zone_code='$state_code'");
  $state_data = sqlFetchArray($state_query);
  if($state_data == NULL){
    $state_query = sqlStatement("SELECT * FROM list_options WHERE list_id='state' AND option_id='$state_code'");
    $state_data = sqlFetchArray($state_query);
    return $state_data['title'];
  }
  return $state_data['zone_name'];
}

function demo_data_contact($pid, $demographics_data){
  $i = 0;
  $content_demo_contact_temp = array();
  if($demographics_data['providerID']){
    $id = $demographics_data['providerID'];
    $query = sqlStatement("SELECT * FROM users WHERE id ='$id'");
    $data = sqlFetchArray($query);
    $content_demo_contact_temp[$i] .= "<td><b>" . xlt("Provider") . " : </b></td><td>" . $data['fname'] . " " . $data['mname'] . " " . $data['lname'] . "</td>";
    $i++;
  }
  if($demographics_data['ref_providerID']){
    $id = $demographics_data['ref_providerID'];
    $query = sqlStatement("SELECT * FROM users WHERE id ='$id'");
    $data = sqlFetchArray($query);
    $content_demo_contact_temp[$i] .= "<td><b>" . xlt("Provider") . " : </b></td><td>" . $data['fname'] . " " . $data['mname'] . " " . $data['lname'] . "</td>";
    $i++;
  }
  if($demographics_data['phone_home']){
    $content_demo_contact_temp[$i] .= "<td><b>" . xlt("Home Phone") . " : </b></td><td>" . $demographics_data['phone_home'] . "</td>";
    $i++;
  }
  if($demographics_data['phone_biz']){
    $content_demo_contact_temp[$i] .= "<td><b>" . xlt("Work Phone") . " : </b></td><td>" . $demographics_data['phone_biz'] . "</td>";
    $i++;
  }
  if($demographics_data['contact_relationship']){
    $content_demo_contact_temp[$i] .= "<td><b>" . xlt("Emergency Contact") . " : </b></td><td>" . $demographics_data['contact_relationship'] . "</td>";
    $i++;
  }
  if($demographics_data['phone_contact']){
    $content_demo_contact_temp[$i] .= "<td><b>" . xlt("Emergency Phone") . " : </b></td><td>" . $demographics_data['phone_contact'] . "</td>";
    $i++;
  }
  if($demographics_data['mothersname']){
    $content_demo_contact_temp[$i] .= "<td><b>" . xlt("Name of Mother") . " : </b></td><td>" . $demographics_data['mothersname'] . "</td>";
    $i++;
  }
  if($demographics_data['guardiansname']){
    $content_demo_contact_temp[$i] .= "<td><b>" . xlt("Name of Guardian") . " : </b></td><td>" . $demographics_data['guardiansname'] . "</td>";
    $i++;
  }
  if($demographics_data['county']){
    $content_demo_contact_temp[$i] .= "<td><b>" . xlt("County") . " : </b></td><td>" . strtoupper($demographics_data['county']) . "</td>";
    $i++;
  }
  if($demographics_data['country_code']){
    $content_demo_contact_temp[$i] .= "<td><b>" . xlt("Country") . " : </b></td><td>" . $demographics_data['country_code'] . "</td>";
    $i++;
  }

  if(count($content_demo_contact_temp) > 0){
    $content_demo_contact .= '<br><br><span style="color:green;font-size:12px;">' . xlt("Contacts") . '</span><br style="line-height: 2;">';
    $content_demo_contact .= "<table>";
    if(count($content_demo_contact_temp)%2 != 0)  $content_demo_contact_temp[count($content_demo_contact_temp)] = '<td></td>';

    $i = 0; $j = 1;
    while($content_demo_contact_temp[$j] != NULL){
      $content_demo_contact .= '&nbsp;&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5">'
                             . $content_demo_contact_temp[$i] . $content_demo_contact_temp[$j] . "</tr>\n";

      $i = $i + 2;
      $j = $j + 2;
    }
    $content_demo_contact .= "</table>\n";
  }

  return $content_demo_contact;
}

function demo_data_employer($pid, $demographics_data, $demographics_data_employer){
  $i = 0;
  $content_demo_employer_temp = array();
  if($demographics_data['industry']){
    if($demographics_data['industry'] == "law_firm"){
      $content_demo_employer_temp[$i] .= "<td><b>" . xlt("Industry") . " : </b></td><td>" . xlt("Law Firm") . "</td>";
      $i++;
    }
    if($demographics_data['industry'] == "engineering_firm"){
      $content_demo_employer_temp[$i] .= "<td><b>" . xlt("Industry") . " : </b></td><td>" . xlt("Engineering Firm") . "</td>";
      $i++;
    }
    if($demographics_data['industry'] == "construction_firm"){
      $content_demo_employer_temp[$i] .= "<td><b>" . xlt("Industry") . " : </b></td><td>" . xlt("Construction Firm") . "</td>";
      $i++;
    }
    else{
      $content_demo_employer_temp[$i] .= "<td><b>" . xlt("Industry") . " : </b></td><td>" . $demographics_data['industry'] . "</td>";
      $i++;
    }
  }
  if($demographics_data['occupation']){
    $content_demo_employer_temp[$i] .= "<td><b>" . xlt("Occupation") . " : </b></td><td>" . ucwords($demographics_data['occupation']) . "</td>";
    $i++;
  }
  if($demographics_data_employer['name']){
    $content_demo_employer_temp[$i] .= "<td><b>" . xlt("Employer Name") . " : </b></td><td>" . $demographics_data_employer['name'] . "</td>";
    $i++;
  }
  if($demographics_data_employer['street']){
    $content_demo_employer_temp[$i] .= "<td><b>" . xlt("Employer Address") . " : </b></td><td>" . $demographics_data_employer['street'] . "</td>";
    $i++;
  }
  if($demographics_data_employer['city']){
    $content_demo_employer_temp[$i] .= "<td><b>" . xlt("City") . " : </b></td><td>" . $demographics_data_employer['city'] . "</td>";
    $i++;
  }
  if($demographics_data_employer['state']){
    $content_demo_employer_temp[$i] .= "<td><b>" . xlt("State") . " : </b></td><td>" . state_name($demographics_data_employer['state']) . "</td>";
    $i++;
  }
  if($demographics_data_employer['postal_code']){
    $content_demo_employer_temp[$i] .= "<td><b>" . xlt("Postal Code") . " : </b></td><td>" . $demographics_data_employer['postal_code'] . "</td>";
    $i++;
  }
  if($demographics_data_employer['country']){
    $content_demo_employer_temp[$i] .= "<td><b>" . xlt("Country") . " : </b></td><td>" . $demographics_data_employer['country'] . "</td>";
    $i++;
  }

  if(count($content_demo_employer_temp) > 0){
    $content_demo_employer .= '<br><br><span style="color:green;font-size:12px;">' . xlt("Employer") . '</span><br style="line-height: 2;">';
    $content_demo_employer .= "<table>";
    if(count($content_demo_employer_temp)%2 != 0)  $content_demo_employer_temp[count($content_demo_employer_temp)] = '<td></td>';

    $i = 0; $j = 1;
    while($content_demo_employer_temp[$j] != NULL){
      $content_demo_employer .= '&nbsp;&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5">'
                             . $content_demo_employer_temp[$i] . $content_demo_employer_temp[$j] . "</tr>\n";

      $i = $i + 2;
      $j = $j + 2;
    }
    $content_demo_employer .= "</table>\n";
  }

  return $content_demo_employer;
}

function demo_data_privacy($pid, $demographics_data){
  $i = 0;
  $content_demo_privacy_temp = array();
  if($demographics_data['allow_patient_portal']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Allow Patient Portal") . " : </b></td><td>" . $demographics_data['allow_patient_portal'] . "</td>";
    $i++;
  }
  if($demographics_data['email_direct']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Trusted Email") . " : </b></td><td>" . $demographics_data['email_direct'] . "</td>";
    $i++;
  }
  if($demographics_data['hippa_notice']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Privacy Notice Received") . " : </b></td><td>" . $demographics_data['hippa_notice'] . "</td>";
    $i++;
  }
  if($demographics_data['hippa_voice']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Allow Voice Message") . " : </b></td><td>" . $demographics_data['hippa_voice'] . "</td>";
    $i++;
  }
  if($demographics_data['hippa_message']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Leave Message With") . " : </b></td><td>" . $demographics_data['hippa_message'] . "</td>";
    $i++;
  }
  if($demographics_data['hippa_mail']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Allow Mail Message") . " : </b></td><td>" . $demographics_data['hippa_mail'] . "</td>";
    $i++;
  }
  if($demographics_data['hippa_allowsms']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Allow SMS") . " : </b></td><td>" . $demographics_data['hippa_allowsms'] . "</td>";
    $i++;
  }
  if($demographics_data['hippa_allowemail']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Allow Email") . " : </b></td><td>" . $demographics_data['hippa_allowemail'] . "</td>";
    $i++;
  }
  if($demographics_data['allow_imm_reg_use']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Allow Immunization Registry Use") . " : </b></td><td>" . $demographics_data['allow_imm_reg_use'] . "</td>";
    $i++;
  }
  if($demographics_data['allow_imm_info_share']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Allow Immunization Info Sharing") . " : </b></td><td>" . $demographics_data['allow_imm_info_share'] . "</td>";
    $i++;
  }
  if($demographics_data['allow_health_info_ex']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Allow Health Information Exchange") . " : </b></td><td>" . $demographics_data['allow_health_info_ex'] . "</td>";
    $i++;
  }
  if($demographics_data['vfc']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("VFC") . " : </b></td><td>" . $demographics_data['vfc'] . "</td>";
    $i++;
  }
  if($demographics_data['deceased_date']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Date Deceased") . " : </b></td><td>" . oeFormatSDFT(strtotime($demographics_data['deceased_date'])) . "</td>";
    $i++;
  }
  if($demographics_data['deceased_reason']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Reason Deceased") . " : </b></td><td>" . $demographics_data['deceased_reason'] . "</td>";
    $i++;
  }
  if($demographics_data['statement_y_n']){
    $content_demo_privacy_temp[$i] .= "<td><b>" . xlt("Print Statement") . " : </b></td><td>" . $demographics_data['statement_y_n'] . "</td>";
    $i++;
  }

  if(count($content_demo_privacy_temp) > 0){
    $content_demo_privacy .= '<br><br><span style="color:green;font-size:12px;">' . xlt("Privacy") . '</span><br style="line-height: 2;">';
    $content_demo_privacy .= "<table>";
    if(count($content_demo_privacy_temp)%2 != 0)  $content_demo_privacy_temp[count($content_demo_privacy_temp)] = '<td></td>';

    $i = 0; $j = 1;
    while($content_demo_privacy_temp[$j] != NULL){
      $content_demo_privacy .= '&nbsp;&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5">'
                             . $content_demo_privacy_temp[$i] . $content_demo_privacy_temp[$j] . "</tr>\n";

      $i = $i + 2;
      $j = $j + 2;
    }
    $content_demo_privacy .= "</table>\n";
  }

  return $content_demo_privacy;
}

function demo_data_social_statistics($demographics_data){
  $i = 0;
  $content_demo_social_statistics_temp = array();
  if($demographics_data['language']){
    $content_demo_social_statistics_temp[$i] .= "<td><b>" . xlt("Language") . " : </b></td><td>" . $demographics_data['language'] . "</td>";
    $i++;
  }
  if($demographics_data['interpretter']){
    $content_demo_social_statistics_temp[$i] .= "<td><b>" . xlt("Interpreter") . " : </b></td><td>" . $demographics_data['interpretter'] . "</td>";
    $i++;
  }
  if($demographics_data['ethnicity']){
    $content_demo_social_statistics_temp[$i] .= "<td><b>" . xlt("Ethnicity") . " : </b></td><td>" . $demographics_data['ethnicity'] . "</td>";
    $i++;
  }
  if($demographics_data['family_size']){
    $content_demo_social_statistics_temp[$i] .= "<td><b>" . xlt("Family Size") . " : </b></td><td>" . $demographics_data['family_size'] . "</td>";
    $i++;
  }
  if(($demographics_data['financial_review'] != '0000-00-00 00:00:00')&&($demographics_data['financial_review'] != NULL)){
    $content_demo_social_statistics_temp[$i] .= "<td><b>" . xlt("Financial Review Date") . " : </b></td><td>" . $demographics_data['financial_review'] . "</td>";
    $i++;
  }
  if($demographics_data['monthly_income']){
    $content_demo_social_statistics_temp[$i] .= "<td><b>" . xlt("Monthly Income") . " : </b></td><td>" . $demographics_data['monthly_income'] . "</td>";
    $i++;
  }
  if($demographics_data['homeless']){
    $content_demo_social_statistics_temp[$i] .= "<td><b>" . xlt("Homeless") . " : </b></td><td>" . $demographics_data['homeless'] . "</td>";
    $i++;
  }
  if($demographics_data['migrantseasonal']){
    $content_demo_social_statistics_temp[$i] .= "<td><b>" . xlt("Migrant") . "/" . xlt("Seasonal") . " : </b></td><td>" . $demographics_data['migrantseasonal'] . "</td>";
    $i++;
  }
  if($demographics_data['religion']){
    $content_demo_social_statistics_temp[$i] .= "<td><b>" . xlt("Religion") . " : </b></td><td>" . ucwords($demographics_data['religion']) . "</td>";
    $i++;
  }

  if(count($content_demo_social_statistics_temp) > 0){
    $content_demo_social_statistics .= '<br><br><span style="color:green;font-size:12px;">' . xlt("Social Statistics") . '</span><br style="line-height: 2;">';
    $content_demo_social_statistics .= "<table>";
    if(count($content_demo_social_statistics_temp)%2 != 0)  $content_demo_social_statistics_temp[count($content_demo_social_statistics_temp)] = '<td></td>';

    $i = 0; $j = 1;
    while($content_demo_social_statistics_temp[$j] != NULL){
      $content_demo_social_statistics .= '&nbsp;&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5">'
                             . $content_demo_social_statistics_temp[$i] . $content_demo_social_statistics_temp[$j] . "</tr>\n";

      $i = $i + 2;
      $j = $j + 2;
    }
    $content_demo_social_statistics .= "</table>\n";
  }

  return $content_demo_social_statistics;
}

$pdf->WriteHTML($content_demo, true, false, false, false, '');

?>
