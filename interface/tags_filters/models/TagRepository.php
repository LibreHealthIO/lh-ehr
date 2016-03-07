<?php
/**
 * Created by PhpStorm.
 * User: kchapple
 * Date: 3/9/16
 * Time: 2:07 PM
 */

use Framework\ListOptions;

class TagRepository
{
    public function create( $args )
    {

        $fields = array( 'created_at', 'created_by', 'updated_at', 'updated_by',
            'tag_name', 'tag_color' );
        $sql = "INSERT INTO tf_tags ( ";
        $count = 0;
        $valuesString = "";
        $binds = array();
        foreach ( $fields as $field ) {
            $binds[$field]= isset( $args[$field] ) ? $args[$field] : '';
            $sql.= $field;
            $valuesString.= "?";
            if ( $count < count( $fields ) - 1 ) {
                $sql.= ", ";
                $valuesString.= ", ";
            }
            $count++;
        }
        $sql .= " ) VALUES ( $valuesString )";

        $now = date( 'Y-m-d H:i:s' );
        $binds['created_at'] = $now;
        $binds['created_by'] = $_SESSION['authUser'];
        $binds['updated_at'] = $now;
        $binds['updated_by'] = $_SESSION['authUser'];

        $result = sqlInsert( $sql, array_values( $binds ) );
        return $result;

    }

    public static function fetchAll()
    {
        $sql = "SELECT T.id as tag_id, T.created_at, T.created_by, T.updated_at, T.updated_by, T.tag_name, T.tag_color
        FROM tf_tags T";
        $colorMap = self::getColorMap();
        $result = sqlStatement( $sql );
        $tags = array();
        while ( $row = sqlFetchArray( $result ) ) {
            $tag = new Tag( $row );
            $tag->hex_color = $colorMap[$tag->tag_color];
            $tags[]= $tag;
        }
        return $tags;
    }

    public static function getColorMap()
    {
        $listOptions = new ListOptions( array( 'list' => 'ACLT_Tag_Colors' ) );
        $listOptions->init();
        $map = array();
        if ( count( $listOptions->getRows() ) > 0 ) {
            foreach ( $listOptions->getRows() as $row ) {
                $color = $row['notes'];
                $map[$row['option_id']]= $color;
            }
        }
        return $map;
    }

    public function getColorOptions()
    {
        $listOptions = new ListOptions( array( 'list' => 'ACLT_Tag_Colors' ) );
        $listOptions->init();
        $options = array();
        if ( count( $listOptions->getRows() ) > 0 ) {
            foreach ( $listOptions->getRows() as $row ) {
                $color = $row['notes'];
                $options[]= array( 'value' => $row['option_id'], 'text' => $row['title'], 'color' => $color );
            }
        }
        return $options;
    }

    public function getColorOptionsJson()
    {
        echo json_encode( $this->getColorOptions() );
    }
}