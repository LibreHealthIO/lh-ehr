<?php

namespace Framework;

abstract class AbstractModel
{
    protected $members = array();

    public function __construct( $args = null )
    {
        $this->members = array();
        if ( is_array( $args ) ) {
            foreach ( $args as $arg => $value ) {
                $member = $arg;
                $this->members[$member]= $arg;
                $this->{$member} = $value;
            }
        }
    }

    public function getMembers()
    {
        return $this->members;
    }
    
    public function toArray()
    {
        return get_object_vars( $this );
    }
    
    public function toJson()
    {
        $vars = $this->toArray();
        return json_encode( $vars );
    }
    
    public function methodsToArray()
    {
        $reflection = new \ReflectionClass( get_class( $this ) );
        $methods = $reflection->getMethods( \ReflectionMethod::IS_PUBLIC );
        
        $data = array();
        foreach ( $methods as $method ) {
            $methodName = $method->getName();
            if ( strpos( $methodName, "get" ) === 0 ) {
                $property = str_replace( "get", "", $methodName );
                $data[$property] = $this->{$methodName}();
            }
            
        }
         
        return $data;
    }
    
    public function methodsToJson()
    {
        return json_encode( $this->methodsToArray() );
    }
}

