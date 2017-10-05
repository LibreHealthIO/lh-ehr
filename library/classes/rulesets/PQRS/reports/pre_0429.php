<?php
/**
 * Pre Measure 0429 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package PQRS_Gateway
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class pre_0429 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new pre_0429_PopulationCriteria();
    }
}

?>
