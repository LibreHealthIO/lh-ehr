<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 6/27/18
 * Time: 3:28 AM
 */
?>

<?php
    include_once("includes/shared.inc.php");
    include_once("includes/settings.inc.php");
    include_once("classes/Database.php");
    include_once("includes/functions.inc.php");
?>

<?php
        // get all post variables coming from ajax method
            $server         =  $_POST["server"];           $port           = $_POST["por"];
            $dbname         =  $_POST["dbname"];           $login          = $_POST["login"];
            $pass           =  $_POST["pass"];             $root           = $_POST["root"];
            $rootpass       =  $_POST["rootpass"];         $loginhost      = $_POST["loginhost"];
            $collate        =  $_POST["collate"];          $inst           = $_POST["inst"];
            $site           =  $_POST["site"];             $source_site_id = $_POST["source_site_id"];
            $clone_database =  $_POST["clone_database"];   $iuser          = $_POST["iuser"];
            $iuserpass      =  $_POST["iuserpass"];        $iufname        = $_POST["iufname"];
            $iuname         =  $_POST["iuname"];           $igroup         = $_POST["igroup"];

            $installer = new Database($_POST);

        // Server side form Validation
        // (applicable if not cloning from another database)

        if(empty($installer->clone_database)){
            if ( ! $installer->login_is_valid() ) {
//                echo "ERROR. Please pick a proper 'Login Name'.<br>";
//                echo "Click Back in browser to re-enter.<br>";
                $arr = array(
                    "message" => $installer->error_message,
                    "status"  => 400
                );
                echo json_encode($arr);
            }else{
                $arr = array(
                    "message" => $installer->success_message,
                    "status"  => 200
                );
                echo json_encode($arr);
            }
        }




?>
