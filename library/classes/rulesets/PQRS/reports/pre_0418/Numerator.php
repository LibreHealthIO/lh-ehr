<?php
/**
 * pre Measure 0418 -- Numerator
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <leebc 11 at acm dot org>
 * @author  Suncoast Connection
 */
 
class pre_0418_Numerator extends PQRSFilter
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
" AND b1.code IN('3095F','G8633') AND b1.modifier ='';"; 
//G8635 and 3095F-8P hard fail
$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count'] > 0){ return true;} else {return false;} 	
    }
}

?>
