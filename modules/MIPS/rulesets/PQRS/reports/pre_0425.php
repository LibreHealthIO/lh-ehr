<?php
/**
 * Pre Measure 0425 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package PQRS_Gateway
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class pre_0425 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new pre_0425_PopulationCriteria();
    }
}

?>
