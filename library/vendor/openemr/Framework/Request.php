<?php 

namespace Framework;

class Request
{
    protected $params = array();
    
    public function __construct()
    {
        $this->parseParams();
    }
    
    public function getParam( $key, $default = '' )
    {
        if ( isset( $this->params[$key] ) ) {
            return $this->params[$key];
        }
    
        return $default;
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    protected function parseParams()
    {
        foreach ( $_REQUEST as $key => $value ) {
            $this->params[$key] = $value;
        }
    }
}
