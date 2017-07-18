<?php
/**
 * PQRS Measure 0448 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0448 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0448_PopulationCriteria();
    }
}

?>
