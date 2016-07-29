<?php

namespace Framework\DataTable\ColumnBehavior;

use Framework\DataTable\ColumnBehaviorIF;

require_once 'ActiveElement.php';

class ColorElement extends ActiveElement implements ColumnBehaviorIF
{
    protected $key = null;
    protected $name = "color";
    protected $list = null;
    protected $class = null;

    public function getOutput($data)
    {
        $name = $this->name;
        $encounterId = $data['encounter'];
        $value = "None";
        $dataValue = '';
        if ($data[$name]) {
            if (is_array($this->getMap())) {
                $map = $this->getMap();
                // A map has been provided by the element's constructor
                $value = $map[$data[$name]];
                $dataValue = $data[$name];
            }
        }

        $output = '<a href="javascript;" name="' . $this->name . '" class="color-picker ' . $this->class . '" data-value="' . $dataValue . '" data-title="edit" data-type="select">' . $value . '</a>';

        return $output;
    }
}

