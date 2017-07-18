<?php
/**
 * PQRS Measure 0093 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */

class PQRS_0093 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0093_PopulationCriteria();
    }
}

?>
