<?php

namespace Framework\DataTable\ColumnBehavior;

use Framework\AbstractModel;

class ActiveElement extends AbstractModel
{
    protected $tableId = '';
    protected $map = null;

    public function __construct( $args = null )
    {
        parent::__construct( $args );

        // If we are passed a list, use the list to populate our map
        if ( !empty( $this->list ) ) {
            // A list ID has been provided, so need to fetch the map
            $statement = "SELECT * FROM list_options WHERE list_id = ? ORDER BY seq DESC";
            $result = sqlStatement( $statement, array( $this->list ) );
            $this->map = array();
            while ( $row = sqlFetchArray( $result ) ) {
                $this->map[$row['option_id']] = $row['title'];
            }
        }
    }
    
    public function setTableId( $tableId )
    {
        $this->tableId = $tableId;
    }

    public function getMap()
    {
        return $this->map;
    }
}