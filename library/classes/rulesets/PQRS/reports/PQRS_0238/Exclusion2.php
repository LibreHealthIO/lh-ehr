<?php
/**
 * PQRS Measure 0238 -- Exclusion 2
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */

class PQRS_0238_Exclusion2 extends PQRSFilter
{
    public function getTitle() 
    {
        return "Exclusion 2";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
return false;  

    }
}

?>
