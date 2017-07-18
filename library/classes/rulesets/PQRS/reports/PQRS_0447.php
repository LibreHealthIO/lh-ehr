<?php
/**
 * PQRS Measure 0447 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0447 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0447_PopulationCriteria();
    }
}

?>
