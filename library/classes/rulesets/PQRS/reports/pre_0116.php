<?php
/**
 * Pre Measure 0116 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */

class pre_0116 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new pre_0116_PopulationCriteria();
    }
}

?>