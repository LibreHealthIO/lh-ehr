<?php
/**
 * PQRS Measure 0276 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0276 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_0276_PopulationCriteria();
    }
}

?>
