<?php
/**
 * PQRS Measure 0126 -- Denominator 
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0126_Denominator extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
/*  Rule out needed...manual...fake code or something.  Clinician documented that patient was not an eligible candidate for lower extremity neurological 
exam measure, for example patient bilateral amputee, patient has condition that would not allow 
them to accurately respond to a neurological exam (dementia, Alzheimer’s, etc.), patient has 
previously documented diabetic peripheral neuropathy with loss of protective sensation*/
		return true;
    }
}

?>
