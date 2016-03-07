<?php
namespace Framework\Plugin\Globals;

class Globals
{
    protected $data = null;
    
    public function __construct( $data )
    {
        $this->data = $data;
    }
    
    public function appendToSection( $section, $key, Setting $global )
    {
        $this->data[$section][$key] = $global->format();
    } 

    public function getData()
    {
        return $this->data;
    }
}
