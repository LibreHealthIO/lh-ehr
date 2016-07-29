<?php
namespace Framework\Plugin\UserGroup;

class UserGroupText implements UserGroupFieldIF
{
    protected $name = null;
    protected $label = null;
    protected $value = null;
    protected $style = "width: 120px";

    public function __construct( $name, $label, $value = '' )
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
    }
    
    public function renderUserAdminAdd()
    {
        echo "<input type='text' name='$this->name' id='$this->name' style='$this->style;' value='$this->value'>";
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function getValue()
    {
        return $this->value;
    }
}
