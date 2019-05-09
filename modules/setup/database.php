<?php
/**
 * This is the file is the ("CORE") of the setup procedure. This file is responsible for creating database, cloning database,
 * adding an initial useer, store passwords etc. This file works hand in hand with the step4.php and Database.class.php
 * file to provide an efficient setup procedure. Also responsible for returning valuable information to the user during setup
 *
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package Librehealth EHR
 * @author Mua Laurent <muarachmann@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */
?>

<?php
    include_once("includes/shared.inc.php");
    include_once("includes/settings.inc.php");
    require_once (dirname(__FILE__) . '/../../library/authentication/password_hashing.php');
    include_once("classes/Database.php");
    include_once("includes/functions.inc.php");
?>

<?php
        // start session for the database process
        session_start();

        // get all post variables coming from ajax method
        $server         =  $_POST["server"];           $port           = $_POST["port"];
        $dbname         =  $_POST["dbname"];           $login          = $_POST["login"];
        $pass           =  $_POST["pass"];             $root           = $_POST["root"];
        $rootpass       =  $_POST["rootpass"];         $loginhost      = $_POST["loginhost"];
        $collate        =  $_POST["collate"];          $inst           = $_POST["inst"];
        $site           =  $_POST["site"];             $source_site_id = $_POST["source_site_id"];
        $clone_database =  $_POST["clone_database"];   $iuser          = $_POST["iuser"];
        $iuserpass      =  $_POST["iuserpass"];        $iufname        = $_POST["iufname"];
        $iuname         =  $_POST["iuname"];           $igroup         = $_POST["igroup"];

        $installer = new Database($_POST);

        // store the session variables in a temporal storage temp/session_id.text or php
        // deletes this file if it is 2 days older or so cause no longer needed
        $files = glob("tmp/*");
        $now   = time();
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * 2) { // 2 days and older
                    unlink($file);
                }
            }
        }

        // array for sstoring the progress, message and status for each process taking place
        $messageArray = array();

        // Server side form Validation
        // (applicable if not cloning from another database)

        if(empty($installer->clone_database)){
            if ( ! $installer->login_is_valid() ) {
                $messageArray["message"]  = $installer->error_message;
                $messageArray["status"]   = 400;
                $messageArray["percentage"] = 2;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
                exit();
            }else{
                $messageArray["message"]  = $installer->success_message;
                $messageArray["status"]   = 200;
                $messageArray["percentage"] = 2;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
            }

            if ( ! $installer->iuser_is_valid() ) {
                sleep(1);
                $messageArray["message"]  = $installer->error_message;
                $messageArray["status"]   = 400;
                $messageArray["percentage"] = 4;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
                exit();
            }else{
                sleep(1);
                $messageArray["message"]  = $installer->success_message;
                $messageArray["status"]   = 200;
                $messageArray["percentage"] = 4;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
            }

            if ( ! $installer->password_is_valid() ) {
                sleep(1);
                $messageArray["message"]  = $installer->error_message;
                $messageArray["status"]   = 400;
                $messageArray["percentage"] = 6;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
                exit();
            }
            else{
                sleep(1);
                $messageArray["message"]  = $installer->success_message;
                $messageArray["status"]   = 200;
                $messageArray["percentage"] = 6;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
            }
        }
        sleep(1);
        $messageArray["message"]  = "Configuring LibreHealth EHR...";
        $messageArray["status"]   = 200;
        $messageArray["percentage"] = 7;
        file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
        sleep(1);


        // Skip below if database shell has already been created.
        if ($inst != 2) {
            sleep(1);
            $messageArray["message"]  = "Connecting to MySQL Server...";
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 7;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);
            if ( ! $installer->root_database_connection() ) {
                sleep(1);
                $messageArray["message"]  = $installer->error_message;
                $messageArray["status"]   = 400;
                $messageArray["percentage"] = 8;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
                exit();
            }
            else {
                sleep(1);
                $messageArray["message"]  = $installer->success_message;
                $messageArray["status"]   = 200;
                $messageArray["percentage"] = 8;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
            }
        }

        // Only pertinent if cloning another installation database
        if (!empty($installer->clone_database)) {
            sleep(1);
            $messageArray["message"]  = "Dumping source database...";
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 8;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);
            if ( ! $installer->create_dumpfiles() ) {
                sleep(1);
                $messageArray["message"]  = $installer->error_message;
                $messageArray["status"]   = 400;
                $messageArray["percentage"] = 8;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
            }
            else {
                sleep(1);
                $messageArray["message"]  = "successfully dumped source database";
                $messageArray["status"]   = 200;
                $messageArray["percentage"] = 9;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
            }
        }

        // Only pertinent if mirroring another installation directory e.g (default site)
        if ( ! empty($installer->source_site_id)) {
            sleep(1);
            $messageArray["message"]  = "Creating site directory...";
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 8;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);

            if ( ! $installer->create_site_directory() ) {
                sleep(1);
                $messageArray["message"]  = $installer->error_message;
                $messageArray["status"]   = 400;
                $messageArray["percentage"] = 8;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
                exit();
            }
            else {
                sleep(1);
                $messageArray["message"]  = "successfully created site directory";
                $messageArray["status"]   = 200;
                $messageArray["percentage"] = 9;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
            }
        }


        // Skip below if database shell has already been created.(if you choosed the option you have already database)
        if ($inst != 2) {
            sleep(1);
            $messageArray["message"]  = "Creating database...";
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 10;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);

            if ( ! $installer->create_database() ) {
                sleep(1);
                $messageArray["message"]  = $installer->error_message. ". Could not create database <span style='color: black;text-decoration: underline;'>". $installer->dbname." </span>";
                $messageArray["status"]   = 400;
                $messageArray["percentage"] = 11;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
                exit();

            } else {
                sleep(1);
                $messageArray["message"]  = "Successfully created database ". $installer->dbname;
                $messageArray["status"]   = 200;
                $messageArray["percentage"] = 20;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
            }
            sleep(1);
            $messageArray["message"]  = "Creating user with permissions for database...";
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 22;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);

            if ( ! $installer->grant_privileges() ) {
                sleep(1);
                $messageArray["message"]  = $installer->error_message. " Error when granting privileges to the specified user. <span style='color: black;text-decoration: underline;'>". $installer->login." </span>";
                $messageArray["status"]   = 400;
                $messageArray["percentage"] = 23;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
                exit();
            } else {
                sleep(1);
                $messageArray["message"]  = "Granted privileges to user <span style='color: black;text-decoration: underline;'> ".$installer->login. "</span > on database <span style='color: black;text-decoration: underline;'>". $installer->dbname." </span>";
                $messageArray["status"]   = 200;
                $messageArray["percentage"] = 24;
                file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
                sleep(1);
            }

            sleep(1);
            $messageArray["message"]  = "Reconnecting as new user...";
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 25;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);
            //disconnects the user
            $installer->disconnect();
        } else {

            sleep(1);
            $messageArray["message"]  = "Connecting to MySQL Server...";
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 25;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);
        }


        // connecting user to mysql connection
        if ( ! $installer->user_database_connection() ) {
            sleep(1);
            $messageArray["message"]  = $installer->error_message. " Error when connecting user <span style='color: black;text-decoration: underline;'>". $installer->login." </span> to database". $installer->$dbname;
            $messageArray["status"]   = 400;
            $messageArray["percentage"] = 23;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);
            exit();
        }
        else {
            sleep(1);
            $messageArray["message"]  = "Successfully connected to mysql server";
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 26;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);
        }


        // Load the database files ready for execution
        $messageArray["message"]  = "Creating Main tables... Creating Language Translation (utf8) tables... Creating CVX Immunization Codes tables... This may take a while <span class='fa fa-clock-o'></span> <i class='fa fa-spinner fa-spin pull-right' style='font-size:24px'></i>";
        $messageArray["status"]   = 200;
        $messageArray["percentage"] = 46;
        file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));

        $dump_results = $installer->load_dumpfiles();

        if ( !$dump_results ) {
            $messageArray["message"]  = $installer->error_message;
            $messageArray["status"]   = 400;
            $messageArray["percentage"] = 27;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            exit();
        } else {
            $messageArray["message"]  = $dump_results;
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 58;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
        }



        sleep(1);
        $messageArray["message"]  = "Writing SQL configuration...";
        $messageArray["status"]   = 200;
        $messageArray["percentage"] = 60;
        file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
        sleep(1);
        if ( ! $installer->write_configuration_file(0) ) {
            sleep(1);
            $messageArray["message"]  = $installer->error_message;
            $messageArray["status"]   = 400;
            $messageArray["percentage"] = 58;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);
            exit();
        }
        else {
            sleep(1);
            $messageArray["message"]  = "Successfully written configuration ". $installer->conffile;
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 67;
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);
        }


        // Only pertinent if not cloning another installation database
        if (empty($installer->clone_database)) {

            sleep(1);
            $messageArray["message"] = "Setting version indicators...";
            $messageArray["status"] = 200;
            $messageArray["percentage"] = 69;
            file_put_contents("tmp/ajaxprocess.txt", json_encode($messageArray));
            sleep(1);
            if (!$installer->add_version_info()) {
                sleep(1);
                $messageArray["message"] = $installer->error_message;
                $messageArray["status"] = 400;
                $messageArray["percentage"] = 69;
                file_put_contents("tmp/ajaxprocess.txt", json_encode($messageArray));
                sleep(1);
                exit();
            } else {
                sleep(1);
                $messageArray["message"] = "Successfully added version info to database";
                $messageArray["status"] = 200;
                $messageArray["percentage"] = 71;
                file_put_contents("tmp/ajaxprocess.txt", json_encode($messageArray));
                sleep(1);
            }

            sleep(1);
            $messageArray["message"] = "Writing global configuration defaults...";
            $messageArray["status"] = 200;
            $messageArray["percentage"] = 75;
            file_put_contents("tmp/ajaxprocess.txt", json_encode($messageArray));
            sleep(1);
            if (!$installer->insert_globals()) {
                sleep(1);
                $messageArray["message"] = $installer->error_message;
                $messageArray["status"] = 400;
                $messageArray["percentage"] = 76;
                file_put_contents("tmp/ajaxprocess.txt", json_encode($messageArray));
                sleep(1);
                exit();
            } else {
                sleep(1);
                $messageArray["message"] = "Successfully written global configuration defaults";
                $messageArray["status"] = 200;
                $messageArray["percentage"] = 78;
                file_put_contents("tmp/ajaxprocess.txt", json_encode($messageArray));
                sleep(1);
            }

            sleep(1);
            $messageArray["message"] = "Adding Initial User...";
            $messageArray["status"] = 200;
            $messageArray["percentage"] = 79;
            file_put_contents("tmp/ajaxprocess.txt", json_encode($messageArray));
            sleep(1);

            if (!$installer->add_initial_user()) {
                sleep(1);
                $messageArray["message"] = $installer->error_message;
                $messageArray["status"] = 400;
                $messageArray["percentage"] = 79;
                file_put_contents("tmp/ajaxprocess.txt", json_encode($messageArray));
                sleep(1);
                exit();
            } else {
                sleep(1);
                $messageArray["message"] = $installer->success_message;
                $messageArray["status"] = 200;
                $messageArray["percentage"] = 91;
                file_put_contents("tmp/ajaxprocess.txt", json_encode($messageArray));
                sleep(1);

            }

        }



        if (!empty($installer->clone_database) ) {
            // Database was cloned, skip ACL setup.
            sleep(1);
            $messageArray["message"]  = "Click 'continue' for further instructions.";
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 100;
            //tract the next action to do
            $messageArray["next_state"] = "user_config";
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);
        }
        else {
            sleep(1);
            $messageArray["message"]  = "Next step will install and configure access controls. <span style='color: black;text-decoration: underline;'>(php-GACL).</span>";
            $messageArray["status"]   = 200;
            $messageArray["percentage"] = 100;
            //tract the next action to do
            $messageArray["next_state"] = "php_gacl";
            file_put_contents("tmp/ajaxprocess.txt" , json_encode($messageArray));
            sleep(1);
        }

?>