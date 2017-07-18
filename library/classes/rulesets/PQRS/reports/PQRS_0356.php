<?php
/**
 * PQRS Measure 0356 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0356 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0356_PopulationCriteria();
    }
}

?>
