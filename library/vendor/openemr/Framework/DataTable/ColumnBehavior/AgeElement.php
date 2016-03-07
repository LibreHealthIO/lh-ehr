<?php

namespace Framework\DataTable\ColumnBehavior;

use Framework\DataTable\ColumnBehaviorIF;

require_once 'ActiveElement.php';
require_once(__DIR__."/../../../library/patient.inc");

class AgeElement extends ActiveElement implements ColumnBehaviorIF
{
    
    public function getOutput( $data )
    {
        $dob = date( 'Y-m-d', strtotime( $data['DOB'] ) );
        $age = getPatientAge( $dob );
        return $age;
    }
}
