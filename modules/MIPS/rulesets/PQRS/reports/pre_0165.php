<?php
/**
 * Pre Measure 0165 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package PQRS_Gateway
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class pre_0165 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new pre_0165_PopulationCriteria();
    }
}

?>
