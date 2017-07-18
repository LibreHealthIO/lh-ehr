<?php
/**
 * PQRS Measure 0445 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0445 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0445_PopulationCriteria();
    }
}

?>
