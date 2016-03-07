<?php

namespace Framework;

class App 
{
    protected $request = null;
    protected $viewer = null;
    protected $view = null;
    protected $layout = null;
    protected $applicationPath = '';
    protected $applicationUrl = '';
    protected $scriptPath = '';
    
    public function __construct()
    {
        $this->request = new Request();
        $this->viewer = new Viewer();
        $this->view = new View(); 
        $this->layout = new View();    
        $this->applicationPath = dirname( $_SERVER["SCRIPT_FILENAME"] );  
        $this->applicationUrl = dirname( $_SERVER['SCRIPT_NAME'] ); 
        $this->viewer->setApplicationUrl($this->applicationUrl);   
        $this->setScriptPath($this->applicationPath.DIRECTORY_SEPARATOR.'views');
    }

    public function run() 
    {
        $actionParam = $this->request->getParam('action', null);
        if ( $actionParam === null ) {
            throw new \Exception('No action parameter provided to bootstrap.');
        }
        
        $paramParts = explode('!', $actionParam);
        $controller = $paramParts[0];
        if ( empty($controller) ) {
            throw new \Exception('No controller specified.');
        }
        
        $action = $paramParts[1];
        if ( empty($action) ) {
            $action = 'index';
        }

        $controllerClassName = ucfirst( $controller )."Controller";
        $controllerInstance = new $controllerClassName();
        $controllerInstance->setRequest( $this->request );
        $controllerInstance->setApplicationUrl($this->applicationUrl);
        $controllerInstance->setView($this->view);

        $this->perform($controllerInstance, $action);
    }
    
    public function setScriptPath( $path )
    {
        $this->scriptPath = $path;
    }
    
    public function getScriptPath()
    {
        return $this->scriptPath;
    }
    
    public function getView()
    {
        return $this->view;
    }
    
    public function getLayout()
    {
        return $this->layout;
    }

    function perform( AbstractController $controller, $action )
    {
        $action_method = '_action_' . $action;
    
        // execute the default action if action is not found
        if ( method_exists( $controller, $action_method ) ) {
            $controller->$action_method();
        } else {
            error_log( "Could not find action method $action_method" );
            $controller->_action_default();
        }

        // If we have a layout, render the view inside the layout.
        // Otherwise, just render the view
        $layoutName = $controller->getLayoutScript();
        $viewName = $controller->getViewScript();
        
        // Check to see if we have a view name. If not, this probably an ajax request with no view associated (returns json or text)
        if ( $viewName ) {
            if ( $layoutName ) {
                $this->view->setViewScript( $this->getScriptPath() . DIRECTORY_SEPARATOR . $viewName );
                $this->layout->setViewScript( $this->getScriptPath() . DIRECTORY_SEPARATOR . $layoutName );
                $this->layout->content = $this->viewer->getHtml($this->view, $this->view->getAttributes());
                $this->viewer->render($this->layout, $this->layout->getAttributes());
            } else {
                $this->view->setViewScript( $this->getScriptPath() . DIRECTORY_SEPARATOR . $viewName );
                $this->viewer->render($this->view, $this->view->getAttributes());
            }
        }
        
        return true;
    }
    
    public function stripExtension( $filename )
    {
        // Strip the extension
        $parts = pathinfo( $filename );
        $name = str_replace( $parts['dirname'] . DIRECTORY_SEPARATOR, '', $filename );
        $name = str_replace( '.' . $parts['extension'], '', $name );
        return $name;
    }
}

