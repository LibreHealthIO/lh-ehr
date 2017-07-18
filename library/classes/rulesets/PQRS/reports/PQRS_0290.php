<?php
/**
 * PQRS Measure 0290 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0290 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0290_PopulationCriteria();
    }
}

?>
