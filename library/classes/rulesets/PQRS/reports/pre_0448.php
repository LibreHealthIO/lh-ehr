<?php
/**
 * Pre Measure 0448 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package PQRS_Gateway
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class pre_0448 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new pre_0448_PopulationCriteria();
    }
}

?>
