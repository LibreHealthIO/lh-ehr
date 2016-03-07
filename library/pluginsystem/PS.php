<?php

namespace PluginSystem;

class PS
{
    /**
     * Plugin API
     *
     *
     */
    public static function init()
    {
        $system = PluginSystem::getInstance();
        if ( $system->isInitialized() === false ) {
            $system->init();
        }
    }

    public static function addAction( $key, $callback )
    {
        $system = PluginSystem::getInstance();
        $system->addAction( $key, $callback );
    }

    public static function doAction( $key, & $args = null )
    {
        $system = PluginSystem::getInstance();
        $system->doAction( $key, $args );
    }

    public static function registerPlugin( \Plugin\AbstractPlugin $plugin )
    {
        $system = PluginSystem::getInstance();
        $system->registerPlugin( $plugin );
    }

    public static function installPlugin( $name )
    {
        $system = PluginSystem::getInstance();
        $system->installPlugin( $name );
    }

    public static function activatePlugin( $name )
    {
        $system = PluginSystem::getInstance();
        $system->activatePlugin( $name );
    }

    public static function deactivatePlugin( $name )
    {
        $system = PluginSystem::getInstance();
        $system->deactivatePlugin( $name );
    }

}
