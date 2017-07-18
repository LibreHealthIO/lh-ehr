<?php
/**
 * PQRS Measure 0417 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0417 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0417_PopulationCriteria();
    }
}

?>
