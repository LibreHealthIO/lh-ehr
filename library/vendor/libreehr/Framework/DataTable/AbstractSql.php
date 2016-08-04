<?php
namespace Framework\DataTable;

abstract class AbstractSql
{
    protected $statement = "";
    protected $resultSet = null;
    protected $searchFilters = array();
    protected $searchRequirements = array();
    protected $binds = array();
    protected $limit = null;
    protected $sortOrder = array();
    protected $columns = array();
    
    public function getColumns()
    {
        return $this->columns;
    }  

    public function setColumns( array $columns )
    {
        $this->columns = $columns;
    }
    
    public abstract function getStatement();
    
    public function getTotalCount()
    {
        $this->statement = $this->getStatement();
        $statement = "SELECT count(*) AS count FROM ($this->statement) AS my_table";
        $row = sqlQuery($statement);
        return $row['count'];
    }
    
    public function getFilteredCount()
    {
        $this->statement = $this->getStatement();
        $this->applySearchFilters();
        $statement = "SELECT count(*) AS count FROM ($this->statement) AS my_table";
        $row = sqlQuery($statement,$this->binds);
        return $row['count'];
    }
    
    public function addSearchFilter( SearchFilter $searchFilter, $requirement = false )
    {
        if ( $requirement === true ) {
            $this->searchRequirements[] = $searchFilter;
        } else {
            $this->searchFilters[] = $searchFilter;
        }
    }
    
    
    public function setLimit( Limit $limit )
    {
        $this->limit = $limit;
    }
    
    public function addSortOrder( SortOrder $sortOrder )
    {
        $this->sortOrder[] = $sortOrder;
    }
    
    private function setStatement( $statement, $binds = null )
    {
        $this->statement = $statement;
        if ( $binds !== null ) {
            $this->binds = $binds;
        }
    }
    
    protected function applySearchFilters()
    {
        $this->binds = array();
        if ( count($this->searchFilters) ) {
        
            // Check to see if we have a WHERE in our statement already
            if ( strpos( $this->statement, "WHERE" ) === false ) {
                $this->statement .= " WHERE ( ";
            } else {
                $this->statement .= " AND ( ";
            }
        
            $count = 0;
            foreach ( $this->searchFilters as $filter ) {
                if ( $filter instanceof SearchFilter ) {

                    if ( $filter->getType() == SearchFilter::TYPE_IS_NULL ) {
                        $this->statement .= $filter->getColumn() . " IS NULL ";
                    } else {
                        $keyword = $filter->getKeyword();
                        if ($filter->getType() == SearchFilter::TYPE_LIKE) {
                            $keyword = "%$keyword%";
                            $this->statement .= $filter->getColumn() . " LIKE ? ";
                        } else if ($filter->getType() == SearchFilter::TYPE_STRICT) {
                            $this->statement .= $filter->getColumn() . " = ? ";
                        }

                        if ($count < count($this->searchFilters) - 1) {
                            $this->statement .= " OR ";
                        }
                        $this->binds[] = $keyword;
                    }

                    $count++;
                }
            }
        
            $this->statement .= " ) ";
        }

        if ( count( $this->searchRequirements ) ) {
            if (strpos($this->statement, "WHERE") === false) {
                $this->statement .= " WHERE ( ";
            } else {
                $this->statement .= " AND ( ";
            }

            $count = 0;
            foreach ($this->searchRequirements as $filter) {
                if ($filter instanceof SearchFilter) {
                    if ( $filter->getType() == SearchFilter::TYPE_IS_NULL ) {
                        $this->statement .= " ( " . $filter->getColumn() . " IS NULL OR " . $filter->getColumn() ." = '' ) ";
                    } else {
                        $keyword = $filter->getKeyword();

                        if ($filter->getType() == SearchFilter::TYPE_LIKE) {
                            $keyword = "%$keyword%";
                            $this->statement .= $filter->getColumn() . " LIKE ? ";
                        } else if ($filter->getType() == SearchFilter::TYPE_STRICT) {
                            $this->statement .= $filter->getColumn() . " = ? ";
                        }

                        if ($count < count($this->searchRequirements) - 1) {
                            $this->statement .= " AND ";
                        }
                        $this->binds[] = $keyword;
                    }

                    $count++;
                }
            }

            $this->statement .= " ) ";
        }
        
    }

    public function getTableColumn( Column $column )
    {
        $parts = explode(".", $column->getData());
        $return = '';
        if ( count( $parts ) > 1 ) {
            $return = $parts[1];
        } else if ( $parts[0] !== null ) {
            $return = $parts[0];
        }

        return $return;
    }

    public function processRow( $row )
    {
        $result = array();
        foreach ( $this->getColumns() as $col ) {
            if ( $col instanceof Column ) {

                $behavior = $col->getBehavior();
                if ( $behavior instanceof ColumnBehaviorIF ) {
                    $result[$this->getTableColumn($col)]= $behavior->getOutput( $row );
                } else {
                    $temp = $row[$this->getTableColumn($col)];
                    $result[$this->getTableColumn($col)]= $temp;
                }
            }
        }
        return $result;
    }

    public function execute()
    {
        $this->statement = $this->getStatement();
        
        // Apply search filters to the statement
        $this->applySearchFilters();
        
        // Process order
        if ( count($this->sortOrder) > 0 ) {
            $count = 0;
            $this->statement .= " ORDER BY ";
            foreach ( $this->sortOrder as $sortOrder ) {
                if ( $sortOrder instanceof SortOrder ) {
                    $this->statement .= $sortOrder->getColumn()." ".$sortOrder->getDirection();
                    if ( $count < count( $this->sortOrder ) - 1 ) {
                        $this->statement .= ", ";
                    }
                    $count++;
                }
            }
        }
        
        // Process limit
        if ( $this->limit instanceof Limit ) {
            $this->statement .= " LIMIT ".$this->limit->start.", ".$this->limit->length;
        }
        error_log($this->statement);
        $this->resultSet = sqlStatement( $this->statement, $this->binds );
        return $this->resultSet;
    }
    
    public function fetchNext()
    {
        if ( $this->resultSet ) {
            return sqlFetchArray( $this->resultSet );
        }
        
        return false;
    }    
}
