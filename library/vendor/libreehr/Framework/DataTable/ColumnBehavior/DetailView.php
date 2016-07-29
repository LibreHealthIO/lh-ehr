<?php

namespace Framework\DataTable\ColumnBehavior;


use Framework\DataTable\ColumnBehaviorIF;

require_once 'ActiveElement.php';

class DetailView implements ColumnBehaviorIF
{
    protected $action = ''; // Action URL to call
    protected $keys = array(); // param key to pass
    
    public function __construct( $action, array $keys = null )
    {
        $this->action = $action;
        if ( $keys !== null ) {
            $this->keys = $keys;
        }
    }
    
    public function getOutput( $data )
    {
        $actionString = "{$this->action}&";
        foreach ( $this->keys as $key ) {
            $actionString .= "$key={$data[$key]}&";
        }
        return "<a class='column_behavior_details' href='$actionString'>Show</a>";
    }
}

