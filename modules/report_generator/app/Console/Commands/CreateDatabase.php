<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

/**
 * This will create a command to create databases.
 *
 * @author Priyanshu Sinha <pksinha217@gmail.com>
 * Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
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
    protected $description = 'Use this command to create database. Note that it will create the database configured in .env file. Self made command';

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
     * @param db_name Database name
     * @param db_host Database hostname
     * @param db_port Database port number
     * @param db_username Database username
     * @param db_password Database user's password
     * @return PDO connection.
     */
    private function getPDO($db_host, $db_port, $db_username, $db_password)
    {
        try {
    	    $connection = new PDO(sprintf('mysql:host = %s;port=%d', $db_host, $db_port), $db_username, $db_password);
    	    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    		return $connection;
        } catch(PDOException $exception) {
            $this->error(sprintf('Oops! Some Error Occured... %s', $exception->getMessage()));
    	}
    }

    /**
     * Function to create databases. If database name is not specified in .env file,
     * it will display the messasge and exits.Then it checks if database already exists
     * If not, it will create database for it. It's used to create 2 dbs in handle method.
     * @param db_name Database name
     * @param db_host Database hostname
     * @param db_port Database port number
     * @param db_username Database username
     * @param db_password Database user's password
     * @return void
     * @author 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
     */
    private function createDB($db_name, $db_host, $db_port, $db_username, $db_password)
    {
        /* LibreLaravel database creation logic. */
        if(!$db_name) {
            $this->info('Database name is not specified in .env file. Please check that both database names are specified.');
        } else {
            $pdo_instance = $this->getPDO($db_host, $db_port, $db_username, $db_password);
            $check_db_exists = $pdo_instance->prepare('select count(*) from INFORMATION_SCHEMA.SCHEMATA where SCHEMA_NAME = :dbname');
            $check_db_exists->bindParam(':dbname', $db_name, PDO::PARAM_STR, 12);
            $check_db_exists->execute();
            $check_db_exists->setFetchMode(PDO::FETCH_ASSOC);
            /*If database exist it will return 1 else 0 in $check_db_exists variable. */
            if(!$check_db_exists->fetchColumn()) {
                $pdo_instance->exec(sprintf('CREATE DATABASE IF NOT EXISTS %s;', $db_name));
                $this->info(sprintf('Created Database %s Successfully. Run migrations to install tables.', $db_name));
            } else {
                $this->info(sprintf('Database %s already exists. Migrate the tables.', $db_name));
            }
        }
    }

    /**
     * Function to create databases. If database name is not specified in .env file, it will display the messasge and exits.Then it checks if database already exists
     * If not then it will create database for it.
     * @param None
     * @return void
     */
    public function handle()
    {
        /** librelaravel database creation logic.
         *  @TODO: Uncomment the line below when EHR is in full Laravel mode.
         *  That is, when the EHR has been completely ported to Laravel.
         */
    	 //$this->createDB(env('DB_DATABASE'), env('DB_HOST'), env('DB_PORT'), env('DB_USERNAME'), env('DB_PASSWORD'));

         /* librereportgenerator database creation logic. */
         $this->createDB(env('DB_REPORT_GENERATOR_DATABASE'), env('DB_REPORT_GENERATOR_HOST'), env('DB_REPORT_GENERATOR_PORT'), env('DB_REPORT_GENERATOR_USERNAME'), env('DB_REPORT_GENERATOR_PASSWORD'));
    }

}
