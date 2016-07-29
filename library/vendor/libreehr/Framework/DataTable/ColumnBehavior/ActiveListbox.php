<?php

namespace Framework\DataTable\ColumnBehavior;

use Framework\DataTable\ColumnBehaviorIF;
use Framework\AbstractModel;
use Framework\ListOptions;

require_once 'ActiveElement.php';

class ActiveListbox extends ActiveElement implements ColumnBehaviorIF
{
    protected $list = null;
    protected $name = null;
    protected $class = null;

    public function getOutput( $data )
    {
        $name = $this->name;
        $encounterId = $data['encounter'];
        $value = "None";
        $dataValue = '';
        if ( $data[$name] ) {
            if ( is_array( $this->getMap() ) ) {
                $map = $this->getMap();
                // A map has been provided by the element's constructor
                $value = $map[$data[$name]];
                $dataValue = $data[$name];
            }
        }

        $output = '<a href="javascript;" name="'.$this->name.'"  class="mi2-editable '.$this->class.'" data-pk="'.$encounterId.'" data-value="'.$dataValue.'" data-title="edit" data-type="select">'.$value.'</a>';
        
        return $output;
    }
}
