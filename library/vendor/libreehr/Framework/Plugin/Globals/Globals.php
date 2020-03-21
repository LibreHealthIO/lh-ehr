<?php
namespace Framework\Plugin\Globals;

class Globals
{
    protected $globals_metadata = null;
    protected $user_specific_globals = null;
    protected $user_specific_tabs = null;
    
    public function __construct( $args )
    {
        $this->globals_metadata = $args['global_metadata'];
        $this->user_specific_globals = $args['user_specific_globals'];
        $this->user_specific_tabs = $args['user_specific_tabs'];
    }

    public function save()
    {
        global $GLOBALS_METADATA, $USER_SPECIFIC_GLOBALS, $USER_SPECIFIC_TABS;
        $GLOBALS_METADATA = $this->globals_metadata;
        $USER_SPECIFIC_GLOBALS = $this->user_specific_globals;
        $USER_SPECIFIC_TABS = $this->user_specific_tabs;
    }
    
    public function appendToSection( $section, $key, Setting $global )
    {
        $this->globals_metadata[$section][$key] = $global->format();
        if ( $global->isUserSetting() ) {
            $this->user_specific_globals[]= $key;
        }
    } 

    public function getData()
    {
        return $this->globals_metadata;
    }
}
