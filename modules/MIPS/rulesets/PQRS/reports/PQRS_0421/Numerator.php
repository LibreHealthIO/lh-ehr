<?php
/**
 * PQRS Measure 0421 -- Numerator
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <leebc 11 at acm dot org>
 * @author  Suncoast Connection
 */
 
class PQRS_0421_Numerator extends PQRSFilter
{
    public function getTitle()
    {
        return "Numerator";
    }

    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$query =
"SELECT COUNT(b1.code) as count ".  
" FROM billing AS b1 ".
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter) ".
" WHERE b1.pid = ? ".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".  
" AND b1.code IN('G9541','G9542','G9543','37193');"; 
//G9544 hard fail
$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count'] > 0){ return true;} else {return false;} 	
		
    }
}

?>
