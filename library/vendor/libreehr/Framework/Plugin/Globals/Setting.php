<?php
namespace Framework\Plugin\Globals;

class Setting
{
    protected $label = null;
    protected $dataType = null;
    protected $default = null;
    protected $description = null;
    
    public function __construct( $label, $dataType, $default, $description )
    {
        $this->label = $label;
        $this->dataType = $dataType;
        $this->default = $default;
        $this->description = $description;    
    }
    
    public function format()
    {
        return array(
        	xl( $this->label ),
            $this->dataType,
            $this->default,
            xl( $this->description )
        );
    }
}