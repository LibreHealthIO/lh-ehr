<?php
/**
 * PQRS Measure 0450 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0450 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0450_PopulationCriteria();
    }
}

?>
