<?php
/*
 * These functions are common functions used in Immunization report.
 * They have been pulled out and placed in this file. This is done to prepare
 * the for building a report generator.
 *
 * Copyright (C) 2018 Tigpezeghe Rodrige <tigrodrige@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Tigpezeghe Rodrige <tigrodrige@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 */

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("../../library/report_functions.php");
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

if(isset($_POST['form_from_date'])) {
  $from_date = $_POST['form_from_date'] !== "" ?
    fixDate($_POST['form_from_date'], date('Y-m-d')) :
    0;
}

if(isset($_POST['form_to_date'])) {
  $to_date =$_POST['form_to_date'] !== "" ?
    fixDate($_POST['form_to_date'], date('Y-m-d')) :
    0;
}

$form_code = isset($_POST['form_code']) ? $_POST['form_code'] : Array();

if (empty ($form_code) ) {
  $query_codes = '';
} else {
    $query_codes = 'c.id in (';
    foreach( $form_code as $code ){ $query_codes .= $code . ","; }
    $query_codes = substr($query_codes ,0,-1);
    $query_codes .= ') and ';
}

function tr($a) {
  return (str_replace(' ','^',$a));
}

function format_cvx_code($cvx_code) {
  if ( $cvx_code < 10 ) {
    return "0$cvx_code";
  }
  return $cvx_code;
}

function format_phone($phone) {
  $phone = preg_replace("/[^0-9]/", "", $phone);
    switch (strlen($phone)) {
      case 7:
        return tr(preg_replace("/([0-9]{3})([0-9]{4})/", "000 $1$2", $phone));
      case 10:
        return tr(preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1 $2$3", $phone));
      default:
        return tr("000 0000000");
    }
}

function format_ethnicity($ethnicity) {
  switch ($ethnicity) {
    case "hisp_or_latin":
      return ("H^Hispanic or Latino^HL70189");
    case "not_hisp_or_latin":
      return ("N^not Hispanic or Latino^HL70189");
    default: // Unknown
      return ("U^Unknown^HL70189");
  }
}

$query = "select " .
  "i.patient_id as patientid, " .
  "p.language, ".
  "i.cvx_code , " ;

if ($_POST['form_get_hl7']==='true') {
  $query .= "DATE_FORMAT(p.DOB,'%Y%m%d') as DOB, ".
      "concat(p.street, '^^', p.city, '^', p.state, '^', p.postal_code) as address, ".
      "p.country_code, ".
      "p.phone_home, ".
      "p.phone_biz, ".
      "p.status, ".
      "p.sex, ".
      "p.ethnoracial, ".
      "p.race, ".
      "p.ethnicity, ".
      "c.code_text, ".
      "c.code, ".
      "c.code_type, ".
      "DATE_FORMAT(i.vis_date,'%Y%m%d') as immunizationdate, ".
      "DATE_FORMAT(i.administered_date,'%Y%m%d') as administered_date, ".
      "i.lot_number as lot_number, ".
      "i.manufacturer as manufacturer, ".
       "concat(p.fname, '^', p.lname) as patientname, ";
} else {
    $query .= "concat(p.fname, ' ',p.mname,' ', p.lname) as patientname, ".
      "i.vis_date as immunizationdate, "  ;
}

$query .= "i.id as immunizationid, c.code_text_short as immunizationtitle ".
  "from immunizations i, patient_data p, codes c ".
  "left join code_types ct on c.code_type = ct.ct_id ".
  "where ".
  "ct.ct_key='CVX' and ";

if($from_date!=0) {
  $query .= "i.vis_date >= '$from_date' " ;
}

if($from_date!=0 and $to_date!=0) {
  $query .= " and " ;
}

if($to_date!=0) {
  $query .= "i.vis_date <= '$to_date' ";
}

if($from_date!=0 or $to_date!=0) {
  $query .= " and " ;
}

$query .= "i.patient_id=p.pid and ".
$query_codes . "i.cvx_code = c.code and ";

//do not show immunization added erroneously
$query .=  "i.added_erroneously = 0";

//echo "<p> DEBUG query: $query </p>\n"; // debugging

$D="\r";
$nowdate = date('Ymd');
$now = date('YmdGi');
$now1 = date('Y-m-d G:i');
$filename = "imm_reg_". $now . ".hl7";

// GENERATE HL7 FILE
if ($_POST['form_get_hl7']==='true') {
  $content = '';
  $res = sqlStatement($query);

  while ($r = sqlFetchArray($res)) {
    $content .= "MSH|^~\&|LIBREEHR||||$nowdate||".
      "VXU^V04^VXU_V04|LIBREEHR-110316102457117|P|2.5.1" .
      "$D" ;
    if ($r['sex']==='Male') $r['sex'] = 'M';
    if ($r['sex']==='Female') $r['sex'] = 'F';
    if ($r['status']==='married') $r['status'] = 'M';
    if ($r['status']==='single') $r['status'] = 'S';
    if ($r['status']==='divorced') $r['status'] = 'D';
    if ($r['status']==='widowed') $r['status'] = 'W';
    if ($r['status']==='separated') $r['status'] = 'A';
    if ($r['status']==='domestic partner') $r['status'] = 'P';
    $content .= "PID|" . // [[ 3.72 ]]
        "|" . // 1. Set id
        "|" . // 2. (B)Patient id
        $r['patientid']. "^^^MPI&2.16.840.1.113883.19.3.2.1&ISO^MR" . "|". // 3. (R) Patient indentifier list. TODO: Hard-coded the OID from NIST test.
        "|" . // 4. (B) Alternate PID
        $r['patientname']."|" . // 5.R. Name
        "|" . // 6. Mather Maiden Name
        $r['DOB']."|" . // 7. Date, time of birth
        $r['sex']."|" . // 8. Sex
        "|" . // 9.B Patient Alias
        "2106-3^" . $r['race']. "^HL70005" . "|" . // 10. Race // Ram change
        $r['address'] . "^^M" . "|" . // 11. Address. Default to address type  Mailing Address(M)
        "|" . // 12. county code
        "^PRN^^^^" . format_phone($r['phone_home']) . "|" . // 13. Phone Home. Default to Primary Home Number(PRN)
        "^WPN^^^^" . format_phone($r['phone_biz']) . "|" . // 14. Phone Work.
        "|" . // 15. Primary language
        $r['status']."|" . // 16. Marital status
        "|" . // 17. Religion
        "|" . // 18. patient Account Number
        "|" . // 19.B SSN Number
        "|" . // 20.B Driver license number
        "|" . // 21. Mathers Identifier
        format_ethnicity($r['ethnicity']) . "|" . // 22. Ethnic Group
        "|" . // 23. Birth Plase
        "|" . // 24. Multiple birth indicator
        "|" . // 25. Birth order
        "|" . // 26. Citizenship
        "|" . // 27. Veteran military status
        "|" . // 28.B Nationality
        "|" . // 29. Patient Death Date and Time
        "|" . // 30. Patient Death Indicator
        "|" . // 31. Identity Unknown Indicator
        "|" . // 32. Identity Reliability Code
        "|" . // 33. Last Update Date/Time
        "|" . // 34. Last Update Facility
        "|" . // 35. Species Code
        "|" . // 36. Breed Code
        "|" . // 37. Breed Code
        "|" . // 38. Production Class Code
        ""  . // 39. Tribal Citizenship
        "$D" ;
    $content .= "ORC" . // ORC mandatory for RXA
        "|" .
        "RE" .
        "$D" ;
    $content .= "RXA|" .
        "0|" . // 1. Give Sub-ID Counter
        "1|" . // 2. Administrattion Sub-ID Counter
        $r['administered_date']."|" . // 3. Date/Time Start of Administration
        $r['administered_date']."|" . // 4. Date/Time End of Administration
        format_cvx_code($r['code']). "^" . $r['immunizationtitle'] . "^" . "CVX" ."|" . // 5. Administration Code(CVX)
        "999|" . // 6. Administered Amount. TODO: Immunization amt currently not captured in database, default to 999(not recorded)
        "|" . // 7. Administered Units
        "|" . // 8. Administered Dosage Form
        "|" . // 9. Administration Notes
        "|" . // 10. Administering Provider
        "|" . // 11. Administered-at Location
        "|" . // 12. Administered Per (Time Unit)
        "|" . // 13. Administered Strength
        "|" . // 14. Administered Strength Units
        $r['lot_number']."|" . // 15. Substance Lot Number
        "|" . // 16. Substance Expiration Date
        "MSD" . "^" . $r['manufacturer']. "^" . "HL70227" . "|" . // 17. Substance Manufacturer Name
        "|" . // 18. Substance/Treatment Refusal Reason
        "|" . // 19.Indication
        "|" . // 20.Completion Status
        "A" . // 21.Action Code - RXA
        "$D" ;
  }

  // send the header here
  header('Content-type: text/plain');
  header('Content-Disposition: attachment; filename=' . $filename );

  // put the content in the file
  echo($content);
  exit;
}

?>
