<?php
/**
 * Pre Measure 0278 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package PQRS_Gateway
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class pre_0278 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new pre_0278_PopulationCriteria();
    }
}

?>
