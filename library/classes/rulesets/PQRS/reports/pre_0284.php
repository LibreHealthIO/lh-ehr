<?php
/**
 * Pre Measure 0284 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package PQRS_Gateway
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class pre_0284 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new pre_0284_PopulationCriteria();
    }
}

?>
