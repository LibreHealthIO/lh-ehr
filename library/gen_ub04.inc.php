<?php
/** 
* library/gen_ub04.inc.php Genetating a UB04 print file. 
* 
* Program for Generating a printed paper
* Copy of the UB-04 (CMS 1450)
* 
* Copyright (C) 2014-2017 Terry Hill <teryhill@librehealth.io> 
* 
* LICENSE: This Source Code is subject to the terms of the Mozilla Public License, v. 2.0.
* See the Mozilla Public License for more details.
* If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*
* @package LibreHealth EHR
* @author Terry Hill <teryhill@librehealth.io>
* @link http://librehealth.io
*
* Please help the overall project by sending changes you make to the authors and to the LibreHealth EHR community.
*
*/

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("Claim.class.php");


$ub04_curr_line = 1;
$ub04_curr_col = 1;
$ub04_data = '';
$ub04_proc_index = 0;

function print_r2($val){
        echo '<pre>';
        print_r($val);
        echo  '</pre>';
}

//debug == 1;
/**
 * take the data element and place it at the correct coordinates on the page
 * 
 * @global int $ub04_curr_line
 * @global type $ub04_curr_col
 * @global type $ub04_data
 * @param type $line
 * @param type $col
 * @param type $maxlen
 * @param type $data
 * @param type $strip   regular expression for what to strip from the data. period and has are the defaults
 *                     
 */

 
function put_ub04($line, $col, $maxlen, $data, $strip = '/[.#]/') {
    global $ub04_curr_line, $ub04_curr_col, $ub04_data;
    if ($line < $ub04_curr_line)
        die("Data item at ($line, $col) precedes current line.");
    while ($ub04_curr_line < $line) {
        $ub04_data .= "\n";
        ++$ub04_curr_line;
        $ub04_curr_col = 1;
    }
    if ($col < $ub04_curr_col)
        die("Data item at ($line, $col) precedes current column.");
    while ($ub04_curr_col < $col) {
        $ub04_data .= " ";
        ++$ub04_curr_col;
    }
    $data = preg_replace($strip, '', strtoupper($data));
    $len = min(strlen($data), $maxlen);
    $ub04_data .= substr($data, 0, $len);
    $ub04_curr_col += $len;
}

function gen_ub04($pid, $encounter, &$log) {
    global $ub04_data, $ub04_proc_index;

    $ub04_data = '';
    $ub04_proc_index = 0;

    $today = time();
    $claim = new Claim($pid, $encounter);

    $log .= "Generating ub04 claim $pid-$encounter for " .
            $claim->patientFirstName() . ' ' .
            $claim->patientMiddleName() . ' ' .
            $claim->patientLastName() . ' on ' .
            date('Y-m-d H:i', $today) . ".\n";

    while ($ub04_proc_index < $claim->procCount()) {
        if ($ub04_proc_index)
            $ub04_data .= "\014"; // append form feed for new page
        gen_ub04_page($pid, $encounter, $log, $claim);
    }

    $log .= "\n";
    return $ub04_data;
}



function gen_ub04_page($pid, $encounter, &$log, &$claim) {
    global $ub04_curr_line, $ub04_curr_col, $ub04_data, $ub04_proc_index;
		
        $ub04_curr_line = 0;
        $ub04_curr_col = 0;

        // Box 1. Service Facility Location Information: Name
            put_ub04(0, 1, 25, $claim->facilityName());

        // Box 3A. Patient's Account No.
        // Used Patient Encounter Number
            put_ub04(0, 72, 15, "$encounter");

        // Box 1. Continued. Service Facility Location Information: Street
            put_ub04(1, 2, 25, $claim->facilityStreet());

        // Box 3B. Patient's Medical Records No.
        // Used patient account number.
            put_ub04(1, 73, 15, "$pid");

	    // Box 4. Type of Bill
        // In the format of a leading Zero followed by "Submitting Facility" plus "Bill Classification" and "Frequency"
		
		IF (substr($claim->ub04_options[type_of_bill],0,1) == '0' AND strlen($claim->ub04_options[type_of_bill]) == 4) {   
			 put_ub04(1, 104, 4, ($claim->ub04_options[type_of_bill]));
		}
        
        // Box 1. Continued. Service Facility Location Information: City State Zip
        $tmp = $claim->facilityCity() ? ($claim->facilityCity() . ' ') : '';
            put_ub04(2, 2, 27, $tmp . $claim->facilityState() . ' ' .
            $claim->facilityZip());

        // Box 1. Continued. Billing Provider: Phone Number 
        $tmp = $claim->billingContactPhone();
            put_ub04(3, 1, 3, substr($tmp, 0, 3));
            put_ub04(3, 5, 7, substr($tmp, 3));


        // Box 5. Federal Tax ID Number
            put_ub04(3, 69, 10, $claim->billingFacilityETIN());

        // Box 6. Statment Covers Period Hospitalization Dates Related to Current Services
	    $tmp = $claim->ub04_options[hospitalization_date_from];
            put_ub04(3, 81, 2, substr($tmp,5,2));
            put_ub04(3, 83, 2, substr($tmp,8,2));
            put_ub04(3, 85, 2, substr($tmp,2,2));
        $tmp = $claim->ub04_options[hospitalization_date_to];
            put_ub04(3, 91, 2, substr($tmp,5,2));
            put_ub04(3, 93, 2, substr($tmp,8,2));
            put_ub04(3, 95, 2, substr($tmp,2,2));
     
	
        // Box 9A. Patient's Address
            put_ub04(4, 56, 28, $claim->patientStreet());

        // Box 8b. Patient's Name
		if ($claim->patientMiddleName()) {
           $tmp = $claim->patientLastName() . ', ' . $claim->patientFirstName() . ' ' . substr($claim->patientMiddleName(),0,1);
        }
        else
        { 
           $tmp = $claim->patientLastName() . ', ' . $claim->patientFirstName();
        }
            put_ub04(5, 2, 28, $tmp);

        // Box 9. continued. 9b 9C 9D Patient's City and State
            put_ub04(5, 43, 20, $claim->patientCity());
            put_ub04(5, 87, 2,  $claim->patientState());

        // Box 9. continued. Patient's Zip Code and Telephone
            put_ub04(5, 92, 10, $claim->patientZip());


        // Box 10. Patient's Birth Date 
        $tmp = $claim->patientDOB();
            put_ub04(7, 1, 2, substr($tmp, 4, 2));
            put_ub04(7, 3, 2, substr($tmp, 6, 2));
            put_ub04(7, 5, 4, substr($tmp, 0, 4));
   
	    // Box 11. Patient's Sex
            put_ub04(7, 14, 1, $claim->patientSex());

        // Box 12. Date of Admission
        $tmp = $claim->ub04_options[hospitalization_date_from];
		    put_ub04(7, 17, 2, substr($tmp, 5, 2));
            put_ub04(7, 19, 2, substr($tmp, 8, 2));
            put_ub04(7, 21, 4, substr($tmp, 0, 4));

	    // Box 13. Admission Hour
		    put_ub04(7, 26, 2, ($claim->ub04_options[admission_hour]));
 	
	    // Box 14. Type of Admission
			put_ub04(7, 29, 1, ($claim->ub04_options[admit_type]));
			
	    // Box 15. Source of Admission
		    put_ub04(7, 34, 1, ($claim->ub04_options[admit_source]));
   	
	    // Box 16. Discharge Hour
		    put_ub04(7, 37, 2, ($claim->ub04_options[discharge_hour]));
  	
	    // Box 17. Discharge Status
			put_ub04(7, 42, 2, ($claim->ub04_options[discharge_status]));
		
	    // Box 18. Condition Codes
		    put_ub04(7, 46, 2, ($claim->ub04_options[condition_code_1]));
	
	    // Box 19. Condition Codes
		    put_ub04(7, 50, 2, ($claim->ub04_options[condition_code_2]));
	
	    // Box 20. Condition Codes
		    put_ub04(7, 54, 2, ($claim->ub04_options[condition_code_3]));
	
	    // Box 21. Condition Codes
		    put_ub04(7, 58, 2, ($claim->ub04_options[condition_code_4]));
	
	    // Box 22. Condition Codes
		    put_ub04(7, 62, 2, ($claim->ub04_options[condition_code_5]));
	
	    // Box 23. Condition Codes
		    put_ub04(7, 66, 2, ($claim->ub04_options[condition_code_6]));
	
	    // Box 24. Condition Codes
		    put_ub04(7, 70, 2, ($claim->ub04_options[condition_code_7]));
	
	    // Box 25. Condition Codes
		    put_ub04(7, 74, 2, ($claim->ub04_options[condition_code_8]));
	
	    // Box 26. Condition Codes
		    put_ub04(7, 78, 2, ($claim->ub04_options[condition_code_9]));
	
	    // Box 27. Condition Codes
		    put_ub04(7, 82, 2, ($claim->ub04_options[condition_code_10]));
	
	    // Box 28. Condition Codes
		    put_ub04(7, 86, 2, ($claim->ub04_options[condition_code_11]));
	
	    // Box 29. Accident State
		    put_ub04(7, 89, 2, ($claim->ub04_options[accident_state]));
	
	    // Box 30. Accident Date
        $tmp = $claim->ub04_options[accident_date];
		If ($tmp != "0000-00-00") {
            put_ub04(7, 94, 2, substr($tmp, 5, 2));
            put_ub04(7, 96, 2, substr($tmp, 8, 2));
            put_ub04(7, 98, 2, substr($tmp, 2, 2));
        }
	    // Box 31A. Occurrence Code and Date
        $tmp = $claim->ub04_options[occurrence_code_1];
		    put_ub04(9, 1, 2, $tmp);
		
		$tmp = $claim->ub04_options[occurrence_date_1];
		If ($tmp != "0000-00-00") {
		    put_ub04(9, 6, 2, substr($tmp, 5, 2));
            put_ub04(9, 8, 2, substr($tmp, 8, 2));
            put_ub04(9, 10, 2, substr($tmp, 2, 2));
        }
        // Box 32A. Occurrence Code and Date
    	$tmp = $claim->ub04_options[occurrence_code_2];
		    put_ub04(9, 15, 2, $tmp);
		
	    $tmp = $claim->ub04_options[occurrence_date_2];
		If ($tmp != "0000-00-00") {
	        put_ub04(9, 19, 2, substr($tmp, 5, 2));
            put_ub04(9, 21, 2, substr($tmp, 8, 2));
            put_ub04(9, 23, 2, substr($tmp, 2, 2));
	    }
	    // Box 33A. Occurrence Code and Date
    	$tmp = $claim->ub04_options[occurrence_code_3];
		    put_ub04(9, 28, 2, $tmp);
	
	    $tmp = $claim->ub04_options[occurrence_date_3];
		If ($tmp != "0000-00-00") {
		    put_ub04(9, 33, 2, substr($tmp, 5, 2));
            put_ub04(9, 35, 2, substr($tmp, 8, 2));
            put_ub04(9, 37, 2, substr($tmp, 2, 2));
	    }
	    // Box 34A. Occurrence Code and Date
    	$tmp = $claim->ub04_options[occurrence_code_4];
		    put_ub04(9, 41, 2, $tmp);
		
	    $tmp = $claim->ub04_options[occurrence_date_4];
		If ($tmp != "0000-00-00") {
		    put_ub04(9, 46, 2, substr($tmp, 5, 2));
            put_ub04(9, 48, 2, substr($tmp, 8, 2));
            put_ub04(9, 50, 2, substr($tmp, 2, 2));
	    }
	    // Box 35A. Occurrence Code and Date
    	$tmp = $claim->ub04_options[occurrence_span_code_1];
		    put_ub04(9, 55, 2, $tmp);
	    $tmp = $claim->ub04_options[occurrence_span_date_from_1];
		If ($tmp != "0000-00-00") {
		    put_ub04(9, 59, 2, substr($tmp, 5, 2));
            put_ub04(9, 61, 2, substr($tmp, 8, 2));
            put_ub04(9, 63, 2, substr($tmp, 2, 2));
		$tmp = $claim->ub04_options[occurrence_span_date_to_1];
		    put_ub04(9, 68, 2, substr($tmp, 5, 2));
            put_ub04(9, 70, 2, substr($tmp, 8, 2));
            put_ub04(9, 72, 2, substr($tmp, 2, 2));
	    }
	    // Box 36A. Occurrence Code and Date
    	$tmp = $claim->ub04_options[occurrence_span_code_2];
		    put_ub04(9, 78, 2, $tmp);
	    $tmp = $claim->ub04_options[occurrence_span_date_from_2];
		If ($tmp != "0000-00-00") {
		    put_ub04(9, 81, 2, substr($tmp, 5, 2));
            put_ub04(9, 83, 2, substr($tmp, 8, 2));
            put_ub04(9, 85, 2, substr($tmp, 2, 2));
		$tmp = $claim->ub04_options[occurrence_span_date_to_2];
		    put_ub04(9, 91, 2, substr($tmp, 5, 2));
            put_ub04(9, 93, 2, substr($tmp, 8, 2));
            put_ub04(9, 95, 2, substr($tmp, 2, 2));
	    }
	    // Box 31B. Occurrence Code and Date
        $tmp = $claim->ub04_options[occurrence_code_1A];
		    put_ub04(10, 1, 2, $tmp);
		$tmp = $claim->ub04_options[occurrence_date_1A];
		If ($tmp != "0000-00-00") {
		    put_ub04(10, 6, 2, substr($tmp, 5, 2));
            put_ub04(10, 8, 2, substr($tmp, 8, 2));
            put_ub04(10, 10, 2, substr($tmp, 2, 2));
	    }
	    // Box 32B. Occurrence Code and Date
    	$tmp = $claim->ub04_options[occurrence_code_2A];
		    put_ub04(10, 15, 2, $tmp);
	    $tmp = $claim->ub04_options[occurrence_date_2A];
		If ($tmp != "0000-00-00") {
		    put_ub04(10, 19, 2, substr($tmp, 5, 2));
            put_ub04(10, 21, 2, substr($tmp, 8, 2));
            put_ub04(10, 23, 2, substr($tmp, 2, 2));
	    }
	    // Box 33B. Occurrence Code and Date
    	$tmp = $claim->ub04_options[occurrence_code_3A];
		    put_ub04(10, 28, 2, $tmp);
	    $tmp = $claim->ub04_options[occurrence_date_3A];
		If ($tmp != "0000-00-00") {
		    put_ub04(10, 33, 2, substr($tmp, 5, 2));
            put_ub04(10, 35, 2, substr($tmp, 8, 2));
            put_ub04(10, 37, 2, substr($tmp, 2, 2));
	    }
	    // Box 34B. Occurrence Code and Date
    	$tmp = $claim->ub04_options[occurrence_code_4A];
		    put_ub04(10, 41, 2, $tmp);
	    $tmp = $claim->ub04_options[occurrence_date_4A];
		If ($tmp != "0000-00-00") {
	        put_ub04(10, 46, 2, substr($tmp, 5, 2));
            put_ub04(10, 48, 2, substr($tmp, 8, 2));
            put_ub04(10, 50, 2, substr($tmp, 2, 2));
        }
	    // Box 35B. Occurrence Span Code and Date
    
	    $tmp = $claim->ub04_options[occurrence_span_code_1A];
		    put_ub04(10, 55, 2, $tmp);
	    $tmp = $claim->ub04_options[occurrence_span_date_from_1A];
		If ($tmp != "0000-00-00") {
		    put_ub04(10, 59, 2, substr($tmp, 5, 2));
            put_ub04(10, 61, 2, substr($tmp, 8, 2));
            put_ub04(10, 63, 2, substr($tmp, 2, 2));
	    $tmp = $claim->ub04_options[occurrence_span_date_to_1A];
		    put_ub04(10, 68, 2, substr($tmp, 5, 2));
            put_ub04(10, 70, 2, substr($tmp, 8, 2));
            put_ub04(10, 72, 2, substr($tmp, 2, 2));
	    }
	    // Box 36B. Occurrence Span Code and Date
        $tmp = $claim->ub04_options[occurrence_span_code_2A];
		    put_ub04(10, 78, 2, $tmp);
	    $tmp = $claim->ub04_options[occurrence_span_date_from_2A];
		If ($tmp != "0000-00-00") {
		    put_ub04(10, 81, 2, substr($tmp, 5, 2));
            put_ub04(10, 83, 2, substr($tmp, 8, 2));
            put_ub04(10, 85, 2, substr($tmp, 2, 2));
	    $tmp = $claim->ub04_options[occurrence_span_date_to_2A];
		    put_ub04(10, 91, 2, substr($tmp, 5, 2));
            put_ub04(10, 93, 2, substr($tmp, 8, 2));
            put_ub04(10, 95, 2, substr($tmp, 2, 2));
	    }
        // Box 38 . Insured's Name 
		
		if ($claim->patientMiddleName()) {
           $tmp = $claim->patientLastName() . ', ' . $claim->patientFirstName() . ' ' . substr($claim->patientMiddleName(),0,1);
        }
        else
        { 
           $tmp = $claim->patientLastName() . ', ' . $claim->patientFirstName();
        }
		
        //if ($claim->insuredMiddleName()) {
        // $tmp = $claim->insuredLastName() . ', ' . $claim->insuredFirstName() . ' ' . substr($claim->insuredMiddleName(),0,1);
        //}
        //else
        //{  
        // $tmp = $claim->insuredLastName() . ', ' . $claim->insuredFirstName();
        //}
		    put_ub04(11, 2, 28, $tmp);
			
        // Box 38. Insured's Address
            put_ub04(12, 2, 28, $claim->patientStreet());
	
	
	    // Box 39A. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_1a];
	        put_ub04(12, 59, 2, $tmp);
		$tmp = $claim->ub04_options[value_code_amount_1a];
		
		If ($tmp != 0) {
		    put_ub04(12, 68, 9, sprintf('%8.2f', $tmp));
	    }
	    // Box 40A. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_2a];
            put_ub04(12, 77, 2, $tmp);
		$tmp = $claim->ub04_options[value_code_amount_2a];
		If ($tmp != 0) {
		    put_ub04(12, 85, 9, sprintf('%8.2f', $tmp));
	    }
	    // Box 41A. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_3a];
	        put_ub04(12, 94, 2, $tmp);
		$tmp = $claim->ub04_options[value_code_amount_3a];
		If ($tmp != 0) {
	        put_ub04(12, 102, 9, sprintf('%8.2f', $tmp));
        }
        // Box 38 continued. Insured's City and State $claim->insuredCity() $claim->patientCity()
            put_ub04(13, 2, 20, $claim->patientCity());
            put_ub04(13, 32, 2, $claim->patientState());
            put_ub04(13, 35, 10, $claim->patientZip());
	
        // Box 39B. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_1b];
	        put_ub04(13, 59, 2, $tmp);
		$tmp = $claim->ub04_options[value_code_amount_1b];
		If ($tmp != 0) {
	        put_ub04(13, 68, 9, sprintf('%8.2f', $tmp));
	    }
	    // Box 40B. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_2b];
	        put_ub04(13, 77, 2, $tmp);
		$tmp = $claim->ub04_options[value_code_amount_2b];
		If ($tmp != 0) {
		    put_ub04(13, 85, 9, sprintf('%8.2f', $tmp));
	    }
	    // Box 41B. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_3b];
	        put_ub04(13, 94, 2, $tmp);
	    $tmp = $claim->ub04_options[value_code_amount_3b];
		If ($tmp != 0) {
		    put_ub04(13, 102, 9, sprintf('%8.2f', $tmp));
        }
        // Box 38 continued. Insured's Zip Code and Telephone
        $tmp = $claim->insuredPhone();
            put_ub04(14, 2, 3, substr($tmp, 0, 3));
            put_ub04(14, 6, 7, substr($tmp, 3));

	    // Box 39C. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_1c];
	        put_ub04(14, 59, 2, $tmp);
	    $tmp = $claim->ub04_options[value_code_amount_1c];
		If ($tmp != 0) {
	        put_ub04(14, 68, 9, sprintf('%8.2f', $tmp));
	    }
	    // Box 40C. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_2c];
	        put_ub04(14, 77, 2, $tmp);
	    $tmp = $claim->ub04_options[value_code_amount_2c];
		If ($tmp != 0) {
	        put_ub04(14, 85, 9, sprintf('%8.2f', $tmp));
	    }
	    // Box 41C. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_3c];
	        put_ub04(14, 94, 2, $tmp);
	    $tmp = $claim->ub04_options[value_code_amount_3c];
		If ($tmp != 0) {
	        put_ub04(14, 102, 9, sprintf('%8.2f', $tmp));
	    }
	    // Box 39D. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_1d];
		    put_ub04(15, 59, 2, $tmp);
	    $tmp = $claim->ub04_options[value_code_amount_1d];
		If ($tmp != 0) {
	        put_ub04(15, 68, 9, sprintf('%8.2f', $tmp));
	    }
	    // Box 40D. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_2d];
	        put_ub04(15, 77, 2, $tmp);
	    $tmp = $claim->ub04_options[value_code_amount_2d];
		If ($tmp != 0) {
	        put_ub04(15, 85, 9, sprintf('%8.2f', $tmp));
	    }
	    // Box 41D. Value Code and Amount
        $tmp = $claim->ub04_options[value_code_3d];
	        put_ub04(15, 94, 2, $tmp);
	    $tmp = $claim->ub04_options[value_code_amount_3d];
		If ($tmp != 0) {
	        put_ub04(15, 102, 9, sprintf('%8.2f', $tmp));
        }
        $proccount = $claim->procCount(); // number of procedures
        // Charges, adjustments and payments are accumulated by line item so that
        // each page of a multi-page claim will stand alone.  Payments include the
        // co-pay for the first page only.
        $clm_total_charges = 0;
        $clm_amount_adjusted = 0;
        $clm_amount_paid = $ub04_proc_index ? 0 : $claim->patientPaidAmount();
 	
	    // get revenue codes (related_code) and merge the array with $claim to prepare for sorting
	    //
        for ($tlh =0;$tlh< $proccount; ++$tlh) {
 	    $tmp = $claim->procs[$tlh][code_text];
	
	    if ($claim->procs[$tlh][code_type] == 'HCPCS') {
		    $tmpcode = '3';
	    }
	    else
	    {
		    $tmpcode = '1';
	    }
		    $getrevcd = $claim->cptCode($tlh);
		    $sql = "SELECT * FROM codes WHERE code_type = ? and code = ? ORDER BY related_code DESC";
		    $revcode[$tlh] = sqlQuery($sql, array($tmpcode,$getrevcd) );
		    $revcode2[$tlh] = array_merge( $revcode[$tlh],$claim->procs[$tlh] );
        }
	
    	// set up and sort the array on related_code (revenue code) for proper printing order
	    //
	    foreach ($revcode as $key => $row) {
            $revcod[$key]  = $row['related_code'];
        }
        array_multisort($revcod, SORT_ASC, $revcode2);
  
        // Procedure loop starts here.
        //
	    for ($svccount = 0; $svccount < 22 && $ub04_proc_index < $proccount; ++$ub04_proc_index) {
          $dia = $claim->diagIndexArray($ub04_proc_index);

          if (!$claim->cptCharges($ub04_proc_index)) {
            $log .= "*** Procedure '" . $claim->cptKey($ub04_proc_index) .
                    "' has no charges!\n";
          }

          if (empty($dia)) {
            $log .= "*** Procedure '" . $claim->cptKey($ub04_proc_index) .
                    "' is not justified!\n";
          }

          $clm_total_charges += $claim->cptCharges($ub04_proc_index);

          // Compute prior payments and "hard" adjustments.
          for ($ins = 1; $ins < $claim->payerCount(); ++$ins) {
            if ($claim->payerSequence($ins) > $claim->payerSequence())
                continue; // skip future payers
            $payerpaid = $claim->payerTotals($ins, $claim->cptKey($ub04_proc_index));
            $clm_amount_paid += $payerpaid[1];
            $clm_amount_adjusted += $payerpaid[2];
          }

        ++$svccount;
		
    
	      $lino = $svccount  + 15;
	
  
          ++$lino;
		  $num = ($ub04_proc_index);
	
		  $tmp = $revcode2[$num][related_code];
    	      put_ub04($lino, 1, 4, substr($tmp,14,4));
              put_ub04($lino, 8, 22, $revcode2[$num][code_text]);
              put_ub04($lino, 43, 10, $revcode2[$num][code]);
			  
			  $procode[$num] = $revcode2[$num][code] .'@'. $revcode2[$num][date] ;
       
	      // replace colon with space for printing May need to make this blank
              put_ub04($lino, 54, 28, str_replace(':', ' ', $revcode2[$num][modifier]));

          // 45. Service Date
				
          //$tmp = $revcode2[$num][date];
		  $tmp = $claim->serviceDate();
		      put_ub04($lino, 63, 2, substr($tmp, 4, 2));
              put_ub04($lino, 65, 2, substr($tmp, 6, 2));
              put_ub04($lino, 67, 2, substr($tmp, 2, 2));
 
       
		  // Box 46. Service Units
		  //
		      put_ub04($lino, 75, 4, $revcode2[$num][units]);
		 
       
          // Box 46 Total Charges
              put_ub04($lino, 84, 8, str_replace('.', ' ', sprintf('%8.2f', $revcode2[$num][fee])));

         
        }

    
        // Line 23 HCPCS revenue code 
            put_ub04(39, 1, 4, '0001');
            put_ub04(39, 15, 1, '1');
	        put_ub04(39, 23, 1, '1');
	
	
        // Line 23 Creation Date
        $tmp = date("m-d-y");
            put_ub04(39, 61, 2, substr($tmp, 0, 2));
            put_ub04(39, 63, 2, substr($tmp, 3, 2));
            put_ub04(39, 65, 2, substr($tmp, 6, 2));



        // Line 23 Total Charges
            put_ub04(39, 84, 8, str_replace('.', ' ', sprintf('%8.2f', $clm_total_charges)));
        if (!$clm_total_charges) {
            $log .= "*** This claim has no charges!\n";
        }

    //  Amount Paid
    //        put_ub04(39, 62, 8, str_replace('.',' ',sprintf('%8.2f',$clm_amount_paid)));
    //  Balance Due
    // For secondary payers this reflects primary "contracted rate" adjustments,
    // so in general box 30 will not equal box 28 minus box 29.
    //
    //
    //       put_ub04(39, 71, 8, str_replace('.',' ',sprintf('%8.2f',
    //       $clm_total_charges - $clm_amount_paid - $clm_amount_adjusted)));
    //
   
        // 56. Service Facility NPI
        put_ub04(40, 92, 10, $claim->facilityNPI());

        // Box 50. Payer Name
	    // Primary Payer
	    //
	    $tmp = $claim->payerName(0);
            put_ub04(41, 1, 20, $tmp);
	
	    $tmp = $claim->planName(0);
	        put_ub04(41, 24, 15, $tmp);

        // 52. Release of Information
	    put_ub04(41, 52, 1, 'Y');
	
        // 53. Accept Assignment
        if ($claim->billingFacilityAssignment() == TRUE) {
            put_ub04(41, 56, 1, 'Y');
        } 
		else 
		{
            put_ub04(41, 56, 1, 'N');
        }
        
		// Secondary payor
	    //
	    $tmp = $claim->payerName(1);
                put_ub04(42, 1, 20, $tmp);
	
	    $tmp = $claim->planName(1);
	            put_ub04(42, 24, 15, $tmp);
		
		        // 52. Release of Information
		if ($claim->payerName(1) != ''){
	       put_ub04(42, 52, 1, 'Y');
	    }
        // 53. Accept Assignment
        if ($claim->payerName(1) != ''){
		  if ($claim->billingFacilityAssignment() == TRUE) {
            put_ub04(42, 56, 1, 'Y');
          } 
		  else 
		  {
            put_ub04(42, 56, 1, 'N');
          }
		}
		//print_r2($claim);
		// Box 58. Insured's Name 

        if ($claim->insuredMiddleName()) {
         $tmp = $claim->insuredLastName() . ', ' . $claim->insuredFirstName() . ' ' . substr($claim->insuredMiddleName(),0,1);
        }
        else
        {  
         $tmp = $claim->insuredLastName() . ', ' . $claim->insuredFirstName();
        }
            put_ub04(45, 1, 28, $tmp);

        // Box 59. Patient Relationship to Insured  line 44
            put_ub04(45, 36, 2,  $claim->insuredRelationship());

        // Box 60. Insured's ID Number
	        put_ub04(45, 40, 17, $claim->policyNumber());
	
	    // Box 61. Group Name
	    //    put_ub04(45, 58, 17, $claim->groupNumber());

	    // Box 61. Group Name
	        put_ub04(45, 92, 17, $claim->groupNumber());
			
	    // Box 63. Treatment Authorization Number
	        put_ub04(49, 1, 28, $claim->ub04_options[prior_auth_number]);
	   	    put_ub04(50, 1, 28, $claim->ub04_options[prior_auth_number_1]);
            put_ub04(51, 1, 28, $claim->ub04_options[prior_auth_number_1]);	
	
        $tmp = $claim->diagArray();
        $diags = array();
		
        foreach ($tmp as $diag) $diags[] = $diag;
        if (!empty($diags[0])) {
            put_ub04(52, 4, 5, substr($diags[0], 0, 5));
        }
        if (!empty($diags[1])) {
            put_ub04(52, 14, 5, substr($diags[1], 0, 5));
        }
		if (!empty($diags[2])) {
            put_ub04(52, 25, 5, substr($diags[2], 0, 5));
        }
        if (!empty($diags[3])) {
            put_ub04(52, 36, 5, substr($diags[3], 0, 5));
        }
		if (!empty($diags[4])) {
            put_ub04(52, 46, 5, substr($diags[4], 0, 5));
        }
        if (!empty($diags[5])) {
            put_ub04(52, 57, 5, substr($diags[5], 0, 5));
        }
		if (!empty($diags[6])) {
            put_ub04(52, 67, 5, substr($diags[6], 0, 5));
        }
        if (!empty($diags[7])) {
            put_ub04(52, 80, 5, substr($diags[7], 0, 5));
        }
		if (!empty($diags[8])) {
            put_ub04(52, 88, 5, substr($diags[8], 0, 5));
        }

		
		// Diagnosis Type
		// ICD9 or ICD10
		$tmp1 = $claim->diagtype;
        if ($tmp1 == "ICD9"){
		    put_ub04(53, 1, 1, '9');
		} else {
            put_ub04(53, 1, 1, '0');
		}
	
       if (!empty($diags[9])) {
            put_ub04(53, 4, 5, substr($diags[9], 0, 5));
        }
        if (!empty($diags[10])) {
            put_ub04(53, 14, 5, substr($diags[10], 0, 5));
        }
		if (!empty($diags[11])) {
            put_ub04(53, 25, 5, substr($diags[11], 0, 5));
        }
        if (!empty($diags[12])) {
            put_ub04(53, 36, 5, substr($diags[12], 0, 5));
        }
		if (!empty($diags[13])) {
            put_ub04(53, 46, 5, substr($diags[13], 0, 5));
        }
        if (!empty($diags[14])) {
            put_ub04(53, 57, 5, substr($diags[14], 0, 5));
        }
		if (!empty($diags[15])) {
            put_ub04(53, 67, 5, substr($diags[15], 0, 5));
        }
        if (!empty($diags[16])) {
            put_ub04(53, 80, 5, substr($diags[16], 0, 5));
        }
		if (!empty($diags[17])) {
            put_ub04(53, 88, 5, substr($diags[17], 0, 5));
        }
	
	
	    if (!empty($diags[0])) {
            put_ub04(54, 8, 5, substr($diags[0], 0, 5));
		}
		if (!empty($diags[1])) {
            put_ub04(54, 25, 5, substr($diags[1], 0, 5));
		}
		if (!empty($diags[2])) {
            put_ub04(54, 35, 5, substr($diags[2], 0, 5));
		}
		if (!empty($diags[3])) {
            put_ub04(54, 42, 5, substr($diags[3], 0, 5));
		}
		
		
		// Box 76. Attending Information
		
		IF (strlen($claim->ub04_options[attending_first_name]) != 0) {
           if ($claim->ub04_options[attending_qualifier] == 'XX') {
		    put_ub04(55, 79, 10, $claim->ub04_options[attending_npi]);
		   }
		   else
		   {
		    put_ub04(55, 79, 10, $claim->ub04_options[attending_upin]);
		   }
			put_ub04(55, 96, 2,  $claim->ub04_options[attending_qualifier]);
		}
		
		
		$tmp1 = $claim->serviceDate();
		$tmp = strpos($procode[0], '@');
		put_ub04(56, 1, 8, substr($procode[0],0,$tmp));
		if (substr($procode[0],0,$tmp) != '') {
		put_ub04(56, 13, 2, substr($tmp1, 4, 2));
        put_ub04(56, 15, 2, substr($tmp1, 6, 2));
        put_ub04(56, 17, 2, substr($tmp1, 2, 2));
		}
        
		$tmp = strpos($procode[1], '@');
		put_ub04(56, 23, 8, substr($procode[1],0,$tmp));
		if (substr($procode[1],0,$tmp) != '') {
		put_ub04(56, 33, 2, substr($tmp1, 4, 2));
        put_ub04(56, 35, 2, substr($tmp1, 6, 2));
        put_ub04(56, 37, 2, substr($tmp1, 2, 2));
        }
		
		$tmp = strpos($procode[2], '@');
		put_ub04(56, 42, 8, substr($procode[2],0,$tmp));
		if (substr($procode[2],0,$tmp) != '') {
		put_ub04(56, 53, 2, substr($tmp1, 4, 2));
        put_ub04(56, 55, 2, substr($tmp1, 6, 2));
        put_ub04(56, 57, 2, substr($tmp1, 2, 2));
		}
		
        IF (strlen($claim->ub04_options[attending_first_name]) != 0) {		
			put_ub04(56, 71, 20, $claim->ub04_options[attending_last_name]);
			put_ub04(56, 95, 20, $claim->ub04_options[attending_first_name]);
		}
        
					
	    // Box 77. Operating Information
		IF (strlen($claim->ub04_options[operating_first_name]) != 0) {
           if ($claim->ub04_options[operating_qualifier] == 'XX') {
		    put_ub04(57, 79, 10, $claim->ub04_options[operating_npi]);
		   }
		   else
		   {
		    put_ub04(57, 79, 10, $claim->ub04_options[operating_upin]);
		   }
			put_ub04(57, 96, 2,  $claim->ub04_options[operating_qualifier]);
		}
	
        $tmp1 = $claim->serviceDate();
		$tmp = strpos($procode[3], '@');
	    put_ub04(58, 1, 8, substr($procode[3],0,$tmp));
		if (substr($procode[3],0,$tmp) != '') {
		put_ub04(58, 13, 2, substr($tmp1, 4, 2));
        put_ub04(58, 15, 2, substr($tmp1, 6, 2));
        put_ub04(58, 17, 2, substr($tmp1, 2, 2));
        }
		
		$tmp = strpos($procode[4], '@');
		put_ub04(58, 23, 8, substr($procode[4],0,$tmp));
		if (substr($procode[4],0,$tmp) != '') {
		put_ub04(58, 33, 2, substr($tmp1, 4, 2));
        put_ub04(58, 35, 2, substr($tmp1, 6, 2));
        put_ub04(58, 37, 2, substr($tmp1, 2, 2));
	    }
		
		$tmp = strpos($procode[5], '@');
		put_ub04(58, 42, 8, substr($procode[5],0,$tmp));
		if (substr($procode[5],0,$tmp) != '') {
		put_ub04(58, 53, 2, substr($tmp1, 4, 2));
        put_ub04(58, 55, 2, substr($tmp1, 6, 2));
        put_ub04(58, 57, 2, substr($tmp1, 2, 2));
		}
		
        IF (strlen($claim->ub04_options[operating_first_name]) != 0) {		
			put_ub04(58, 71, 20, $claim->ub04_options[operating_last_name]);
			put_ub04(58, 95, 20, $claim->ub04_options[operating_first_name]);
		}
        
        // box 80 remarks (Ins name and address)
		
		 //put_ub04(59, 1, 31, $claim->payerName());
		
		// Box 81CC A.
		
		IF (strlen($claim->ub04_options[qualifier_a]) != 0 AND strlen($claim->ub04_options[attending_first_name]) != 0) {
   	        put_ub04(59, 36, 2,  $claim->ub04_options[qualifier_a]);
			put_ub04(59, 39, 10, $claim->ub04_options[taxonomy_a]);		
		}
        
        
		
	
		
		// Box 78. Other Information
		IF (strlen($claim->ub04_options[other_1_first_name]) != 0) {
           if ($claim->ub04_options[other_1_qualifier] == 'XX') {
		    put_ub04(59, 79, 10, $claim->ub04_options[other_1_npi]);
		   }
		   else
		   {
		    put_ub04(59, 79, 10, $claim->ub04_options[other_1_upin]);
		   }
			put_ub04(59, 96, 2,  $claim->ub04_options[other_1_qualifier]);
		}
     	
		// Box 80 remarks (Ins Billing address)
		
        //put_ub04(60, 1, 31, $claim->payerAttn());
		put_ub04(60, 1, 31, $claim->payerName());

		// Box 81CC B.	
		IF (strlen($claim->ub04_options[qualifier_b]) != 0 AND strlen($claim->ub04_options[operating_first_name]) != 0) {
   	        put_ub04(60, 36, 2,  $claim->ub04_options[qualifier_b]);
			put_ub04(60, 39, 10, $claim->ub04_options[taxonomy_b]);		
		}
        

		// Box 78. Other Information Continued
		IF (strlen($claim->ub04_options[other_1_first_name]) != 0) {
			put_ub04(60, 71, 20, $claim->ub04_options[other_1_last_name]);
			put_ub04(60, 95, 20, $claim->ub04_options[other_1_first_name]);
		}
        
		// Box 80 remark (Ins Address)
		  put_ub04(61, 1, 31, $claim->payerStreet());

		
		
	    // Box 81CC C.
		IF (strlen($claim->ub04_options[qualifier_c]) != 0 AND strlen($claim->ub04_options[other_1_first_name]) != 0) {
   	        put_ub04(61, 36, 2,  $claim->ub04_options[qualifier_c]);
			put_ub04(61, 39, 10, $claim->ub04_options[taxonomy_c]);		
		}
        		

		
		// Box 79. Other Information
		IF (strlen($claim->ub04_options[other_2_first_name]) != 0) {
           if ($claim->ub04_options[other_2_qualifier] == 'XX') {
		    put_ub04(61, 79, 10, $claim->ub04_options[other_2_npi]);
		   }
		   else
		   {
		    put_ub04(61, 79, 10, $claim->ub04_options[other_2_upin]);
		   }
			put_ub04(61, 96, 2,  $claim->ub04_options[other_2_qualifier]);
		}
        
		
		
        // Box 80 Remark (Payer city, state, zip.)
        $tmp = $claim->payerCity() ? ($claim->payerCity() . ', ') : '';
        put_ub04(62, 1, 31, $tmp . $claim->payerState() . ' ' . $claim->payerZip());
		
	    // Box 81CC D.	
		IF (strlen($claim->ub04_options[qualifier_d]) != 0 AND strlen($claim->ub04_options[other_2_first_name]) != 0) {
   	        put_ub04(62, 36, 2,  $claim->ub04_options[qualifier_d]);
			put_ub04(62, 39, 10, $claim->ub04_options[taxonomy_d]);		
		}
        	


		// Box 79. Other Information Continued
		IF (strlen($claim->ub04_options[other_2_first_name]) != 0) {
			put_ub04(62, 71, 20, $claim->ub04_options[other_2_last_name]);
			put_ub04(62, 95, 20, $claim->ub04_options[other_2_first_name]);
		}
        
	
			
	
    return;
}

?>
