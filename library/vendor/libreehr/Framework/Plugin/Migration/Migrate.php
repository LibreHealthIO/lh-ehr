<?php
namespace Framework\Plugin\Migration;

use Plugin;

require_once LIBREEHR_DIRECTORY."/library/sql_upgrade_fx.php";
        
class Migrate
{
    protected $migrations = array();

    public function __construct()
    {
        // Create migrations table if it doesn't exist
        $this->createMigrationsTable();
    }
    
    /**
     * Upgrade or patch the database with a selected upgrade/patch file.
     *
     * The following "functions" within the selected file will be processed:
     *
     * #IfNotTable
     * argument: table_name
     * behavior: if the table_name does not exist, the block will be executed
     *
     * #IfTable
     * argument: table_name
     * behavior: if the table_name does exist, the block will be executed
     *
     * #IfMissingColumn
     * arguments: table_name colname
     * behavior: if the table exists but the column does not, the block will be executed
     *
     * #IfNotColumnType
     * arguments: table_name colname value
     * behavior: If the table table_name does not have a column colname with a data type equal to value, then the block will be executed
     *
     * #IfNotRow
     * arguments: table_name colname value
     * behavior: If the table table_name does not have a row where colname = value, the block will be executed.
     *
     * #IfNotRow2D
     * arguments: table_name colname value colname2 value2
     * behavior: If the table table_name does not have a row where colname = value AND colname2 = value2, the block will be executed.
     *
     * #IfNotRow3D
     * arguments: table_name colname value colname2 value2 colname3 value3
     * behavior: If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3, the block will be executed.
     *
     * #IfNotRow4D
     * arguments: table_name colname value colname2 value2 colname3 value3 colname4 value4
     * behavior: If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3 AND colname4 = value4, the block will be executed.
     *
     * #IfNotRow2Dx2
     * desc: This is a very specialized function to allow adding items to the list_options table to avoid both redundant option_id and title in each element.
     * arguments: table_name colname value colname2 value2 colname3 value3
     * behavior: The block will be executed if both statements below are true:
     * 1) The table table_name does not have a row where colname = value AND colname2 = value2.
     * 2) The table table_name does not have a row where colname = value AND colname3 = value3.
     *
     * #IfRow2D
     * arguments: table_name colname value colname2 value2
     * behavior: If the table table_name does have a row where colname = value AND colname2 = value2, the block will be executed.
     *
     * #IfIndex
     * desc: This function is most often used for dropping of indexes/keys.
     * arguments: table_name colname
     * behavior: If the table and index exist the relevant statements are executed, otherwise not.
     *
     * #IfNotIndex
     * desc: This function will allow adding of indexes/keys.
     * arguments: table_name colname
     * behavior: If the index does not exist, it will be created
     *
     * #IfNotMigrateClickOptions
     * Custom function for the importing of the Clickoptions settings (if exist) from the codebase into the database
     *
     * #EndIf
     * all blocks are terminated with a #EndIf statement.
     *
     * @param string $directive
     *            String containing one or more SQL directives
     */
    public function upgradeFromDirective( $directive )
    {
        global $webserver_root;
        $query = "";
        $line = "";
        $skipping = false;
        $messages = array();
        
        foreach ( preg_split('/((\r?\n)|(\r\n?))/', $directive ) as $line) {
            $line = rtrim( $line, ' \t' );
            
            if ( preg_match( '/\s*--/', $line ) )
                continue;
            if ( $line == "" )
                continue;
            
            $test = preg_match( '/#IfMissingColumn\s+(\S+)\s+(\S+)/', $line, $matches );
            
            if ( preg_match( '/#IfNotTable\s+(\S+)/', $line, $matches ) ) {
                $skipping = tableExists( $matches[1] );
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfTable\s+(\S+)/', $line, $matches ) ) {
                $skipping = ! tableExists( $matches[1] );
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfMissingColumn\s+(\S+)\s+(\S+)/', $line, $matches ) ) {
                if ( tableExists( $matches[1] ) ) {
                    $skipping = columnExists( $matches[1], $matches[2] );
                } else {
                    // If no such table then the column is deemed not "missing".
                    $skipping = true;
                }
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfNotColumnType\s+(\S+)\s+(\S+)\s+(\S+)/', $line, $matches ) ) {
                if ( tableExists( $matches[1] ) ) {
                    $skipping = columnHasType( $matches[1], $matches[2], $matches[3] );
                } else {
                    // If no such table then the column type is deemed not "missing".
                    $skipping = true;
                }
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfIndex\s+(\S+)\s+(\S+)/', $line, $matches ) ) {
                if ( tableExists( $matches[1] ) ) {
                    // If no such index then skip.
                    $skipping = ! tableHasIndex( $matches[1], $matches[2] );
                } else {
                    // If no such table then skip.
                    $skipping = true;
                }
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfNotIndex\s+(\S+)\s+(\S+)/', $line, $matches ) ) {
                if ( tableExists( $matches[1] ) ) {
                    $skipping = tableHasIndex( $matches[1], $matches[2] );
                } else {
                    // If no such table then the index is deemed not "missing".
                    $skipping = true;
                }
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfNotRow\s+(\S+)\s+(\S+)\s+(.+)/', $line, $matches ) ) {
                if ( tableExists( $matches[1] ) ) {
                    $skipping = tableHasRow( $matches[1], $matches[2], $matches[3] );
                } else {
                    // If no such table then the row is deemed not "missing".
                    $skipping = true;
                }
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfNotRow2D\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(.+)/', $line, $matches ) ) {
                if ( tableExists( $matches[1] ) ) {
                    $skipping = tableHasRow2D( $matches[1], $matches[2], $matches[3], $matches[4], $matches[5] );
                } else {
                    // If no such table then the row is deemed not "missing".
                    $skipping = true;
                }
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfNotRow3D\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(.+)/', $line, $matches ) ) {
                if ( tableExists( $matches[1] ) ) {
                    $skipping = tableHasRow3D( $matches[1], $matches[2], $matches[3], $matches[4], $matches[5], $matches[6], $matches[7] );
                } else {
                    // If no such table then the row is deemed not "missing".
                    $skipping = true;
                }
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfNotRow4D\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(.+)/', $line, $matches ) ) {
                if ( tableExists( $matches[1] ) ) {
                    $skipping = tableHasRow4D( $matches[1], $matches[2], $matches[3], $matches[4], $matches[5], $matches[6], $matches[7], $matches[8], $matches[9] );
                } else {
                    // If no such table then the row is deemed not "missing".
                    $skipping = true;
                }
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfNotRow2Dx2\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(.+)/', $line, $matches ) ) {
                if ( tableExists( $matches[1] ) ) {
                    // If either check exist, then will skip
                    $firstCheck = tableHasRow2D( $matches[1], $matches[2], $matches[3], $matches[4], $matches[5] );
                    $secondCheck = tableHasRow2D( $matches[1], $matches[2], $matches[3], $matches[6], $matches[7] );
                    if ( $firstCheck || $secondCheck ) {
                        $skipping = true;
                    } else {
                        $skipping = false;
                    }
                } else {
                    // If no such table then the row is deemed not "missing".
                    $skipping = true;
                }
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfRow2D\s+(\S+)\s+(\S+)\s+(\S+)\s+(\S+)\s+(.+)/', $line, $matches ) ) {
                if ( tableExists( $matches[1] ) ) {
                    $skipping = ! (tableHasRow2D( $matches[1], $matches[2], $matches[3], $matches[4], $matches[5] ));
                } else {
                    // If no such table then should skip.
                    $skipping = true;
                }
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#IfNotMigrateClickOptions/', $line ) ) {
                if ( tableExists( "issue_types" ) ) {
                    $skipping = true;
                } else {
                    // Create issue_types table and import the Issue Types and clickoptions settings from codebase into the database
                    clickOptionsMigrate();
                    $skipping = false;
                }
                if ( $skipping )
                    $messages[]= "<font color='green'>Skipping section $line</font><br />\n";
            } else if ( preg_match( '/#EndIf/', $line ) ) {
                $skipping = false;
            }
            
            if ( preg_match( '/\s*#/', $line ) )
                continue;
            if ( $skipping )
                continue;
            
            $query = $query . $line;
            if ( substr( $query, - 1 ) == ';' ) {
                $query = rtrim( $query, ';' );
                $messages[]= "$query<br />\n";
                if ( ! sqlStatement( $query ) ) {
                    $messages[]= "<font color='red'>The above statement failed: " . mysql_error() . "<br />Upgrading will continue.<br /></font>\n";
                }
                $query = '';
            }
        }
    } // end function
    
    public function createMigrationsTable()
    {
        $statement = "CREATE TABLE IF NOT EXISTS `migrations` (
            `migration` varchar(255),
            `batch` int )";
        return sqlQuery( $statement );
    }
    
    public function getMigrationsRun()
    {
        $statement = "SELECT * FROM `migrations` ORDER BY `batch`";
        $result = sqlStatement( $statement );
        $migrationsRun = array();
        while ( $row = sqlFetchArray( $result ) ) {
            $migrationsRun[$row['migration']] = $row['batch'];
        }
        
        return $migrationsRun;
    }
    
    public function getLastBatchNumber()
    {
        $statement = "SELECT max(batch) as last_batch_number FROM `migrations`";
        $result = sqlQuery( $statement );
        return $result['last_batch_number'];
    }
    
    /**
     * 
     * @param unknown $componenets
     * 
     * This funciton is called automatically after all plugins have been started
     * because in start.php, this object is registered as an action observer, and
     * subscribes to the plugins_started action
     * 
     * I find the migrations that are pending to be run
     * 
     */
    public function onPluginsStarted( $components )
    {
        $migrationsRun = $this->getMigrationsRun();

        // Load all of the migration files in the directories of installed components
        $vendorDir = realpath( __DIR__."/../../../../../vendor" );
        $migrations = array();
        foreach ( $components as $component ) {
            $subdir = $component->name;
            $location = "$vendorDir/$subdir";
            if ( file_exists( "$location/migrations" ) ) {
                foreach ( glob( "$location/migrations/*.php" ) as $migrationFile ) {
                    $filename = basename( $migrationFile );
                    if ( !isset( $migrationsRun[$filename] ) ) {
                        require_once "$location/migrations/$filename";
                        $migrations[]= $filename;
                    }
                }
            }
        }
        
        if ( count( $migrations ) ) {            
            // Sort the migrations by date
            sort( $migrations );
        }
        
        $this->migrations = $migrations;
    }
    
    public function insertMigration( $migration, $batch )
    {
        $statement = "INSERT INTO `migrations` ( `migration`, `batch` ) VALUES ( ?, ? )";
        return sqlInsert( $statement, array( $migration, $batch ) );
    }
    
    public function migrate( $direction )
    {
        if ( count( $this->migrations ) ) {
            // Get next batch number
            $lastBatchNumber = $this->getLastBatchNumber();
            $nextBatchNumber = $lastBatchNumber + 1;
            
            // All of the migration files have been collected and sorted
            // Iterate over them and execute the directives
            $migrated = array();
            foreach ( $this->migrations as $migration ) {
                $class = str_replace( ".php", "", $migration );
                $mObj = new $class();
                if ( $mObj instanceof Migration ) {
                    $directive = $mObj->{$direction}();
                    $this->upgradeFromDirective( $directive );
                    $migrated[]= $migration;
                }
            }
            
            $this->insertMigrations( $migrated, $nextBatchNumber );
        }
    }
}


