<?php

namespace Framework\DataTable\ColumnBehavior;

use Framework\DataTable\ColumnBehaviorIF;
use Framework\AbstractModel;

require_once 'ActiveElement.php';

class ActiveCheckbox extends ActiveElement implements ColumnBehaviorIF
{
    protected $list = null;
    protected $name = null;
    protected $url = null;
    protected $class = null;
    
    public function getOutput( $data )
    {
        $name = $this->name;
        $url = $this->url;
        $encounterId = $data['encounter'];
        $value = $data[$name];
        $output = "";
        $checked = "";
        if ( $value ) { 
            $checked = "checked";        
        } 
        $output = '<input type="checkbox" '.$checked.' class="mi2-editable '.$this->class.'" name="'.$name.'" data-pk="'.$encounterId.'" data-url="'.$url.'" />';
        
        
        return $output;
    }
}
