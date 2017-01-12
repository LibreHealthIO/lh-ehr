<?php
namespace Framework\Plugin\Globals;

class Setting
{
    protected $label = null;
    protected $dataType = null;
    protected $default = null;
    protected $description = null;
    protected $isUserSetting = false;
    
    public function __construct( $label, $dataType, $default, $description, $isUserSetting = false )
    {
        $this->label = $label;
        $this->dataType = $dataType;
        $this->default = $default;
        $this->description = $description;
        $this->isUserSetting = $isUserSetting;
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

    public function isUserSetting()
    {
        return $this->isUserSetting;
    }
}
