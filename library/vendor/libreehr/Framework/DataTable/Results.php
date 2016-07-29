<?php
namespace Framework\DataTable;

use Framework\AbstractModel;

class Results extends AbstractModel
{
    protected $results = array();

    protected $sEcho = 1;
    protected $totalItems = 0;
    protected $filteredTotal = 0;
    
    /**
     * 
     * @var RowClassFilterIF $rowClassFilter
     */
    protected $rowClassFilter = null;
    
    /**
     * Find a column by it's index
     * @param int $index
     */
    public function findColumn( $columns, $index )
    {
        foreach ( $columns as $key => $col ) {
            if ( $key == $index ) {
                return $col;
            }
        }
    
        return null;
    }
    
    public function getResults( AbstractSql $sql, array $options = null )
    {
        $this->sEcho = intval( $options['sEcho'] );
        $aColumns = explode( ',', $options['sColumns'] );
        
        if ( isset( $options['iDisplayStart'] ) && $options['iDisplayLength'] != '-1' ) {
            $limit = new Limit( intval( $options['iDisplayStart'] ), intval( $options['iDisplayLength'] ) );
            $sql->setLimit( $limit );
        }
        
        // Process sort order
        for ( $i = 0; $i < intval( $options['iSortingCols'] ); ++$i ) {
            $iSortCol = intval( $options["iSortCol_$i"] );
            if ( $options["bSortable_$iSortCol"] == "true" ) {
                $sSortDir = mysql_real_escape_string( $options["sSortDir_$i"] ); // ASC or DESC
                $order = ( $sSortDir == 'desc' ) ? SortOrder::SORT_DESC : SortOrder::SORT_ASC;
                $column = $this->findColumn( $sql->getColumns(), $iSortCol );
                if ( $column instanceof Column ) {
                    if ( $column->isOrderable() ) {
                        $sortOrder = new SortOrder( $column->getData(), $order );
                        $sql->addSortOrder( $sortOrder );
                    }
                } else {
                    error_log( "Column not found at index $iSortCol" );
                }
            }
        }

        // KCC hack to always sort by last updated datetime after other sort parameters
//        $sortOrder = new SortOrder( 'last_updated_datetime', SortOrder::SORT_DESC );
//        $sql->addSortOrder( $sortOrder );

        if ( !empty( $options['sSearch'] ) ) {
        
            foreach ( $sql->getColumns() as $column ) {
                if ( $column instanceof Column ) {
                    if ( $column->isSearchable() ) {
                        $searchTerm = $options['sSearch'];
                        $searchFilter = new SearchFilter( $column->getData(), $searchTerm );
                        $sql->addSearchFilter( $searchFilter );
                    }
                }
            }
        }

        // Filter individual columns
        $columnIndex = 0;
        foreach ( $sql->getColumns() as $column ) {

            if ( $column instanceof Column &&
                $column->isSearchable() &&
                $options["sSearch_$columnIndex"] ) {
                $searchTerm = $options["sSearch_$columnIndex"];

                // By default, the type is "like"
                // but if we have a map, make the term match exactly using strict
                $type = SearchFilter::TYPE_LIKE;
                $map = null;
                if ( $column->getBehavior() &&
                    ( $map = $column->getBehavior()->getMap() ) &&
                    is_array( $map ) ) {

                    if ( $options["sSearch_$columnIndex"] === 'NULL' ) {
                        // If the search term contains NULL keyword, then we search for values with sql "IS NULL"
                        $type = SearchFilter::TYPE_IS_NULL;
                    } else {
                        $type = SearchFilter::TYPE_STRICT;
                    }
                }
                $searchFilter = new SearchFilter( $column->getData(), $searchTerm, $type );
                $sql->addSearchFilter( $searchFilter, true );
            }

            $columnIndex++;
        }
        
        $this->sEcho = $options['sEcho'];
        
        $count = 0;
        $results = array();
        $sql->execute();
        while ( $row = $sql->fetchNext() ) {
            $result = $sql->processRow( $row );
            $results[]= $result;
            $count++;
        }
        // Get total number of rows in the table.
        $this->totalItems = $sql->getTotalCount();
        
        // Get total number of rows in the table after filtering.
        $this->filteredTotal = $sql->getFilteredCount();
        
        $this->results = $results;
        return $this->results;
    }
    
    public function toJson()
    {
        $output = array(
            "sEcho" => $this->sEcho,
            "iTotalRecords" => $this->totalItems,
            "iTotalDisplayRecords" => $this->filteredTotal,
            "aaData" => array()
        );
        
        $count = 0;
        foreach ( $this->results as $row ) {
            $values = array_values( $row );
            $rowClass = '';
            if ( $this->rowClassFilter instanceof RowClassFilterIF ) {
                $rowClass = $this->rowClassFilter->calculateRowClass( $row );
            }
            $arow = array( 'DT_RowId' => 'row-'.$count, 'DT_RowClass' => $rowClass );
            foreach ( $values as $val ) {
                $arow[]= stripslashes( $val );
            }
            $output['aaData'][]= $arow;
            $count++;
        }
        
        $json = json_encode( $output );
        return $json;
    }
}