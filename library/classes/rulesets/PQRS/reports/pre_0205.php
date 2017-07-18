<?php
/**
 * Pre Measure 0205 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */

class pre_0205 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new pre_0205_PopulationCriteria();
    }
}

?>
