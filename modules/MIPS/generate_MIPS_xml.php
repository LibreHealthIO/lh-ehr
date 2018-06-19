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

require_once ('mips_headers.inc.php');

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




function get_entityType() {
	$myGOT=$GLOBALS['pqrs_entityType']; // use for entityType individual or group
	//htmlecho("DEBUG:  entityType -- Got $myGOT \n");
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
htmlecho("Assumptions and instructions for generating this XML:  \n");
htmlecho(" * The eligible professional has signed a waiver giving the registry permission\n     to submit data on their behalf.  *REQUIRED*  \n");
htmlecho(" * This report must not include any \"pre_\" measures. \n");
htmlecho(" * You have gone into Administration > Facilities > Mark ONE facility as 'Primary\n     Business Entity'.  Be sure it has the correct TIN.   *REQUIRED*  \n");
htmlecho(" * Measures that must be reported on for EVERY Encounter will be manually   \n     dealt with in the XML.  \n");

htmlecho("\nThis tool has generated an XML file related to this report.\n");
htmlecho("The file naming convention is ProviderNPI-ProviderTIN.xml \n");
echo("<br>You may download these files by going to \"<b>QA Measures</b>\" --> \"<b>Upload Claim Files</b>\"\n and clicking on the \"<b>XML_out</b>\" folder, then the appropriate <b>.xml</b> file(s).\n");

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



	$entityType=get_entityType();
	htmlecho("entityType is: ".$entityType ." \n");


	$COLLECTION_METHOD="false";
	htmlecho("End-to-End reporting is: ".$COLLECTION_METHOD ." \n");

	$PROVIDER_NPI=$report_view['provider'];
	htmlecho("PROVIDER_NPI is: ".$PROVIDER_NPI ." \n");

	$PROVIDER_TIN=get_TIN();
	htmlecho("PROVIDER_TIN is: ".$PROVIDER_TIN ." \n");


//TODO:  Generate these in code instead of hard coding 2017
	$ENCOUNTER_FROM_DATE=$report_view['date_begin'];
	htmlecho("ENCOUNTER_FROM_DATE is: ".$ENCOUNTER_FROM_DATE ." \n");

	$ENCOUNTER_TO_DATE=$report_view['date_target'];
	htmlecho("ENCOUNTER_TO_DATE is: ".$ENCOUNTER_TO_DATE ." \n");


	$OUTFILE_PATH=$GLOBALS['OE_SITE_DIR']."/filemanager/files/XML_out";
	//htmlecho("DEBUG:  OUTFILE_PATH is $OUTFILE_PATH  \n");

	$OUTFILE_BASENAME=$PROVIDER_NPI."_".$PROVIDER_TIN;
	//htmlecho("DEBUG:  OUTFILE_BASENAME is ".$OUTFILE_BASENAME ." \n");
	htmlecho("XML filenames are formatted: ".$OUTFILE_BASENAME ."_#.xml\n");

	$ZIPFILE_NAME=$OUTFILE_BASENAME.".zip";
	echo("<b>ZIPFILE_NAME is: ".$ZIPFILE_NAME ."</b> \n");


	# This is the Total number of XML files to be generated
	$TOTAL_MEASURES=count($dataSheet);
	htmlecho(" Total number of Measures being reported on is: $TOTAL_MEASURES  \n");


	$FILE_NUMBER="0";
	
	////////////////////////////////////START XML output Headers///////////////////////////////////////////
			$OUTFILE_NAME="$OUTFILE_BASENAME"."_".$FILE_NUMBER.".xml";
		$myFileHandle=fopen($OUTFILE_PATH."/".$OUTFILE_NAME, "w") or die("Unable to open file!".$OUTFILE_PATH."/".$OUTFILE_NAME);

		htmlecho(" \nGenerating File number ".$FILE_NUMBER.": ".$OUTFILE_NAME." \n\n");

		fwrite($myFileHandle, "<?xml version=\"1.0\"?>\n");
		//begin <data>
		fwrite($myFileHandle, "<data>\n");
		//begin <submission>
		//fwrite($myFileHandle, " <submission>\n"); no submission element for upload file format.
		fwrite($myFileHandle, "    <entityType>".$entityType."</entityType>\n");
		fwrite($myFileHandle, "    <taxpayerIdentificationNumber>$PROVIDER_TIN</taxpayerIdentificationNumber>\n");
		if ($entityType == "individual"){
		fwrite($myFileHandle, "    <nationalProviderIdentifier>$PROVIDER_NPI</nationalProviderIdentifier>\n");                                    }
		fwrite($myFileHandle, "    <performanceYear>2018</performanceYear>\n");
		//begin <measurementSet>
		fwrite($myFileHandle, "    <measurementSet>\n");
		fwrite($myFileHandle, "         <submissionMethod>registry</submissionMethod>\n");
		fwrite($myFileHandle, "         <submitterType>organization</submitterType>\n");
		fwrite($myFileHandle, "         <performanceStart>".$ENCOUNTER_FROM_DATE."</performanceStart>\n");
		fwrite($myFileHandle, "         <performanceEnd>".$ENCOUNTER_TO_DATE."</performanceEnd>\n");
		fwrite($myFileHandle, "         <category>quality</category>\n");//todo grab measure group name for IA and other measures.
		/////////////////////////////////////END XML HEADERS///////////////////////////////////////////////////////
		
////////////////////////////////////// LOOP per measure (just one <measureSet>)!!!!!!!!!!!!!!!!!!!!!!!!!!!///////////////////////////////////////////////////////////////////////////////
	foreach($dataSheet as $row) {
        $FILE_NUMBER++;
// Set this AGAIN because we may change it when processing Inverse Measures
	    	$xmloptimize = $_GET['xmloptimize'];  

		htmlecho("\n-------------------------------------------------------------------------------- \n");
		htmlecho("For Measure ".$FILE_NUMBER.":   \n");

		$PQRS_MEASURE_NUMBER=substr($row['id'],-3);
		htmlecho(" MIPS Measure Number is: $PQRS_MEASURE_NUMBER  \n");
        if (in_array($PQRS_MEASURE_NUMBER,$BLACKLIST)){
          echo(" Optimization OFF for this <i><b>INVERSE MEASURE!</b></i>  \n");  
            $xmloptimize ='false';}


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

//REPORTING_RATE=`ask "Reporting rate? (i.e. 100.00)"` NOT USED


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

			$PERFORMANCE_RATE=sprintf("%00.2f", ($MEETS_PERFORMANCE_INSTANCES/($PERFORMANCE_DENOMINATOR - $PERFORMANCE_EXCLUSION_INSTANCES)) * 100);

		}
        	if ($xmloptimize=='true' ){
			htmlecho(" Optimized Failed is: $PERFORMANCE_NOT_MET_INSTANCES (calculated)  \n");
		}
		htmlecho(" Reporting Rate for this Measure is:  $REPORTING_RATE (calculated) \n");
		htmlecho(" Your Performance Rate is: $PERFORMANCE_RATE (calculated) \n");


# ==============================================================
#  output measures


        //begin <measurement>
		fwrite($myFileHandle,  "      <measurement>\n");

		fwrite($myFileHandle,  "        <measureId>$PQRS_MEASURE_NUMBER</measureId>\n");


		fwrite($myFileHandle,  "          <value>\n");
        fwrite($myFileHandle,  "             <reporting-rate>$REPORTING_RATE</reporting-rate>\n");
		fwrite($myFileHandle,  "             <performance-rate>$PERFORMANCE_RATE</performance-rate>\n");        
        ///<strata> foreach loops would begin here. TODO OLD CODE=fwrite($myFileHandle,  "          <measure-strata-num>$MEASURE_STRATA_NUM</measure-strata-num>\n");
		fwrite($myFileHandle,  "             <isEndToEndReported>false</isEndToEndReported>\n");
		fwrite($myFileHandle,  "             <performanceMet>$MEETS_PERFORMANCE_INSTANCES</performanceMet>\n");
		fwrite($myFileHandle,  "             <performanceNotMet>$PERFORMANCE_NOT_MET_INSTANCES</performanceNotMet>\n");
		fwrite($myFileHandle,  "             <eligiblePopulation>$ELIGIBLE_INSTANCES</eligiblePopulation>\n");
		fwrite($myFileHandle,  "             <eligiblePopulationExclusion>$PERFORMANCE_EXCLUSION_INSTANCES</eligiblePopulationExclusion>\n");
		//fwrite($myFileHandle,  "             <eligiblePopulationException>$DOES_NOTEXIST</eligiblePopulationException>\n");
        //<stratum>$somedamnvalue</stratum>
        //</strata> loops would end here
		fwrite($myFileHandle,  "          </value>\n");
		fwrite($myFileHandle,  "      </measurement>\n");
        //end <measurement>


	}	// End loop.  LOOP LOOP LOOP LOOP


		fwrite($myFileHandle,  "   </measurementSet>\n");
		//fwrite($myFileHandle,  " </submission>\n"); no submission element required.
		fwrite($myFileHandle,  "</data>\n");
		fclose($myFileHandle);



} else {	// End if(!empty($report_id))
	echo ("ERROR!  No report_id specified!\n");
}


?>
