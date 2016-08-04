<?php
namespace Framework\DataTable;

use Framework\AbstractModel;
use Framework\Request;

/**
 * 
 * @author kchapple
 *
 *    Model of a sortable, searchable list.
 */
class DataTable extends AbstractModel
{
    protected $table = '';
    
    protected $tableId = '';
    protected $countSQL = '';
    protected $resultsUrl = '';
    protected $baseUrl = '';
    protected $columnHeadersJSON = ''; // JSON string representation of column headers array
    protected $iDisplayLength = 10;
    
    protected $sFirst = '';
    protected $sLast = '';
    protected $sNext = '';
    protected $sPrevious = '';
    
    protected $sEcho = 1;
    
    protected $sql = null;
    protected $rowClassFilter = null;

    protected $setPatientUrl = '';
    
    
    public function __construct( AbstractSql $sql, $tableId, $resultsUrl, RowClassFilterIF $rowClassFilter = null, $setPatientUrl = '' )
    {
        $this->setPatientUrl = $setPatientUrl;
        $this->rowClassFilter = $rowClassFilter;
        $this->sql = $sql;
        $this->resultsUrl = $resultsUrl;
        $this->tableId = $tableId;
        $this->baseUrl = ( $GLOBALS['webroot'] ) ? $GLOBALS['webroot'] : '';
        
        $this->sFirst = xla('First');
        $this->sLast = xla('Last');
        $this->sNext = xla('Next');
        $this->sPrevious = xla('Previous');
    }

    public function getEntry()
    {
        return $this->sql;
    }
    
    public function getResults( Request $request )
    {
        $results = new Results( array( 'rowClassFilter' => $this->rowClassFilter ) );
        $results->getResults( $this->sql, $request->getParams() );
        return $results->toJson();
    }
    
    public function getDisplayLength()
    {
        return $this->iDisplayLength;
    }
    
    public function getTableId()
    {
        return $this->tableId;
    }
    
    public function getResultsUrl()
    {
        return $this->resultsUrl;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function setResultsUrl( $resultsUrl )
    {
        $this->resultsUrl = $resultsUrl;
    }
    
    public function getColumns()
    {
        return $this->sql->getColumns();
    }
    
    public function toJson()
    {
        $json = array();
        foreach ( $this->sql->getColumns() as $col ) {
            if ( $col instanceof Column ) {
                $json[]= $col->toJson();
            }
            
        }
        $this->columnHeadersJSON = $json;
        
        return parent::toJson();
    }
    
    /**
     * Render the required javascript
     */
    public function renderJavascript()
    {
        $string = "";
        foreach ( $this->sql->getColumns() as $column ) {
           if ( $column->getBehavior() instanceof \ActiveElement ) {
               $activeElem = $column->getBehavior();
               $string .= $activeElem->getJavascript();
           }
        }
    }
}
