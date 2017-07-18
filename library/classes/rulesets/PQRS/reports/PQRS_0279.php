<?php
/**
 * PQRS Measure 0279 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0279 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0279_PopulationCriteria();
    }
}

?>
