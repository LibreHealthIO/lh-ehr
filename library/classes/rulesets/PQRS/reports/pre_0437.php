<?php
/**
 * Pre Measure 0437 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package PQRS_Gateway
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class pre_0437 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new pre_0437_PopulationCriteria();
    }
}

?>
