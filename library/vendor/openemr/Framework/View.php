<?php

namespace Framework;

class View extends AbstractModel implements ViewableIF
{
    protected $_viewScript = 'view.php';
    protected $_attributes = array();
    
    public function __set($key, $value)
    {
        $this->_attributes[$key] = $value;
    }
    
    public function getAttributes()
    {
        return $this->_attributes;
    }
    
    public function setViewScript( $script )
    {
        $this->_viewScript = $script;
    }
    
    public function getViewScript()
    {
        return $this->_viewScript;
    }
}