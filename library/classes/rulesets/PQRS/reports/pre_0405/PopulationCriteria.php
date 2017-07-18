<?php
/**
 * pre Measure 0405 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <leebc 11 at acm dot org>
 * @author  Suncoast Connection
 */
 
class pre_0405_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0405_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new pre_0405_Numerator();
    }
    
    public function createDenominator()
    {
        return new pre_0405_Denominator();
    }
    
    public function createExclusion()
    {
        return new pre_0405_Exclusion();
    }
}

?>
