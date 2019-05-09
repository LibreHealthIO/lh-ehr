<?php
/**
 * The purpose of this code is to get the patient history data
 * in a formatted manner and store it in a single variable $content_his.
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

if (acl_check('patients', 'med')){
    $content_his = '<span style="font-size:25px;font-family: Arial, sans-serif;">' . xlt("History Data") . ':<br></span>';
    $history_query = sqlStatement("SELECT * FROM history_data WHERE pid='$pid' ORDER BY id DESC");
    while($history_data = sqlFetchArray($history_query)){
      $risk_factors = explode('|', $history_data['risk_factors']);
      $exams = explode('|', $history_data['exams']);
      $family_his_father = $history_data['history_father'];
      $family_his_mother = $history_data['history_mother'];
      $family_his_siblings = $history_data['history_siblings'];
      $family_his_spouse = $history_data['history_spouse'];
      $family_his_offspring = $history_data['history_offspring'];
      $family_dc_father = $history_data['dc_father'];
      $family_dc_mother = $history_data['dc_mother'];
      $family_dc_siblings = $history_data['dc_siblings'];
      $family_dc_spouse = $history_data['dc_spouse'];
      $family_dc_offspring = $history_data['dc_offspring'];
      $relatives_his_cancer = $history_data['relatives_cancer'];
      $relatives_his_tb = $history_data['relatives_tuberculosis'];
      $relatives_his_diabetes = $history_data['relatives_diabetes'];
      $relatives_his_high_bp = $history_data['relatives_high_blood_pressure'];
      $relatives_his_heart_problems = $history_data['relatives_heart_problems'];
      $relatives_his_stroke = $history_data['relatives_stroke'];
      $relatives_his_epilepsy = $history_data['relatives_epilepsy'];
      $relatives_his_mental_illness = $history_data['relatives_mental_illness'];
      $relatives_his_suicide = $history_data['relatives_suicide'];
      $other_his_name1 = $history_data['name_1'];
      $other_his_value1 = $history_data['value_1'];
      $other_his_name2 = $history_data['name_2'];
      $other_his_value2 = $history_data['value_2'];
      $other_his_additional = $history_data['additional_history'];
      $lifestyle_his_tobacco = $history_data['tobacco'];
      $lifestyle_his_alcohol = $history_data['alcohol'];
      $lifestyle_his_sleep_patterns = $history_data['sleep_patterns'];
      $lifestyle_his_exercise_patterns = $history_data['exercise_patterns'];
      $lifestyle_his_seatbelt_use = $history_data['seatbelt_use'];
      $lifestyle_his_counselling = $history_data['counselling'];
      $lifestyle_his_hazardous_activities = $history_data['hazardous_activities'];
      $lifestyle_his_recreational_drugs = $history_data['recreational_drugs'];
      $$lifestyle_his_coffee = $history_data['coffee'];
      break;
    }
    $content_his .= history_data_general($risk_factors, $exams);
    $content_his .= history_data_family($family_his_father, $family_his_mother, $family_his_siblings,
                                        $family_his_spouse, $family_his_offspring, $family_dc_father,
                                        $family_dc_mother, $family_dc_siblings, $family_dc_spouse,
                                        $family_dc_offspring);
    $content_his .= history_data_relatives($relatives_his_cancer, $relatives_his_tb, $relatives_his_diabetes,
                                           $relatives_his_high_bp, $relatives_his_heart_problems, $relatives_his_stroke,
                                           $relatives_his_epilepsy, $relatives_his_mental_illness, $relatives_his_suicide);
    $content_his .= history_data_lifestyle($lifestyle_his_tobacco, $lifestyle_his_alcohol, $lifestyle_his_sleep_patterns,
                                           $lifestyle_his_exercise_patterns, $lifestyle_his_seatbelt_use, $lifestyle_his_counselling,
                                           $lifestyle_his_hazardous_activities, $lifestyle_his_recreational_drugs, $lifestyle_his_coffee);
    $content_his .= history_data_other($other_his_name1, $other_his_value1, $other_his_name2, $other_his_value2, $other_his_additional);
}

function history_data_general($risk_factors, $exams){
  $content_his_general .= '<span style="color:green;font-size:12px;">' . xlt("General") . '</span><br style="line-height: 1">';
  $content_his_general .= "<span><b>" . xlt("Risk Factors") . ":</b></span><br>";
  if(count($risk_factors) > 0){
    if(count($risk_factors)%2 != 0) $risk_factors[count($risk_factors)] = ' ';
    $i = 0;
    $j = 1;
    $content_his_general .= "<table>\n";
    while($risk_factors[$j] != NULL){
      $content_his_general .= '&nbsp;&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5;">'
                              ."<td>" . risk_name($risk_factors[$i]) . "</td>"
                              ."<td>" . risk_name($risk_factors[$j]) . "</td></tr>\n";

     $i = $i + 2;
     $j = $j + 2;
    }
    $content_his_general .= "</table>\n";
  }

  $content_his_general .= '<br><br><span><b>' . xlt("Exams") . "/" . xlt("Tests") . ':</b></span><br style="line-height: 1.5;">';
  $i = 0;
  $j = 1;
  $content_his_general .= "<table>\n";
  if(count($exams)%2 != 0) $exams[count($exams)] = '<td></td>';
  while($exams[$j] != NULL){
    $content_his_general .= '&nbsp;&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5;">'
                            ."<td>" . exam_data($exams[$i]) . "</td>"
                            ."<td>" . exam_data($exams[$j]) . "</td></tr>\n";

    $i = $i + 2;
    $j = $j + 2;
  }
  $content_his_general .= "</table>\n";

  return  $content_his_general;

}
function history_data_family($family_his_father, $family_his_mother, $family_his_siblings,
                            $family_his_spouse, $family_his_offspring, $family_dc_father,
                            $family_dc_mother, $family_dc_siblings, $family_dc_spouse,
                            $family_dc_offspring){  
  $i = 0;
  $content_his_family_temp = array();
  if($family_his_father){
    $content_his_family_temp[$i] .= "<td><b>" . xlt("Father") . " : </b></td><td>" . $family_his_father . "</td>";
    if($family_dc_father) $content_his_family_temp[$i] .= "<td><b>Diagnosis Code : </b>" . $family_dc_father . "</td>";
    $i++;
  }
  if($family_his_mother){
    $content_his_family_temp[$i] .= "<td><b>" . xlt("Mother") . " : </b></td><td>" . $family_his_mother . "</td>";
    if($family_dc_mother) $content_his_family_temp[$i] .= "<td><b>Diagnosis Code : </b>" . $family_dc_mother . "</td>";
    $i++;
  }
  if($family_his_siblings){
    $content_his_family_temp[$i] .= "<td><b>" . xlt("Siblings") . " : </b></td><td>" . $family_his_siblings . "</td>";
    if($family_dc_siblings) $content_his_family_temp[$i] .= "<td><b>Diagnosis Code : </b>" . $family_dc_siblings . "</td>";
    $i++;
  }
  if($family_his_spouse){
    $content_his_family_temp[$i] .= "<td><b>" . xlt("Spouse") . " : </b></td><td>" . $family_his_spouse . "</td>";
    if($family_dc_spouse) $content_his_family_temp[$i] .= "<td><b>Diagnosis Code : </b>" . $family_dc_spouse . "</td>";
    $i++;
  }
  if($family_his_offspring){
    $content_his_family_temp[$i] .= "<td><b>" . xlt("Offspring") . " : </b></td><td>" . $family_his_offspring . "</td>";
    if($family_dc_offspring) $content_his_family_temp[$i] .= "<td><b>Diagnosis Code : </b>" . $family_dc_offspring . "</td>";
    $i++;
  }

  if(count($content_his_family_temp) > 0){
    $content_his_family .= '<br><br><span style="color:green;font-size:12px;">' . xlt("Family History") . '</span><br style="line-height: 2;">';
    $content_his_family .= "<table>";

    $i = 0;
    while($content_his_family_temp[$i] != NULL){
      $content_his_family .= '&nbsp;&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5">'
                             . $content_his_family_temp[$i] . "</tr>\n";

      $i++;
    }
    $content_his_family .= "</table>\n";
  }

  return $content_his_family;
}
function history_data_relatives($relatives_his_cancer, $relatives_his_tb, $relatives_his_diabetes,
                                $relatives_his_high_bp, $relatives_his_heart_problems, $relatives_his_stroke,
                                $relatives_his_epilepsy, $relatives_his_mental_illness, $relatives_his_suicide){
  $i = 0;
  $content_his_relatives_temp = array();
  if($relatives_his_cancer){
    $content_his_relatives_temp[$i] .= "<td><b>" . xlt("Cancer") . " : </b></td><td>" . $relatives_his_cancer . "</td>";
    $i++;
  }
  if($relatives_his_tb){
    $content_his_relatives_temp[$i] .= "<td><b>" . xlt("Tuberculosis") . " : </b></td><td>" . $relatives_his_tb . "</td>";
    $i++;
  }
  if($relatives_his_diabetes){
    $content_his_relatives_temp[$i] .= "<td><b>Diabetes : </b></td><td>" . $relatives_his_diabetes . "</td>";
    $i++;
  }
  if($relatives_his_high_bp){
    $content_his_relatives_temp[$i] .= "<td><b>" . xlt("High Blood Pressure") . " : </b></td><td>" . $relatives_his_high_bp . "</td>";
    $i++;
  }
  if($relatives_his_heart_problems){
    $content_his_relatives_temp[$i] .= "<td><b>" . xlt("Heart Problems") . " : </b></td><td>" . $relatives_his_heart_problems . "</td>";
    $i++;
  }
  if($relatives_his_stroke){
    $content_his_relatives_temp[$i] .= "<td><b>" . xlt("Stroke") . " : </b></td><td>" . $relatives_his_stroke . "</td>";
    $i++;
  }
  if($relatives_his_epilepsy){
    $content_his_relatives_temp[$i] .= "<td><b>" . xlt("Epilepsy") . " : </b></td><td>" . $relatives_his_epilepsy . "</td>";
    $i++;
  }
  if($relatives_his_mental_illness){
    $content_his_relatives_temp[$i] .= "<td><b>" . xlt("Mental Illness") . " : </b></td><td>" . $relatives_his_mental_illness . "</td>";
    $i++;
  }
  if($relatives_his_suicide){
    $content_his_relatives_temp[$i] .= "<td><b>" . xlt("Suicide") . " : </b></td><td>" . $relatives_his_suicide . "</td>";
    $i++;
  }

  if(count($content_his_relatives_temp) > 0){
    $content_his_relatives .= '<br><br><span style="color:green;font-size:12px;">' . xlt("Relatives") . '</span><br style="line-height: 2;">';
    $content_his_relatives .= "<table>";
    if(count($content_his_relatives_temp)%2 != 0)  $content_his_relatives_temp[count($content_his_relatives_temp)] = '<td></td>';

    $i = 0; $j = 1;
    while($content_his_relatives_temp[$j] != NULL){
      $content_his_relatives .= '&nbsp;&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5">'
                             . $content_his_relatives_temp[$i] . $content_his_relatives_temp[$j] . "</tr>\n";

      $i = $i + 2;
      $j = $j + 2;
    }
    $content_his_relatives .= "</table>\n";
  }

  return $content_his_relatives;
}
function history_data_other($other_his_name1, $other_his_value1, $other_his_name2, $other_his_value2, $other_his_additional){
  $i = 0;
  $content_his_other_temp = array();
  if(($other_his_name1)||($other_his_value1)){
    $content_his_other_temp[$i] .= "<td><b>" . xlt("Name/Value") . " : </b></td><td>" . $other_his_name1 . " / " . $other_his_value1 . "</td>";
    $i++;
  }
  if(($other_his_name2)||($other_his_value2)){
    $content_his_other_temp[$i] .= "<td><b>" . xlt("Name/Value") . " : </b></td><td>" . $other_his_name2 . " / " . $other_his_value2 . "</td>";
    $i++;
  }
  if($other_his_additional){
    $content_his_other_temp[$i] .= "<td><b>" . xlt("Addiotional History") . " : </b></td><td>" . $other_his_additional . "</td>";
    $i++;
  }

  if(count($content_his_other_temp) > 0){
    $content_his_other .= '<br><br><span style="color:green;font-size:12px;">' . xlt("Other") . '</span><br style="line-height: 2;">';
    $content_his_other .= "<table>";
    if(count($content_his_other_temp)%2 != 0)  $content_his_other_temp[count($content_his_other_temp)] = '<td></td>';

    $i = 0; $j = 1;
    while($content_his_other_temp[$j] != NULL){
      $content_his_other .= '&nbsp;&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5">'
                             . $content_his_other_temp[$i] . $content_his_other_temp[$j] . "</tr>\n";

      $i = $i + 2;
      $j = $j + 2;
    }
    $content_his_other .= "</table>\n";
  }

  return $content_his_other;
}
function history_data_lifestyle($lifestyle_his_tobacco, $lifestyle_his_alcohol, $lifestyle_his_sleep_patterns,
                                $lifestyle_his_exercise_patterns, $lifestyle_his_seatbelt_use, $lifestyle_his_counselling,
                                $lifestyle_his_hazardous_activities, $lifestyle_his_recreational_drugs, $lifestyle_his_coffee){
  $i = 0;
  $content_his_lifestyle_temp = array();
  if($lifestyle_his_tobacco){
    $content_his_lifestyle_temp[$i] .= "<td><b>" . xlt("Tobacco") . " : </b>" . lifestyle_full_data($lifestyle_his_tobacco) . "</td>";
    $i++;
  }
  if($lifestyle_his_alcohol){
    $content_his_lifestyle_temp[$i] .= "<td><b>" . xlt("Alcohol") . " : </b>" . lifestyle_full_data($lifestyle_his_alcohol) . "</td>";
    $i++;
  }
  if($lifestyle_his_counselling){
    $content_his_lifestyle_temp[$i] .= "<td><b>" . xlt("Counselling") . " : </b>" . lifestyle_full_data($lifestyle_his_counselling) . "</td>";
    $i++;
  }
  if($lifestyle_his_hazardous_activities){
    $content_his_lifestyle_temp[$i] .= "<td><b>" . xlt("Hazardous Activities") . " : </b>" . lifestyle_full_data($lifestyle_his_hazardous_activities) . "</td>";
    $i++;
  }
  if($lifestyle_his_recreational_drugs){
    $content_his_lifestyle_temp[$i] .= "<td><b>" . xlt("Recreational Drugs") . " : </b>" . lifestyle_full_data($lifestyle_his_recreational_drugs) . "</td>";
    $i++;
  }
  if($lifestyle_his_coffee){
    $content_his_lifestyle_temp[$i] .= "<td><b>" . xlt("Coffee") . " : </b>" . lifestyle_full_data($lifestyle_his_coffee) . "</td>";
    $i++;
  }
  if($lifestyle_his_exercise_patterns){
    $content_his_lifestyle_temp[$i] .= "<td><b>" . xlt("Exercise Patterns") . " : </b>" . lifestyle_full_data($lifestyle_his_exercise_patterns) . "</td>";
    $i++;
  }
  if($lifestyle_his_sleep_patterns){
    $content_his_lifestyle_temp[$i] .= "<td><b>" . xlt("Sleep Patterns") . " : </b>" . lifestyle_full_data($lifestyle_his_sleep_patterns) . "</td>";
    $i++;
  }
  if($lifestyle_his_seatbelt_use){
    $content_his_lifestyle_temp[$i] .= "<td><b>" . xlt("Seatbelt Use") . " : </b>" . lifestyle_full_data($lifestyle_his_seatbelt_use) . "</td>";
    $i++;
  }

  if(count($content_his_lifestyle_temp) > 0){
    $content_his_lifestyle .= '<br><br><span style="color:green;font-size:12px;">' . xlt("Lifestyle") . '</span><br style="line-height: 2;">';
    $content_his_lifestyle .= "<table>";
    if(count($content_his_lifestyle_temp)%2 != 0)  $content_his_lifestyle_temp[count($content_his_lifestyle_temp)] = '<td></td>';

    $i = 0;
    while($content_his_lifestyle_temp[$i] != NULL){
      $content_his_lifestyle .= '&nbsp;&nbsp;&nbsp;&nbsp;<tr style="line-height: 1.5">' . $content_his_lifestyle_temp[$i] . "</tr>\n";

      $i++;
    }
    $content_his_lifestyle .= "</table>\n";
  }

  return $content_his_lifestyle;

}

function lifestyle_full_data($lifestyle_type){
  $lifestyle_type_components = explode('|', $lifestyle_type);
  switch($lifestyle_type_components[3])
  {
    case '1':
      $lifestyle_full_data .=  xlt("Current every day smoker ( SNOMED-CT:449868002 )") . "&nbsp;&nbsp;&nbsp;";
      break;

    case '2':
      $lifestyle_full_data .=  xlt("Current some day smoker ( SNOMED-CT:428041000124106 )") . "&nbsp;&nbsp;&nbsp;";
      break;

    case '3':
      $lifestyle_full_data .=  xlt("Former smoker ( SNOMED-CT:8517006 )") . "&nbsp;&nbsp;&nbsp;";
      break;

    case '4':
      $lifestyle_full_data .=  xlt("Never smoker ( SNOMED-CT:266919005 )") . "&nbsp;&nbsp;&nbsp;";
      break;

    case '5':
      $lifestyle_full_data .=  xlt("Smoker, current status unknown ( SNOMED-CT:77176002 )") . "&nbsp;&nbsp;&nbsp;";
      break;

    case '6':
      $lifestyle_full_data .=  xlt("Unknown if ever smoked ( SNOMED-CT:266927001 )") . "&nbsp;&nbsp;&nbsp;";
      break;

    case '7':
      $lifestyle_full_data .=  xlt("Heavy tobacco smoker ( SNOMED-CT:428071000124103 )") . "&nbsp;&nbsp;&nbsp;";
      break;

    case '8':
      $lifestyle_full_data .=  xlt("Light tobacco smoker ( SNOMED-CT:428061000124105 )") . "&nbsp;&nbsp;&nbsp;";
      break;
  }

  $lifestyle_full_data .= $lifestyle_type_components[0] . "&nbsp;&nbsp;&nbsp;";

  switch(substr($lifestyle_type_components[1], 0, 4))
  {
    case 'quit':
      $lifestyle_full_data .= "<b>" . xlt("Status") . " : </b>" . xlt("Quit ") . $lifestyle_type_components[2];
      break;

    case 'curr':
      $lifestyle_full_data .= "<b>" . xlt("Status") . " : </b>" . xlt("Current");
      break;

    case 'neve':
      $lifestyle_full_data .= "<b>" . xlt("Status") . " : </b>" . xlt("Never");
      break;

    case 'not_':
      $lifestyle_full_data .= "<b>" . xlt("Status") . " : </b>" . xlt("N/A");
      break;
  }

  return $lifestyle_full_data;
}

function  risk_name($risk_code){
  switch($risk_code)
  {
    case 'vv':
      return xlt("Varicode Veins");

    case 'ht':
      return xlt("Hypertension");

    case 'db':
      return xlt("Diabetes");

    case 'sc':
      return xlt("Sickle Cell");

    case 'fib':
      return xlt("Fibroids");

    case 'pid':
      return xlt("PID (Pelvic Inflammatory Disease)");

    case 'mig':
      return xlt("Severe Migraine");

    case 'hd':
      return xlt("Heart Disease");

    case 'str':
      return xlt("Thrombosis/Stroke");

    case 'hep':
      return xlt("Hepatitis");

    case 'gb':
      return xlt("Gall Bladder Condition");

    case 'br':
      return xlt("Breast Disease");

    case 'dpr':
      return xlt("Depression");

    case 'all':
      return xlt("Allergies");

    case 'inf':
      return xlt("Infertility");

    case 'ast':
      return xlt("Asthma");

    case 'ep':
      return xlt("Epilepsy");

    case 'cl':
      return xlt("Contact Lenses");

    case 'coc':
      return xlt("Contraceptive Complication (specify)");

    case 'oth':
      return xlt("Other (specify)");
  }
}
function exam_data($exam){
  $exam_components = explode(':', $exam);

  switch($exam_components[0])
  {
    case 'brs':
      $exam_data = xlt("Breast Exam : ");
      break;

    case 'cec':
      $exam_data = xlt("Cardiac Echo : ");
      break;

    case 'ecg':
      $exam_data = xlt("ECG : ");
      break;

    case 'gyn':
      $exam_data = xlt("Gynecological Exam : ");
      break;

    case 'mam':
      $exam_data = xlt("Mammogram : ");
      break;

    case 'phy':
      $exam_data = xlt("Physical Exam : ");
      break;

    case 'pro':
      $exam_data = xlt("Prostate Exam : ");
      break;

    case 'rec':
      $exam_data = xlt("Rectal Exam : ");
      break;

    case 'sic':
      $exam_data = xlt("Sigmoid/Colonoscopy : ");
      break;

    case 'ret':
      $exam_data = xlt("Retinal Exam : ");
      break;

    case 'flu':
      $exam_data = xlt("Flu Vaccination : ");
      break;

    case 'pne':
      $exam_data = xlt("Pneumonia Vaccination : ");
      break;

    case 'ldl':
      $exam_data = xlt("LDL : ");
      break;

    case 'hem':
      $exam_data = xlt("Hemoglobin : ");
      break;

    case 'psa':
      $exam_data = xlt("PSA : ");
  }

  switch($exam_components[1])
  {
    case '0':
      $exam_data .= xlt("N/A") . "&nbsp;&nbsp;&nbsp;&nbsp;";
      break;

    case '1':
      $exam_data .= xlt("Normal") . "&nbsp;&nbsp;&nbsp;&nbsp;";
      break;

    case '2':
      $exam_data .= xlt("Abnormal") . "&nbsp;&nbsp;&nbsp;&nbsp;";
  }

  if($exam_components[2]) $exam_data .= $exam_components[2];
  else $exam_data .= ' ';

  return $exam_data;
}

$pdf->WriteHTML($content_his, true, false, false, false, '');

?>
