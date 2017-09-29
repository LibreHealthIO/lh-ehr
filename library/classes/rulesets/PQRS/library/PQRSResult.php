<?php
/**
 * PQRS Result
 *
 * Copyright (C) 2015 - 2017      Suncoast Connection
  * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */

class PQRSResult implements RsResultIF
{
    public $rule;
    public $numeratorLabel;
    public $populationLabel;
     
    public $totalPatients; // Total number of patients considered
    public $patientsInPopulation; // Number of patients that pass filter
    public $patientsExcluded; // Number of patients that are excluded
    public $patientsIncluded; // Number of patients that pass target
    public $percentage; // Calculated percentage

    public function __construct( $rowRule, $numeratorLabel, $populationLabel, $totalPatients, $patientsInPopulation, $patientsExcluded, $patientsIncluded, $percentage )
    {
        $this->rule = $rowRule;
        $this->numeratorLabel = $numeratorLabel;
        $this->populationLabel = $populationLabel;
        $this->totalPatients = $totalPatients;
        $this->patientsInPopulation = $patientsInPopulation;
        $this->patientsExcluded = $patientsExcluded;
        $this->patientsIncluded = $patientsIncluded;
        $this->percentage = $percentage;

        // If itemization is turned on, then record the itemized_test_id 
        if ($GLOBALS['report_itemizing_temp_flag_and_id']) {
            $this->itemized_test_id = array('itemized_test_id' => $GLOBALS['report_itemized_test_id_iterator']);
        }

    }

    public function format()
    {
    	$concatenated_label = '';
	if ( $this->numeratorLabel != "Numerator" ) {
		if ( $this->populationLabel != "Population Criteria" ) {
			$concatenated_label = $this->populationLabel . ", " . $this->numeratorLabel;
		}
		else {
			$concatenated_label = $this->numeratorLabel;
		}
	}
	else {
		if ( $this->populationLabel != "Population Criteria" ) {
			$concatenated_label = $this->populationLabel;
		}
	}
    	
        $rowFormat = array( 
        	'is_main'=>TRUE, // TO DO: figure out way to do this when multiple groups.
            'population_label' => $this->populationLabel,
            'numerator_label' => $this->numeratorLabel,
        	'concatenated_label' => $concatenated_label,
            'total_patients' => $this->totalPatients,
            'excluded' => $this->patientsExcluded,
            'pass_filter' => $this->patientsInPopulation,
            'pass_target' => $this->patientsIncluded,
            'percentage' => $this->percentage );
            $rowFormat = array_merge( $rowFormat, $this->rule );

        // If itemization is turned on, then record the itemized_test_id 
        if ($GLOBALS['report_itemizing_temp_flag_and_id']) {
            $rowFormat = array_merge( $rowFormat, $this->itemized_test_id );
        }
        
        return $rowFormat;
    }
}
