<?php
/**
 * Created by PhpStorm.
 * User: kchapple
 * Date: 3/31/16
 * Time: 1:24 PM
 */

use Framework\DataTable\ColumnBehavior\ActiveStatic;

class ColumnObjectEntity extends ActiveStatic
{
    public function getOutput( $data )
    {
        $name = $this->name;
        $value = "";
        if ( $data['object_type'] == 'patient' ) {
            $row = sqlQuery( "SELECT fname, lname FROM patient_data WHERE pid = ?", $data['object_entity'] );
            $value = $row['fname']." ".$row['lname'];
        } else if ( $data['object_type'] == 'tag' ) {
            $row = sqlQuery( "SELECT tag_name FROM tf_tags WHERE id = ?", $data['object_entity'] );
            $value = $row['tag_name'];
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