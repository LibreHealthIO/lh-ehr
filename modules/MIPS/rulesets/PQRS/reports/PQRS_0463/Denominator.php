<?php
/**
 * PQRS Measure 0463 -- Denominator 
 *
 * Copyright (C) 2015 - 2018      Suncoast Connection
  * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */
 
class PQRS_0463_Denominator extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
    $query =
    " SELECT COUNT(b1.code) AS count".  
    " FROM billing AS b1".
    " WHERE b1.pid = ? ".
    " AND b1.code = 'G9955'; ";
    //rule out
    $result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 

    if ($result['count']> 0){ return false;} 
        else { 
        $query =
        " SELECT COUNT(b1.code) AS count".  
        " FROM billing AS b1".
        " INNER JOIN billing AS b2 ON (b1.pid = b2.pid) ".
        " WHERE b1.pid = ? ".
        " AND b1.code ='4554F' ".
        " AND b2.code = 'G9954'; ";
        //Include
        $result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 
        
        if ($result['count']> 0){ return true;} else {return false;}  

    }else {return false;}
    }
}
?>
