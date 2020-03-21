<?php
namespace Framework;

use Framework\AbstractModel;

class ListOptions extends AbstractModel
{   
    protected $list = null;
    protected $rows = array();
    protected $_options = array();
    protected $_default = '';
    
    public function init()
    {
        if ( $this->list ) {
            $this->_default = '';
            $this->_options = array();
            $statement = "SELECT * FROM list_options WHERE list_id = ? ORDER BY seq DESC";
            $result = sqlStatement( $statement, array( $this->list ) );
            while ( $row = sqlFetchArray( $result ) ) {
                $this->rows[]= $row;
                $this->_options[$row['option_id']] = $row['title'];
                if ( $row['is_default'] ) {
                    $this->_default = $row['option_id'];
                }
            }
        }
    }
    
    public function render()
    {
        foreach ( $this->getOptions() as $options ) {
            //
        }
    }

    public function getRows()
    {
        return $this->rows;
    }
    
    public function getOptions()
    {
        return $this->_options;
    }
    
    public function getDefault()
    {
        return $this->_default;
    }
}
