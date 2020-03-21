<?php

namespace Framework\DataTable\ColumnBehavior;

use Framework\DataTable\ColumnBehaviorIF;
use Framework\AbstractModel;

require_once 'ActiveElement.php';

class ActiveDatetime extends ActiveElement implements ColumnBehaviorIF
{
    protected $list = null;
    protected $name = null;
    protected $class = null;
    
    public function getOutput( $data )
    {
        $name = $this->name;
        $encounterId = $data['encounter'];
        $text = $value = "____-__-__ __:__";
        if ( !empty( $data[$name] ) &&
            $data[$name] != '____-__-__ __:__' ) {
            $value = $text = $data[$name];
        } else {
            $value = '';//date( 'Y-m-d H:m' );
        }


        $output = '<a class="mi2-editable '.$this->class.'" href="javascript;" data-type="combodate" data-viewformat="YYYY-MM-DD, HH:mm" data-format="YYYY-MM-DD HH:mm" data-template="YYYY-MM-DD HH:mm" name="'.$name.'" value="'.$value.'" data-pk="'.$encounterId.'">'.$text.'</a>';
        return $output;
    }
    
    public static function validateMysqlDate( $date ){
    if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9])(:([0-5][0-9]))?$/", $date, $matches)) {
        if (checkdate($matches[2], $matches[3], $matches[1])) { 
            return true; 
        } 
    } 
    return false; 
} 
    
    
    
    
    
    
    
}
