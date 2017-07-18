<?php
/**
 * PQRS Measure 0363 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0363 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0363_PopulationCriteria();
    }
}

?>
