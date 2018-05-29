<?php
/**
 * Pre Measure 0412 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package PQRS_Gateway
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class pre_0412 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new pre_0412_PopulationCriteria();
    }
}

?>
