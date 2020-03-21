<?php

namespace PluginSystem;

use \Framework\Plugin\Migration\Migration;
use \Framework\Plugin\Migration\Migrate;

abstract class AbstractPlugin implements PluginActivationIF, PluginDeactivationIF, PluginInstallIF
{
    public abstract function migrationsLocation();
    public abstract function startFileLocation();
    public abstract function pluginName();
    
    /**
     * Get an array of available migrations for this plugin
     * 
     * @return multitype:string
     */
    public function getMigrations()
    {
        $migrate = new Migrate();
        $migrationsRun = $migrate->getMigrationsRun();
        
        $system = PluginSystem::getInstance();
        $location = $this->migrationsLocation();
        $migrations = array();
        if ( file_exists( "$location" ) ) {
            foreach ( glob( "$location/*.php" ) as $migrationFile ) {
                $filename = basename( $migrationFile );
                if ( !isset( $migrationsRun[$filename] ) ) {
                    include_once "$location/$filename";
                    $migrations[]= $filename;
                }
            }
        }
        
        return $migrations;
    }
    
    public function migrate()
    {
        $migrate = new Migrate();
        
        $lastBatchNumber = $migrate->getLastBatchNumber();
        $nextBatchNumber = $lastBatchNumber + 1;
        
        // All of the migration files have been collected and sorted
        // Iterate over them and execute the directives
        $migrated = array();
        foreach ( $this->getMigrations() as $migration ) {
            $class = str_replace( ".php", "", $migration );
            $mObj = new $class();
            if ( $mObj instanceof Migration ) {
                $directive = $mObj->up();
                $migrate->upgradeFromDirective( $directive );
                $migrate->insertMigration( $migration, $nextBatchNumber );
                $migrated[]= $migration;
            }
        }
    }

    public function update()
    {
        $this->migrate();
    }
    
    public function install()
    {
        $this->migrate();
    }
    
    public function activate()
    {
              
    }
    
    public function deactivate()
    {

    }
}
