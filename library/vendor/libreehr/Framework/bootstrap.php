<?php
/**
 * Define paths to libreehr and vendor dir for use in plugins
 */
if ( !defined( 'LIBREEHR_DIRECTORY' ) ) {
    define( 'LIBREEHR_DIRECTORY', realpath( __DIR__.'/../' ) );
}

set_include_path( get_include_path() . PATH_SEPARATOR . LIBREEHR_DIRECTORY );

if ( !defined( 'Mi2_DIRECTORY' ) ) {
    define( 'Mi2_DIRECTORY', realpath( __DIR__ ) );
}

set_include_path( get_include_path() . PATH_SEPARATOR . Mi2_DIRECTORY );

if ( !defined( 'VENDOR_DIR' ) ) {
    define( 'VENDOR_DIR', realpath( __DIR__.'/system' ) );
}

set_include_path( get_include_path() . PATH_SEPARATOR . SYSTEM_DIRECTORY );

include_once SYSTEM_DIRECTORY . '/autoload.php';
if ( method_exists( '\Framework\Plugin\PS', 'init' ) ) {
    \Framework\Plugin\PS::init();
} else {
    error_log( "Could not initialize the plugin system" );
}
