<?php
/**
 * Clinical Measures Engine Report Form
 * Copyright (C) 2015 - 2017      Suncoast Connection
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @author  Bryan lee <leebc@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */
// SANITIZE ALL ESCAPES
$sanitize_all_escapes = true;

// STOP FAKE REGISTER GLOBALS
$fake_register_globals = false;

require_once 'mips_headers.inc.php';

$BLACKLIST = array('0001','0146','0164','0192','0238','0322','0323','0324','0328','0329','0330','0331','0333','0334',
'0345','0346','0347','0348','0388','0392','0393','0405','0406','0416','0432','0433','0434','0437','0439','0441');

function existsDefault(&$array, $key, $default = '') {
  if(array_key_exists($key, $array)) {
    $default = trim($array[$key]);
  }
  return $default;
}

function htmlecho( $myString ){
	echo ( htmlentities( $myString ) );
}

function find_MGID_by_measure_name ( $measure_name ) {
	// echo ("DEBUG find_group_type_by_measure_name: measure_name is ".$measure_name ."\n");
	if ( strpos( $measure_name, "PQRS_Group_") === false) {
		htmlecho (" MGID Not Applicable (Individual Measures) is: X\n");
		return "X";
	}
}  // end find_MGID_by_measure_name	


function get_Provider_email ($user_id) {
	$query="SELECT email FROM `users` WHERE id=$user_id ; ";
	$result=sqlFetchArray(sqlStatement($query));
	$EMAIL=$result['email'];
	//echo ("DEBUG: |$query| result |$result| ;  email is $EMAIL\n");
	return $EMAIL;
}


// Returns the last character in the population_label if it is a digit
// Returns 1 otherwise
function get_Measure_Strata($population_label) {
	$lastcharacter=substr($population_label,20,1);
	if( $lastcharacter == TRUE ) {
		if(ctype_digit($lastcharacter)) {
			return $lastcharacter;
		} 
	}
	// else
	return 1;
}


function get_Medicare_Patient_Count($report_id) {
$query = "SELECT count(DISTINCT ri.pid) as count ".
" FROM report_itemized ri ".
" INNER JOIN insurance_data i on (i.pid=ri.pid) ".
" INNER JOIN insurance_companies c on (c.id = i.provider) ".
" WHERE c.freeb_type = 2 ".
" AND ri.report_id =".$report_id;
#TODO:  Validate this query
	$result=sqlFetchArray(sqlStatement($query))['count'];
	//$result=sqlStatement($query);
	//htmlecho(" DEBUG MFFS:  ".$result." \n");
	return $result;	
}


// Number of instances of reporting for all applicable measures within the measure group, for each eligible instance (reporting numerator)
function get_Reporting_Rate_Numerator($dataSheet) {
	// Add all the pass/fail/exclusions counts together for all measures
///  MATH MATH MATH.  I'm calculating the fail as denominator-pass-exclusions, so the sum of these 3 is always goign to return the denominator.
// SO Let's just return the denominator sums.	#LIES return get_Group_Eligable_Instances($dataSheet);
	return get_Group_Eligable_Instances($dataSheet);
}


// What is Eligible instances for the PQRS Measure Group?(reporting denominator)
function get_Group_Eligable_Instances($dataSheet) {
	// Add all the denominators together for all measures.
	$EligibleInstances=0;
	foreach($dataSheet as $row) {
		if ( $row['pass_filter'] > $EligibleInstances ) {
			$EligibleInstances=$row['pass_filter'];
		}
	//	htmlecho(" DEBUG gGEI countup is +".$row['pass_filter']." = $EligibleInstances  \n");
	}
	return $EligibleInstances;
}


function get_Creator() {
	$myGOT=$GLOBALS['pqrs_creator'];
	//htmlecho("DEBUG:  Creator -- Got $myGOT \n");
	return $myGOT;
}

function get_Registry_Name() {
	$myGOT=$GLOBALS['pqrs_registry_name'];
	//htmlecho("DEBUG:  Registry_Name -- Got $myGOT \n");
	return $myGOT;
}

function get_Registry_ID() {
	$myGOT=$GLOBALS['pqrs_registry_id']; 	// tax payer number  EIN	
	//htmlecho("DEBUG:  Registry_ID -- Got $myGOT \n");
	return $myGOT;
}

function get_Registry_VENDOR_UNIQUE_ID() {
	$myGOT=$GLOBALS['pqrs_vendor_unique_id'];
	//htmlecho("DEBUG:  VENDOR_UNIQUE_ID -- Got $myGOT \n");
	return $myGOT;
}


function get_TIN() {
	// Lookup the primary_business_entity in the facility table
	$query="SELECT federal_ein FROM `facility` WHERE primary_business_entity=1; ";
	$result=sqlFetchArray(sqlStatement($query))['federal_ein'];
	//htmlecho("DEBUG get_TIN result is ".$result." \n");
	if ( ! empty($result) ) {
		return $result;
	} else {
		htmlecho("ERROR:  No facility is set as the Primary Business Entity.\n   You MUST set one with the correct TIN. \n");
		return "TINTINTINTINTINTINTIN";
	}
}

###	==========	BEGIN MAIN	==========

echo ("<pre>\n");
htmlecho("Assumptions -- Things that should have been done before generating this XML:  \n");
htmlecho(" * XML should only be generated for a report with a single provider.  *REQUIRED*  \n");
htmlecho(" * The eligible professional has signed a waiver giving the registry permission\n     to submit data on their behalf.  *REQUIRED*  \n");
htmlecho(" * \"Failed Patients\" is assumed to be = Denominator - Numerator - Exclusions. *REQUIRED*  \n");
htmlecho(" * 9 Measures were chosen for an Individual Measures report.  *REQUIRED*  \n");
htmlecho(" * This report does not include any \"pre_\" measures. \n");
htmlecho(" * Go into Administration --- Facilities --- Mark ONE facility as 'Primary\n     Business Entity'.  Be sure it has the correct TIN.   *REQUIRED*  \n");
htmlecho(" * If you want to receive PQRS email notifications from CMS for this provider,\n     Go into Administration --- 'Addr Book' --- Add an email address *Optional*  \n");
htmlecho(" * You are not reporting on GPROs.  \n");
htmlecho(" * You are not reporting on on Risk Adjusted Measures.  \n");
htmlecho(" * Measures that must be reported on for EVERY Encounter will be manually   \n     dealt with in the XML.  \n");

htmlecho("\nThis feature has generated XML files related to this report.\n");
htmlecho("The naming convention is ProviderNPI-ProviderTIN.xml \n");
echo("<br>You can download these files by going to \"<b>Left Nav</b>\" --> \"<b>Upload Claim Files</b>\"\n and clicking on the \"<b>XML_out</b>\" directory, then the appropriate <b>.zip</b> file.\n");

htmlecho("\n================================================================================ \n");



$report_id = $_GET['report_id'] ;
//$report_id = (isset($_REQUEST['report_id'])) ;
htmlecho("Report_ID: $report_id  \n");
$xmloptimize = $_GET['xmloptimize'];
htmlecho ("Run with reporting-rate optimization: ".$xmloptimize."\n");

if(!empty($report_id)) {
	$report_view = collectReportDatabase($report_id);
	//echo ("DEBUG report_view is:  ".implode($report_view)."\n" );
	$dataSheet = json_decode($report_view['data'], true);
	//echo ("DEBUG dataSheet is:  ".implode($dataSheet)."\n" );
	htmlecho("The first measure is: ".$dataSheet[0]['id']   ." \n");

//TODO:  Sanity check that all measures are either individual or of the same group


	$CREATE_DATE=date("m-d-Y");	//In the form:  01-23-2016
//	htmlecho("CREATE_DATE is ".$CREATE_DATE ." \n");

	$CREATE_TIME=date("H:i");	//In the form:  23:01
//	htmlecho("CREATE_TIME is ".$CREATE_TIME ." \n");

	htmlecho("DATE/TIME CREATED is: ".$CREATE_DATE ." ". $CREATE_TIME ." \n");

	$CREATOR=get_Creator();
	htmlecho("CREATOR is: ".$CREATOR ." \n");

	$VERSION="1.0";		// "The version of the file being submitted"
	htmlecho("VERSION is: ".$VERSION ." \n");

	$REGISTRY_NAME=get_Registry_Name();
	htmlecho("REGISTRY_NAME is: ".$REGISTRY_NAME ." \n");

	$REGISTRY_ID=get_Registry_ID();
	htmlecho("REGISTRY_ID is: ".$REGISTRY_ID ." \n");

	$VENDOR_UNIQUE_ID=get_Registry_VENDOR_UNIQUE_ID();
	htmlecho("VENDOR_UNIQUE_ID is: ".$VENDOR_UNIQUE_ID ." \n");

	$MEASURE_GROUP_ID=find_MGID_by_measure_name($dataSheet[0]['id']);
	//htmlecho("The MGID is ".$MEASURE_GROUP_ID." \n");

	//  1=Individual Registry Submission 2=GPRO Registry Submi
	$SUBMISSION_TYPE="1";	
	htmlecho("SUBMISSION_TYPE is: ".$SUBMISSION_TYPE ." \n");

	if ($MEASURE_GROUP_ID=="X") {
		$SUBMISSION_METHOD="A";	// IndividuAl
	} else {
		$SUBMISSION_METHOD="G";	// Group no longer used.  Preserve for other use.
	}
	htmlecho("SUBMISSION_METHOD is: ".$SUBMISSION_METHOD ." \n");

	// (A=EHR, B=Claims, C=Practice Mgmt System, D=Web Tool)";
	$COLLECTION_METHOD="BCD";
	htmlecho("COLLECTION_METHOD is: ".$COLLECTION_METHOD ." \n");

	$PROVIDER_NPI=$report_view['provider'];
	htmlecho("PROVIDER_NPI is: ".$PROVIDER_NPI ." \n");

	$PROVIDER_TIN=get_TIN();
	htmlecho("PROVIDER_TIN is: ".$PROVIDER_TIN ." \n");

	$PROVIDER_EMAIL=get_Provider_email($PROVIDER_NPI);
	htmlecho("PROVIDER_EMAIL is: ".$PROVIDER_EMAIL ." \n");

	// We are assuming and require that the waiver have been signed
	$WAIVER_SIGNED="Y";
	htmlecho("WAIVER_SIGNED is: (we're always assuming) ".$WAIVER_SIGNED ." \n");

//TODO:  Generate these in code instead of hard coding 2016
	$ENCOUNTER_FROM_DATE="01-01-2016";
	htmlecho("ENCOUNTER_FROM_DATE is: ".$ENCOUNTER_FROM_DATE ." \n");

	$ENCOUNTER_TO_DATE="12-31-2016";
	htmlecho("ENCOUNTER_TO_DATE is: ".$ENCOUNTER_TO_DATE ." \n");


	$OUTFILE_PATH=$GLOBALS['OE_SITE_DIR']."/PQRS/dropzone/files/XML_out/";
	//htmlecho("DEBUG:  OUTFILE_PATH is $OUTFILE_PATH  \n");

	$OUTFILE_BASENAME=$PROVIDER_NPI."_".$PROVIDER_TIN;
	//htmlecho("DEBUG:  OUTFILE_BASENAME is ".$OUTFILE_BASENAME ." \n");
	htmlecho("XML filenames are formatted: ".$OUTFILE_BASENAME ."_#.xml\n");

	$ZIPFILE_NAME=$OUTFILE_BASENAME.".zip";
	echo("<b>ZIPFILE_NAME is: ".$ZIPFILE_NAME ."</b> \n");


	# This is the Total number of XML files to be generated
	$TOTAL_MEASURES=count($dataSheet);
	htmlecho(" Total number of Measures being reported on is: $TOTAL_MEASURES  \n");

# LOOP!!!!!!!!!!!!!!!!!!!!!!!!!!!
	$FILE_NUMBER="0";

	foreach($dataSheet as $row) {

// Set this AGAIN because we may change it when processing Inverse Measures
	    	$xmloptimize = $_GET['xmloptimize'];  

		$FILE_NUMBER++;
		//echo ("DEBUG row -- ".implode("|", $row) ."\n");
		htmlecho("\n-------------------------------------------------------------------------------- \n");
		htmlecho("For Measure ".$FILE_NUMBER.":   \n");

		$PQRS_MEASURE_NUMBER=ltrim(substr($row['id'],strlen($row['id'])-4 ),'0');
		htmlecho(" PQRS Measure Number is: $PQRS_MEASURE_NUMBER  \n");
        if (in_array($PQRS_MEASURE_NUMBER,$BLACKLIST)){
          echo(" Optimization OFF for this <i><b>INVERSE MEASURE!</b></i>  \n");  
            $xmloptimize ='false';}
	# Technically, the $COLLECTION_METHOD can be different for each measure

// "What is Measure Strata Number?)"`
		$MEASURE_STRATA_NUM=get_Measure_Strata($row['population_label']);
		htmlecho(" Measure Strata Number is: $MEASURE_STRATA_NUM \n");

// "How many eligible instances (Reporting Denominator) for the PQRS measure?
		$ELIGIBLE_INSTANCES=$row['pass_filter'];
		htmlecho(" Eligible Instances (Reporting Denominator) is: $ELIGIBLE_INSTANCES  \n");

// "How many Meets Performance Instances? (Performance Numerator)
		$MEETS_PERFORMANCE_INSTANCES=$row['pass_target'];
		htmlecho(" Numerator is: $MEETS_PERFORMANCE_INSTANCES  \n");

// "How many Exclusions?
		$PERFORMANCE_EXCLUSION_INSTANCES=$row['excluded'];
		htmlecho(" Exclusions is: $PERFORMANCE_EXCLUSION_INSTANCES  \n");

// "How many Performance Not Met Instances?
		$PERFORMANCE_NOT_MET_INSTANCES=$ELIGIBLE_INSTANCES-$MEETS_PERFORMANCE_INSTANCES-$PERFORMANCE_EXCLUSION_INSTANCES;
		htmlecho(" Failed (including Not Reported) is: $PERFORMANCE_NOT_MET_INSTANCES (calculated)  \n");

		#REPORTING_RATE=`ask "Reporting rate? (i.e. 100.00)"`
// If MEASURE_GROUP_ID is X, technically the <reporting-rate> tag is optional, but we're going to include it because CMS is dumb and throws error on validation.
		//if ( $MEASURE_GROUP_ID == "X" ){
		if ($ELIGIBLE_INSTANCES == 0) {
			$REPORTING_RATE="null";
			echo("<b>Notice:  reporting-rate is null.  You may need to inform CMS.</b>\n");
		} else if ($xmloptimize=='false'){
			$REPORTING_RATE=sprintf ( "%00.2f", (($MEETS_PERFORMANCE_INSTANCES+$PERFORMANCE_EXCLUSION_INSTANCES+$PERFORMANCE_NOT_MET_INSTANCES)/$ELIGIBLE_INSTANCES ) * 100);
#<meets-performance-instances>+<performance-exclusion-instances>+<performance-not-met-instances>/<eligible-instances>
		} else if ($xmloptimize=='true'){
    		        if (((($MEETS_PERFORMANCE_INSTANCES+$PERFORMANCE_EXCLUSION_INSTANCES)/$ELIGIBLE_INSTANCES ) * 100)>=50){
			        $REPORTING_RATE=sprintf ( "%00.2f", (($MEETS_PERFORMANCE_INSTANCES+$PERFORMANCE_EXCLUSION_INSTANCES)/$ELIGIBLE_INSTANCES ) * 100);
			        $CALC_RATES=FALSE;
		        }else{
    		        $REPORTING_RATE="50.00"; $CALC_RATES=TRUE;}
#<meets-performance-instances>+<performance-exclusion-instances>+<performance-not-met-instances>/<eligible-instances>
		}
		$ALL_INSTANCES = $MEETS_PERFORMANCE_INSTANCES+$PERFORMANCE_EXCLUSION_INSTANCES+$PERFORMANCE_NOT_MET_INSTANCES;

        if ($xmloptimize=='true' && $CALC_RATES==FALSE){
		$PERFORMANCE_DENOMINATOR=$MEETS_PERFORMANCE_INSTANCES+$PERFORMANCE_EXCLUSION_INSTANCES;
		$PERFORMANCE_NOT_MET_INSTANCES = 0;
	    }else if ($xmloptimize=='true' && $CALC_RATES==TRUE){
    	$PERFORMANCE_DENOMINATOR=round($ALL_INSTANCES*.5);
    	$PERFORMANCE_NOT_MET_INSTANCES = $PERFORMANCE_DENOMINATOR-($MEETS_PERFORMANCE_INSTANCES+$PERFORMANCE_EXCLUSION_INSTANCES);
	    }else{
    	$PERFORMANCE_DENOMINATOR=$ALL_INSTANCES;    
         }
		if ($PERFORMANCE_DENOMINATOR == 0 ) {
			$PERFORMANCE_RATE = "null";
			echo("<b>Notice:  performance-rate is null.  You may need to inform CMS.</b>\n");
		} else {
		#PERFORMANCE_RATE=`ask "What is Performance Rate? (i.e. 100.00)"
			$PERFORMANCE_RATE=sprintf("%00.2f", ($MEETS_PERFORMANCE_INSTANCES/($PERFORMANCE_DENOMINATOR - $PERFORMANCE_EXCLUSION_INSTANCES)) * 100);
#<meets-performance-instances> / [(<meets-performance-instances>+<performance-exclusion-instances>+<performance-not-met-instances>) - <performance-exclusion-instances>]
		}
        	if ($xmloptimize=='true' ){
			htmlecho(" Optimized Failed is: $PERFORMANCE_NOT_MET_INSTANCES (calculated)  \n");
		}
		htmlecho(" Reporting Rate for this Measure is:  $REPORTING_RATE (calculated) \n");
		htmlecho(" Your Performance Rate is: $PERFORMANCE_RATE (calculated) \n");


# ==============================================================
#  Generate XML
		$OUTFILE_NAME="$OUTFILE_BASENAME"."_".$FILE_NUMBER.".xml";
		$myFileHandle=fopen($OUTFILE_PATH."/".$OUTFILE_NAME, "w") or die("Unable to open file!");

		htmlecho(" \nGenerating File number ".$FILE_NUMBER.": ".$OUTFILE_NAME." \n\n");
/*		htmlecho("<?xml version=\"1.0\" encoding=\"utf-8\"?> \n"); */
		fwrite($myFileHandle, "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
//		htmlecho("<submission type=\"PQRS-REGISTRY\" version=\"8.0\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"Registry_Payment.xsd\"> \n");
		fwrite($myFileHandle, "<submission type=\"PQRS-REGISTRY\" version=\"8.0\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"Registry_Payment.xsd\">\n");
//		htmlecho("  <file-audit-data> \n");
		fwrite($myFileHandle,  "  <file-audit-data>\n");
//		htmlecho("    <create-date>".$CREATE_DATE."</create-date> \n");
		fwrite($myFileHandle,  "    <create-date>".$CREATE_DATE."</create-date>\n");
//		htmlecho("    <create-time>".$CREATE_TIME."</create-time> \n");
		fwrite($myFileHandle,  "    <create-time>".$CREATE_TIME."</create-time>\n");
//		htmlecho("    <create-by>".$CREATOR."</create-by> \n");
		fwrite($myFileHandle,  "    <create-by>".$CREATOR."</create-by>\n");
//		htmlecho("    <version>".$VERSION."</version> \n");
		fwrite($myFileHandle,  "    <version>".$VERSION."</version>\n");
//		htmlecho("    <file-number>".$FILE_NUMBER."</file-number> \n");
		fwrite($myFileHandle,  "    <file-number>".$FILE_NUMBER."</file-number>\n");
//		htmlecho("    <number-of-files>".$TOTAL_MEASURES."</number-of-files> \n");
		fwrite($myFileHandle,  "    <number-of-files>".$TOTAL_MEASURES."</number-of-files>\n");
//		htmlecho("  </file-audit-data> \n");
		fwrite($myFileHandle,  "  </file-audit-data>\n");
//		htmlecho("  <registry> \n");
		fwrite($myFileHandle,  "  <registry>\n");
//		htmlecho("    <registry-name>".$REGISTRY_NAME."</registry-name> \n");
		fwrite($myFileHandle,  "    <registry-name>".$REGISTRY_NAME."</registry-name>\n");
//		htmlecho("    <registry-id>".$REGISTRY_ID."</registry-id> \n");
		fwrite($myFileHandle,  "    <registry-id>".$REGISTRY_ID."</registry-id>\n");
//		htmlecho("    <vendor-unique-id>".$VENDOR_UNIQUE_ID."</vendor-unique-id> \n");
		fwrite($myFileHandle,  "    <vendor-unique-id>".$VENDOR_UNIQUE_ID."</vendor-unique-id>\n");
//		htmlecho("    <submission-type>".$SUBMISSION_TYPE."</submission-type> \n");
		fwrite($myFileHandle,  "    <submission-type>".$SUBMISSION_TYPE."</submission-type>\n");
//		htmlecho("    <submission-method>".$SUBMISSION_METHOD."</submission-method> \n");
		fwrite($myFileHandle,  "    <submission-method>".$SUBMISSION_METHOD."</submission-method>\n");
//		htmlecho("  </registry> \n");
		fwrite($myFileHandle,  "  </registry>\n");
//		htmlecho("  <measure-group ID=\"".$MEASURE_GROUP_ID."\" > \n");
		fwrite($myFileHandle,  "  <measure-group ID=\"".$MEASURE_GROUP_ID."\" >\n");
//		htmlecho("    <provider> \n");
		fwrite($myFileHandle,  "    <provider>\n");
//		htmlecho('      <gpro-type xsi:nil="true"></gpro-type>'." \n");
		fwrite($myFileHandle,  '      <gpro-type xsi:nil="true"></gpro-type>'."\n");
//		htmlecho("      <npi>$PROVIDER_NPI</npi> \n");
		fwrite($myFileHandle,  "      <npi>$PROVIDER_NPI</npi>\n");
//		htmlecho("      <tin>$PROVIDER_TIN</tin> \n");
		fwrite($myFileHandle,  "      <tin>$PROVIDER_TIN</tin>\n");
	
		if ( empty($PROVIDER_EMAIL) ) {
			//echo ("DEBUG  PROVIDER_EMAIL is empty! \n");
//			htmlecho("      <email-address xsi:nil=\"true\"/> \n");
			fwrite($myFileHandle,  "      <email-address xsi:nil=\"true\"/>\n");
		} else {
//			htmlecho("      <email-address>$PROVIDER_EMAIL</email-address> \n");
			fwrite($myFileHandle,  "      <email-address>$PROVIDER_EMAIL</email-address>\n");
		}

//		htmlecho("      <waiver-signed>$WAIVER_SIGNED</waiver-signed> \n");
		fwrite($myFileHandle,  "      <waiver-signed>$WAIVER_SIGNED</waiver-signed>\n");
//		htmlecho("      <encounter-from-date>$ENCOUNTER_FROM_DATE</encounter-from-date> \n");
		fwrite($myFileHandle,  "      <encounter-from-date>$ENCOUNTER_FROM_DATE</encounter-from-date>\n");
//		htmlecho("      <encounter-to-date>$ENCOUNTER_TO_DATE</encounter-to-date> \n");
		fwrite($myFileHandle,  "      <encounter-to-date>$ENCOUNTER_TO_DATE</encounter-to-date>\n");


//		htmlecho("      <pqrs-measure> \n");
		fwrite($myFileHandle,  "      <pqrs-measure>\n");
//		htmlecho("        <pqrs-measure-number>$PQRS_MEASURE_NUMBER</pqrs-measure-number> \n");
		fwrite($myFileHandle,  "        <pqrs-measure-number>$PQRS_MEASURE_NUMBER</pqrs-measure-number>\n");
//		htmlecho("        <collection-method>$COLLECTION_METHOD</collection-method> \n");
		fwrite($myFileHandle,  "        <collection-method>$COLLECTION_METHOD</collection-method>\n");
//		htmlecho("        <pqrs-measure-details> \n");
		fwrite($myFileHandle,  "        <pqrs-measure-details>\n");
//		htmlecho("          <measure-strata-num>$MEASURE_STRATA_NUM</measure-strata-num> \n");
		fwrite($myFileHandle,  "          <measure-strata-num>$MEASURE_STRATA_NUM</measure-strata-num>\n");
//		htmlecho("          <eligible-instances>$ELIGIBLE_INSTANCES</eligible-instances> \n");
		fwrite($myFileHandle,  "          <eligible-instances>$ELIGIBLE_INSTANCES</eligible-instances>\n");
//		htmlecho("          <meets-performance-instances>$MEETS_PERFORMANCE_INSTANCES</meets-performance-instances> \n");
		fwrite($myFileHandle,  "          <meets-performance-instances>$MEETS_PERFORMANCE_INSTANCES</meets-performance-instances>\n");
//		htmlecho("          <performance-exclusion-instances>$PERFORMANCE_EXCLUSION_INSTANCES</performance-exclusion-instances> \n");
		fwrite($myFileHandle,  "          <performance-exclusion-instances>$PERFORMANCE_EXCLUSION_INSTANCES</performance-exclusion-instances>\n");
//		htmlecho("          <performance-not-met-instances>$PERFORMANCE_NOT_MET_INSTANCES</performance-not-met-instances> \n");
		fwrite($myFileHandle,  "          <performance-not-met-instances>$PERFORMANCE_NOT_MET_INSTANCES</performance-not-met-instances>\n");



		if ($REPORTING_RATE == "null") {
//			htmlecho("           <reporting-rate xsi:nil=”true”/> \n");
			fwrite($myFileHandle,  "           <reporting-rate xsi:nil=”true”/>\n");
		} else {
//			htmlecho("          <reporting-rate>$REPORTING_RATE</reporting-rate> \n");
			fwrite($myFileHandle,  "          <reporting-rate>$REPORTING_RATE</reporting-rate>\n");
		}


		if ( $PERFORMANCE_RATE == "null" ) {
//			htmlecho("          <performance-rate xsi:nil=”true”/>\n");
			fwrite($myFileHandle,  "          <performance-rate xsi:nil=”true”/>\n");
		} else {
//			htmlecho("          <performance-rate>$PERFORMANCE_RATE</performance-rate> \n");
			fwrite($myFileHandle,  "          <performance-rate>$PERFORMANCE_RATE</performance-rate>\n");
		}

// We are not doing RISK-ADJUSTED-MEASURES
// <risk-adjusted-measure-detail>
 // <population-ref-rate>8.3000</population-ref-rate>	// Note: When the population-ref-rate is null use <population-ref-rate xsi:nil="true"/> for this tag.`
 // <risk-standardized-rate>7.0000</risk-standardized-rate>	// Note: When the risk-standardized-rate is null use < risk-standardized-rate  xsi:nil="true"/> for this tag.
 // <lower-ci>6.9213</lower-ci>	// Note: When the lower-ci is null use <lower-ci xsi:nil="true"/> for this tag.
 // <upper-ci>10.3910</upper-ci>	// Note: When the upper-ci is null use <upper-ci xsi:nil="true"/> for this tag.
 // <performance-assessment>Average</performance-assessment>	// Note: When the performance-assessment is null use <performance-assessment xsi:nil="true"/> for this tag.
 // <risk-adjustment-description>Remove patients with X</risk-adjustment-description>	// 300 characters	// Note: When the risk-adjustment-description is null use <risk-adjustment-description xsi:nil="true"/> for this tag.
 // <risk-reporting-rate>95.0000</risk-reporting-rate>	// Note: When the risk-reporting-rate is null use <risk-reporting-rate xsi:nil="true"/> for this tag.
// </risk-adjusted-measure-detail>
//		htmlecho("        </pqrs-measure-details> \n");
		fwrite($myFileHandle,  "        </pqrs-measure-details>\n");
//		htmlecho("      </pqrs-measure> \n");
		fwrite($myFileHandle,  "      </pqrs-measure>\n");
//		htmlecho("    </provider> \n");
		fwrite($myFileHandle,  "    </provider>\n");
//		htmlecho("  </measure-group> \n");
		fwrite($myFileHandle,  "  </measure-group>\n");
//		htmlecho("</submission> \n");
		fwrite($myFileHandle,  "</submission>\n");
		fclose($myFileHandle);


	}	// End loop.  LOOP LOOP LOOP LOOP
} else {	// End if(!empty($report_id))
	echo ("ERROR!  No report_id specified!\n");
}
htmlecho("-------------------------------------------------------------------------------- \n");
echo("\nZipping XML files...  <b>$ZIPFILE_NAME</b>\n");
$output = shell_exec(" rm $OUTFILE_PATH/$ZIPFILE_NAME ");
echo "<pre>$output</pre>";
$output = shell_exec(" zip --junk-paths --move  $OUTFILE_PATH/$ZIPFILE_NAME $OUTFILE_PATH/$OUTFILE_BASENAME*.xml ");
echo "<pre>$output</pre>";
?>
