<?php

namespace Framework\DataTable\ColumnBehavior;

use Framework\DataTable\ColumnBehaviorIF;
use Framework\AbstractModel;

require_once 'ActiveElement.php';

class ActiveStatic extends ActiveElement implements ColumnBehaviorIF
{
    protected $name = '';
    protected $class = '';
    protected $attributes = array();

    public function getOutput( $data )
    {
        $name = $this->name;
        $value = "";
        if ( $data[$name] ) {
            $value = $data[$name];
        }

        $class = $this->class;

        $attributes = "";
        foreach ( $this->attributes as $attr ) {
            $v = $data[$attr];
            $attributes .= " data-$attr='$v' ";
        }

        $output = "<span class='mi2-editable $class' $attributes>$value</span>";

        return $output;
    }
}
