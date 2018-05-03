<?php
/**
 * PQRS Measure 0404 -- Denominator 
 *
 * Copyright (C) 2015 - 2017      Suncoast Connection
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
 *
 * @author  Suncoast Connection
 */
 
class PQRS_0404_Denominator extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$query =
"SELECT COUNT(b2.code) as count ".  
" FROM billing AS b2". 
" JOIN form_encounter AS fe ON (b2.encounter = fe.encounter)".
" JOIN patient_data AS p ON (p.pid = b2.pid)".
" INNER JOIN billing AS b3 ON (b3.pid = b2.pid)".
" INNER JOIN billing AS b4 ON (b4.pid = b2.pid)".
" WHERE b2.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND TIMESTAMPDIFF(YEAR,p.DOB,fe.date)>='18' ".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
" AND (b2.code = 'G9642')".
" AND (b3.code = 'G9643')".
" AND (b4.code = 'G9497'); ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;} else {return false;}
    }
}

?>
