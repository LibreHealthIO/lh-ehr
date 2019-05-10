
<?php
/*
 * HCC Measure HCC_0106 -- Population Criteria
 *
 * Copyright (C) 2018      Suncoast Connection
  * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */
 
class HCC_0106_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new HCC_0106_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new HCC_0106_Numerator();
    }
    
    public function createDenominator()
    {
        return new HCC_0106_Denominator();
    }
    
    public function createExclusion()
    {
        return new HCC_0106_Exclusion();
    }
}

?>

