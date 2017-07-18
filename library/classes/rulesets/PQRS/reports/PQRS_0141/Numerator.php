<?php
/**
 * PQRS Measure 0141 -- Numerator
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0141_Numerator extends PQRSFilter
{
    public function getTitle()
    {
        return "Numerator";
    }

    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
 $query =
" SELECT COUNT(b1.code) AS count".  
" FROM billing AS b1".
" INNER JOIN billing AS b2 ON (b2.pid = b1.pid)".
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" WHERE b1.pid = ? ".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
" AND ((b1.code = '3284F' AND b1.modifier ='')".
" OR (b1.code = '0517F' AND b1.modifier ='' AND b2.code = '3285F' AND b1.modifier ='')); ";
$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 
//hard fail identical to above but with modifier 8P for each of the above 2 conditions
if ($result['count']> 0){ return true;} else {return false;}  	
    }
}
