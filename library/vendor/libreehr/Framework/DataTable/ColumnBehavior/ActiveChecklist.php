<?php

namespace Framework\DataTable\ColumnBehavior;

use Framework\DataTable\ColumnBehaviorIF;
use Framework\AbstractModel;
use Framework\ListOptions;

require_once 'ActiveElement.php';

class ActiveChecklist extends ActiveElement implements ColumnBehaviorIF
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
        $value = "";
        $text = "None";
        if ( $data[$name] ) {
            $ar = json_decode( $data[$name] );
            $tar = array();
            $statement = "SELECT * FROM list_options WHERE list_id = ? ORDER BY seq DESC";
            $result = sqlStatement( $statement, array( $this->list ) );
            while ( $row = sqlFetchArray( $result ) ) {
                $tar[$row['option_id']] = $row['title'];
            }
            $value = "";
            $text = "";
            foreach ( $ar as $a ) {
                $value .= "$a,";
                $text .= "{$tar[$a]} <br>";
            }
        }

        $output = "";
        $output = '<a href="javascript;" name="'.$this->name.'"  class="mi2-editable '.$this->class.'" data-pk="'.$encounterId.'" data-url="'.$url.'" data-value="'.$value.'" data-title="edit" data-type="checklist">'.$text.'</a>';
        return $output;
    }
}
