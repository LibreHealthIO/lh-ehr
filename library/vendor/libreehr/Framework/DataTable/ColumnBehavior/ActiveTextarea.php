<?php

namespace Framework\DataTable\ColumnBehavior;

use Framework\DataTable\ColumnBehaviorIF;
use Framework\AbstractModel;

require_once 'ActiveElement.php';

class ActiveTextarea extends ActiveElement implements ColumnBehaviorIF
{
    protected $list = null;
    protected $name = null;
    protected $class = null;
    protected $label = 'Add Note';

    public function getOutput( $data )
    {
        $name = $this->name;
        $encounterId = $data['encounter'];

        $value = '';
        if ( $data[$name] ) {
            $value = $data[$name];
        }

        $label = $this->label;
        if ( $value ) {
            $label = $value;
        }

        $output = '<a href="javascript;" class="mi2-editable '.$this->class.'" data-pk="'.$encounterId.'" data-title="edit" data-value="'.$value.'" data-rows="3" data-placeholder="'.$label.'" data-type="textarea">'.$label.'</a>';

        return $output;
    }
}
