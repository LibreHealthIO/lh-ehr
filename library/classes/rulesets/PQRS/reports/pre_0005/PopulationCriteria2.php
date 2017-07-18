<?php
/**
 * pre Measure 0005 -- Population Criteria 2
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0005_PopulationCriteria2 implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria 2";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0005_InitialPatientPopulation2();
    }
    
    public function createNumerators()
    {
        return new pre_0005_Numerator2();
    }
    
    public function createDenominator()
    {
        return new pre_0005_Denominator2();
    	// Both varients use same Denominator
    }
    
    public function createExclusion()
    {
        return new pre_0005_Exclusion2();
        // Both varients use same Exclusion
    }
}

?>
