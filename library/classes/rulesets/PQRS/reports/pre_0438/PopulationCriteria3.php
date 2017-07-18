<?php
/**
 * pre Measure 0438 -- Population Criteria 3
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0438_PopulationCriteria3 implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria 3";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0438_InitialPatientPopulation3();
    }
    
    public function createNumerators()
    {
        return new pre_0438_Numerator3();
    }
    
    public function createDenominator()
    {
        return new pre_0438_Denominator3();
    }
    
    public function createExclusion()
    {
        return new pre_0438_Exclusion3();
    }
}

?>
