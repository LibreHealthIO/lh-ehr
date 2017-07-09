<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

/**
 * This will create a command to create database. 
 * @author Priyanshu Sinha <pksinha217@gmail.com>
 */


class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * @var string
     */
    protected $signature = 'make:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use this command to create database. Note that it will create the database configired in .env file. Self made command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    
    /**
     * Function to create PDO connection and return it.
     * @param host name
     * @param port number
     * @param database user
     * @param database password
     * @return PDO connection.
     */
    private function getPDO($host, $port, $user_name, $db_password)
    {
	try
	{
		$connection = new PDO(sprintf('mysql:host = %s;port=%d', $host, $port), $user_name, $db_password);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $connection;
	}
	catch(PDOException $ex)
	{
		$this->error(sprintf('Oops! Some Error Occured... %s', $ex->getMessage()));
	}
    }

    /**
     * Function to create databse. If database name is not specified in .env file, it will display the messasge and exits.Then it checks if database already exists
     * If not then it will create database for it.
     * @param None
     * @return void 
     */

    public function handle()
    {
	/* Take the database configuration from .env file. */
        $databaseName = env('DB_DATABASE', false);
	$databaseHost = env('DB_HOST');
	$databasePort = env('DB_PORT');
	$databaseUser = env('DB_USERNAME');
	$databasePassword = env('DB_PASSWORD');

	/*Database creation logic.*/
	if(!$databaseName)
	{
		$this->info('No database name specified in .env file. Please check it.');
	}
	else
	{
		$pdoInstance = $this->getPDO($databaseHost, $databasePort, $databaseUser, $databasePassword);
		$checkDBExists = $pdoInstance->prepare('select count(*) from INFORMATION_SCHEMA.SCHEMATA where SCHEMA_NAME = :dbname');
		$checkDBExists->bindParam(':dbname', $databaseName, PDO::PARAM_STR, 12);
		$checkDBExists->execute();
		$checkDBExists->setFetchMode(PDO::FETCH_ASSOC);
		/*If database exist it will return 1 else 0*/
		if(!$checkDBExists->fetchColumn())
		{
			$pdoInstance->exec(sprintf('CREATE DATABASE IF NOT EXISTS %s;', $databaseName));
			$this->info(sprintf('Created Database %s Successfully. Run migrations to install tables.', $databaseName));
		}
		else
		{
			$this->info(sprintf('Database Already Created. Migrate the tables.'));
		}
	}
    }
}
