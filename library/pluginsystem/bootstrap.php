<?php
/**
 * Define paths to libreehr and vendor dir for use in plugins
 */
if ( !defined( 'OPENEMR_DIRECTORY' ) ) {
    define( 'OPENEMR_DIRECTORY', realpath( __DIR__.'/../../' ) );
}

set_include_path( get_include_path() . PATH_SEPARATOR . OPENEMR_DIRECTORY );

if ( !defined( 'PLUGIN_SYSTEM_DIRECTORY' ) ) {
    define( 'PLUGIN_SYSTEM_DIRECTORY', realpath( __DIR__ ) );
}

set_include_path( get_include_path() . PATH_SEPARATOR . PLUGIN_SYSTEM_DIRECTORY );

if ( !defined( 'VENDOR_DIRECTORY' ) ) {
    define( 'VENDOR_DIRECTORY', realpath( __DIR__.'/../vendor' ) );
}

set_include_path( get_include_path() . PATH_SEPARATOR . VENDOR_DIRECTORY );

include_once VENDOR_DIRECTORY . '/autoload.php';

if ( method_exists( '\PluginSystem\PS', 'init' ) ) {
    \PluginSystem\PS::init();
} else {
    error_log( "Could not initialize the plugin system" );
}
