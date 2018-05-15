<?php
/**
 * Pre Measure 0403 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package PQRS_Gateway
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class pre_0403 extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
        return new pre_0403_PopulationCriteria();
    }
}

?>
