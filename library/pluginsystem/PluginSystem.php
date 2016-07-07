<?php

namespace PluginSystem;

/**
 * plugin API and plugin system
 *
 * Copyright (C) 2015 Medical Information Integration <info@mi-squared.com>
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
 * @package plugin
 * @author  Ken Chapple <ken@mi-squared.com>
 * @link    http://www.mi-squared.com
 */

require_once(__DIR__ . "/helpers.php");

/**
 *  Implementation of plugin system
 *
 */
class PluginSystem
{
    protected $isInitialized = false;
    protected $pluginDirs = array();
    protected $actions = array();
    protected $components = array();
    protected $plugins = array();
    protected $activeModules = array();

    /**
     * Returns the PluginSystem instance of this class.
     *
     * @staticvar Singleton $instance The PluginSystem instances of this class.
     *
     * @return Singleton The PluginSystem instance.
     */
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
            $instance->init();
        }

        return $instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * PluginSystem via the `new` operator from outside of this class.
     */
    protected function __construct()
    {

    }

    public function isInitialized()
    {
        return $this->isInitialized;
    }

    public function init()
    {
        // Get the plugin directory
        $composerJsonPath = realpath( __DIR__."/../composer.json" );
        $composerJson = file_get_contents( $composerJsonPath );
        if ( $composerJson === false ) {
            throw new \Exception( "$composerJsonPath/composer.json is not readable" );
        }
        $composer = json_decode( $composerJson, true );

        // Start explicitly defined plugins
        foreach ( $composer['plugins'] as $explicitPlugin ) {
            $location = OPENEMR_DIRECTORY."/".$explicitPlugin;
            if ( file_exists( $location."/start.php" ) ) {
                include_once "$location/start.php";
                $this->doAction( 'update_plugin', $location );
            } else {
                // No start file? Probaly mispelled or forgotten
                error_log("No start.php file found for openemr-plugin $location");
            }
        }

        $pluginDirs = $composer['plugin-dirs'];
        $this->pluginDirs = $pluginDirs;
        // Search for installed plugins
        // Run their start.php scripts
        foreach ( $this->pluginDirs as $pluginDir ) {
            foreach ( glob( OPENEMR_DIRECTORY."/".$pluginDir . "/*" ) as $location ) {
                if ( file_exists( "$location/start.php" ) ) {
                    include_once "$location/start.php";
                    $this->doAction( 'update_plugin', $location );
                } else {
                    // No start file? Probaly mispelled or forgotten
                    error_log("No start.php file found for openemr-plugin $location");
                }
            }

        }

        // Notify observers that the plugins have all been started
        $this->doAction( 'plugins_started', $components );
    }

    public function getPluginPath( $name )
    {
        $subdir = "$this->pluginDir/$name";
        return $subdir;
    }

    public function pluginIsActive( $name )
    {
        foreach ( $this->activeModules as $activeModule ) {
            if ( $name == $activeModule['directory'] ) {
                return true;
            }
        }

        return false;
    }

    public function registerPlugin( $plugin )
    {
        $this->plugins[$plugin->getName()]= $plugin;
    }

    public function updatePlugin( $name )
    {
        $subdir = $this->getPluginPath( $name );
        if ( file_exists( "$subdir/start.php" ) ) {
            include_once "$subdir/start.php";
            $this->doAction( 'update_plugin', $name );
        }
    }

    public function installPlugin( $name )
    {
        $subdir = $this->getPluginPath( $name );
        if ( file_exists( "$subdir/start.php" ) ) {
            include_once "$subdir/start.php";
            $this->doAction( 'install_plugin', $name );
        }
    }

    public function activatePlugin( $name )
    {
        $subdir = $this->getPluginPath( $name );
        if ( file_exists( "$subdir/start.php" ) ) {
            include_once "$subdir/start.php";
            $this->doAction( 'activate_plugin', $name );
        }
    }

    public function deactivatePlugin( $name )
    {
        $subdir = $this->getPluginPath( $name );
        if ( file_exists( "$subdir/start.php" ) ) {
            include_once "$subdir/start.php";
            $this->doAction( 'deactivate_plugin', $name );
        }
    }

    public function addAction( $actionKey, $callback, $priority = 0 )
    {
        if ( !isset($this->actions[$actionKey]) ) {
            $this->actions[$actionKey] = array();
        }
        $action = new \stdClass();
        $action->callback = $callback;
        $action->priority = $priority;
        $this->actions[$actionKey][] = $action;
    }

    /**
     * @param $actionKey
     * @param null $args
     * @return mixed return value after all filters/hooks have been applied
     */
    public function doAction( $actionKey, & $args = null )
    {
        $actions = $this->actions[$actionKey];
        if ( count( $actions ) ) {
            // sort actions by priority
            usort( $actions, array( "\\PluginSystem\\PluginSystem", "comparePriority" ) );
            foreach ( $actions as $action ) {
                $callback = $action->callback;
                $ret = $callback( $args, $actionKey );
                ob_flush();
            }
        }

        return $ret;
    }

    public static function comparePriority( $a, $b )
    {
        if ( $a->priority == $b->priority ) {
            return 0;
        }

        return ( $a->priority > $b->priority ) ? +1 : -1;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * PluginSystem instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the PluginSystem
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}
