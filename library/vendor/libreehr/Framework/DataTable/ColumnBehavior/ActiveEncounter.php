<?php

namespace Framework\DataTable\ColumnBehavior;

use Framework\DataTable\ColumnBehaviorIF;
use Framework\AbstractModel;

require_once 'ActiveElement.php';

class ActiveEncounter extends ActiveElement implements ColumnBehaviorIF
{
    protected $name = null;
    
    public function getOutput( $data )
    {
        $name = $this->name;
        $title = $data['patient_name'];
        $encounterId = $data['encounter'];
        $encounterDate = date( 'm/d/Y', strtotime( $data['date'] ) );
        $pid = $data['pid'];
        $output = '<a href="javascript;" class="active-encounter" name="'.$name.'" data-pid="'.$pid.'" data-encounter-date="'.$encounterDate.'" data-encounter="'.$encounterId.'">'.$title.'</a>';
     
        return $output;
    }
}