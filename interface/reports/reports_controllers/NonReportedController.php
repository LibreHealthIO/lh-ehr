<?php
/*
 * These functions are common functions used in Syndromic Surveillance (Non-reported) report.
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
require_once("../../custom/code_types.inc.php");
require_once("../../library/report_functions.php");
require_once($GLOBALS['srcdir'] . "/formatting.inc.php");

$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

// Ensoftek: Jul-2015: Get the facility of the logged in user.
function getLoggedInUserFacility(){
    $sql = "SELECT f.name, f.facility_npi FROM users AS u LEFT JOIN facility AS f ON u.facility_id = f.id WHERE u.id=?";
    $res = sqlStatement($sql, array($_SESSION['authUserID']) );
     while ($arow = sqlFetchArray($res)) {
        return $arow;
    }
    return null;
}

// Ensoftek: Jul-2015: Map codes to confirm to HL7.
function mapCodeType($incode) {
    $outcode = null;
    $code = explode(":", $incode);
    switch ($code[0]) {
        case "ICD9":
            $outcode = "I9CDX";
            break;
        case "ICD10":
            $outcode = "I10";
            break;
        case "SNOMED-CT":
            $outcode = "SCT";
            break;
        case "US Ext SNOMEDCT":
            $outcode = "SCT";
            break;
        default:
            $outcode = "I9CDX"; // default to ICD9
            break;
            // Only ICD9, ICD10 and SNOMED codes allowed in Syndromic Surveillance
    }
    return $outcode;
}


if(isset($_POST['form_from_date'])) {
  	$from_date = $_POST['form_from_date'] !== "" ?
        fixDate($_POST['form_from_date'], date(DateFormatRead(true))) :
    0;
}

if(isset($_POST['form_to_date'])) {
  	$to_date =$_POST['form_to_date'] !== "" ?
        fixDate($_POST['form_to_date'], date(DateFormatRead(true))) :
    0;
}
//
$form_code = isset($_POST['form_code']) ? $_POST['form_code'] : Array();
//
if (empty ($form_code) ) {
  	$query_codes = '';
} else {
  	$query_codes = 'c.id in (';
    	foreach ($form_code as $code) {
        	$query_codes .= $code . ",";
    	}
    $query_codes = substr($query_codes ,0,-1);
    $query_codes .= ') and ';
}

function tr($a) {
  	return (str_replace(' ','^',$a));
}

$query =
  "select " .
  "l.pid as patientid, " .
  "p.language, ".
  "l.diagnosis , " ;

if ($_POST['form_get_hl7']==='true') {
    $query .=
      "DATE_FORMAT(p.DOB,'%Y%m%d') as DOB, ".
      "concat(p.street, '^',p.postal_code,'^', p.city, '^', p.state) as address, ".
      "p.country_code, ".
      "p.phone_home, ".
      "p.phone_biz, ".
      "p.status, ".
      "p.sex, ".
      "p.ethnoracial, ".
      "c.code_text, ".
      "c.code, ".
      "c.code_type, ".
      "DATE_FORMAT(l.date,'%Y%m%d') as issuedate, ".
      "concat(p.fname, '^',p.mname,'^', p.lname) as patientname, ";
} else {
    $query .= "concat(p.fname, ' ',p.mname,' ', p.lname) as patientname, ".
      "l.date as issuedate, "  ;
}

$query .=
  "l.id as issueid, l.title as issuetitle, DATE_FORMAT(l.begdate,'%Y%m%d%H%i') as begin_date ". // Ensoftek: Jul-2015: Get begin date
  "from lists l, patient_data p, codes c ".
  "where ".
  "c.reportable=1 and ".
  "l.id not in (select lists_id from syndromic_surveillance) and ";

if($from_date!=0) {
    $query .= "l.date >= '$from_date' " ;
}

if($from_date!=0 and $to_date!=0) {
    $query .= " and " ;
}

if($to_date!=0) {
    $query .= "l.date <= '$to_date' ";
}

if($from_date!=0 or $to_date!=0) {
    $query .= " and " ;
}

$query .= "l.pid=p.pid and ".
	$query_codes .
  	"l.diagnosis LIKE 'ICD9:%' and ".
  	"substring(l.diagnosis,6) = c.code ";

//echo "<p> DEBUG query: $query </p>\n"; // debugging

$D="\r";
$nowdate = date('YmdHi');
$now = date('YmdGi');
$now1 = date('Y-m-d G:i');
$filename = "syn_sur_". $now . ".hl7";

// Ensoftek: Jul-2015: Get logged in user's facility to be used in the MSH segment
$facility_info = getLoggedInUserFacility();

// GENERATE HL7 FILE
if ($_POST['form_get_hl7']==='true') {
  	$content = '';

  	$res = sqlStatement($query);

  	while ($r = sqlFetchArray($res)) {
    	// MSH
    	$content .= "MSH|^~\&|".strtoupper($libreehr_name).
        "|" . $facility_info['name'] . "^" . $facility_info['facility_npi'] . "^NPI" .
        "|||$now||".
        "ADT^A01^ADT_A01" . // Hard-code to A01: Patient visits provider/facility
        "|$nowdate|P^T|2.5.1|||||||||PH_SS-NoAck^SS Sender^2.16.840.1.114222.4.10.3^ISO" . // No acknowlegement
        "$D";

    // EVN
    $content .= "EVN|" .
        "|" . // 1.B Event Type Code
        "$now" . // 2.R Recorded Date/Time
        "||||" .
        "|" . $facility_info['name'] . "^" . $facility_info['facility_npi'] . "^NPI" .
        "$D" ;

    if ($r['sex']==='Male') $r['sex'] = 'M';
    if ($r['sex']==='Female') $r['sex'] = 'F';
    if ($r['status']==='married') $r['status'] = 'M';
    if ($r['status']==='single') $r['status'] = 'S';
    if ($r['status']==='divorced') $r['status'] = 'D';
    if ($r['status']==='widowed') $r['status'] = 'W';
    if ($r['status']==='separated') $r['status'] = 'A';
    if ($r['status']==='domestic partner') $r['status'] = 'P';

	    // PID
	    $content .= "PID|" .
	        "1|" . // 1. Set id
	        "|" .
	        $r['patientid']."^^^^MR"."|". // 3. (R) Patient indentifier list
	        "|" . // 4. (B) Alternate PID
	        "^^^^^^~^^^^^^S"."|" . // 5.R. Name
	        "|" . // 6. Mather Maiden Name
	        $r['DOB']."|" . // 7. Date, time of birth
	        $r['sex'] . // 8. Sex
	        "|||^^^||||||||||||||||||||||||||||" .
	        "$D" ;

    	$content .= "PV1|" .
	        "1|" . // 1. Set ID
	        "|||||||||||||||||" .
	        // Restrict the string to 15 characters. Will fail if longer.
	        substr($now . "_" . $r['patientid'], 0, 15) . "^^^^VN" . // Supposed to be visit number. Since, we don't have any encounter, we'll use the format 'date_pid' to make it unique
	        "|||||||||||||||||||||||||" .
	        $r['begin_date'] .
	        "$D" ;

    	// OBX: Records chief complaint in LOINC code
    	$content .= "OBX|" .
	        "1|" . // 1. Set ID
	        "CWE|8661-1^^LN||" . // LOINC code for chief complaint
	        "^^^^^^^^" . $r['issuetitle'] .
	        "||||||" .
	        "F" .
	        "$D" ;

    	// DG1
    	$r['diagnosis'] = mapCodeType($r['diagnosis']);  // Only ICD9, ICD10 and SNOMED
    	$r['code'] = str_replace(".", "", $r['code']); // strip periods code

    	$content .= "DG1|" .
	        "1|" . // 1. Set ID
	        "|" .
	        $r['code'] . "^" . $r['code_text'] . "^" . $r['diagnosis'] .
	        "|||W" .
	        "$D" ;

        // mark if issues generated/sent
        $query_insert = "insert into syndromic_surveillance(lists_id,submission_date,filename) " .
         "values (" . $r['issueid'] . ",'" . $now1 . "','" . $filename . "')";
        sqlStatement($query_insert);
	}

  	// Ensoftek: Jul-2015: No need to tr the content
  	//$content = tr($content);

  	// send the header here
  	header('Content-type: text/plain');
  	header('Content-Disposition: attachment; filename=' . $filename );

  	// put the content in the file
  	echo($content);
  	exit;
}

/* This prepares a list of codes for report
 * @params: None
 * @return: Echo HTML
 * @author: Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * */
function dropDownCodes() {
	$query1 = "select id, code as name, code_type from codes ".
   		" where reportable=1 ORDER BY name";
 	$cres = sqlStatement($query1);
 	echo "   <select multiple='multiple' size='3' name='form_code[]'>\n";
 	//echo "    <option value=''>-- " . xl('All Codes') . " --\n";
 	while ($crow = sqlFetchArray($cres)) {
		if (convert_type_id_to_key($crow['code_type']) == "ICD9") {
		   // This report currently only works for ICD9 codes. Need to make this work for other
		   // diagnosis code sets in the future.
		   $crow['name'] = convert_type_id_to_key($crow['code_type']) . ":" . $crow['name'];
		   $codeid = $crow['id'];
		   echo "    <option value='$codeid'";
		   if (in_array($codeid, $form_code)) echo " selected";
		   echo ">" . $crow['name'] . "\n";
	  	}
 	}
 	echo "   </select>\n";
}

?>
