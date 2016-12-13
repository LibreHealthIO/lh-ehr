<?php
namespace Framework;

/**
 *   Abstract Controller
 *
 * Copyright (C) 2013 Medical Information Integration
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package framework
 * @author  Ken Chapple <ken@mi-squared.com>
 * @author  Medical Information Integration, LLC
 * @link    http://librehealth.io
 **/

abstract class AbstractController 
{
    protected $layoutScript = null;
    protected $viewScript = null;    
    protected $request = null;
    protected $applicationUrl = null;
    protected $view = null;
    
    public function __construct()
    {
        
    }
    
    public function getBaseUrl()
    {
        return $this->applicationUrl;
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    public function setRequest( Request $request )
    {
        $this->request = $request;
    }

    public function _action_error() 
    {
        $this->view = "views/error.php";
    }

    /**
     * By default, controllers have no default action
     */
    function _action_default() 
    {
        $this->_action_error();
    }
    
    public function setApplicationUrl($url)
    {
        $this->applicationUrl = $url;
    }
    
    public function setView( $view ) 
    {
        $this->view = $view;
    }
   

    public function setViewScript( $view, $layout = null ) 
    {
        $this->viewScript = $view;
        $this->layoutScript = $layout;
    }
    
    public function getViewScript()
    {
        return $this->viewScript;
    }
    
    public function getLayoutScript()
    {
        return $this->layoutScript;
    }

    public function forward( $forward ) 
    {
        $this->view->_forward = $forward;
    }

    public function redirect( $redirect ) 
    {
        $this->view->_redirect = $redirect;
    }
}
