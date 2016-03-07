<?php
/**
* Plugin API
*
*
*/

function add_action( $key, $callback )
{
    $system = \PluginSystem\PluginSystem::getInstance();
    $system->addAction( $key, $callback );
}

function do_action( $key, & $args = null )
{
    $system = \PluginSystem\PluginSystem::getInstance();
    return $system->doAction( $key, $args );
}

function register_plugin( \PluginSystem\AbstractPlugin $plugin )
{
    $system = \PluginSystem\PluginSystem::getInstance();
    $system->registerPlugin( $plugin );
}

function install_plugin( $name )
{
    $system = \PluginSystem\PluginSystem::getInstance();
    $system->installPlugin( $name );
}

function activate_plugin( $name )
{
    $system = \PluginSystem\PluginSystem::getInstance();
    $system->activatePlugin( $name );
}

function deactivate_plugin( $name )
{
    $system = \PluginSystem\PluginSystem::getInstance();
    $system->deactivatePlugin( $name );
}
