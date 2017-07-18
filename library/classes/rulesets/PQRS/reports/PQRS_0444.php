<?php
/**
 * PQRS Measure 0444 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0444 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0444_PopulationCriteria();
    }
}

?>
