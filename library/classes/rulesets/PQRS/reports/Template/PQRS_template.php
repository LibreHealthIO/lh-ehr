<?php
/**
 * PQRS Measure TEMPLATE -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_TEMPLATE extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new PQRS_TEMPLATE_PopulationCriteria();
    }
}

?>
