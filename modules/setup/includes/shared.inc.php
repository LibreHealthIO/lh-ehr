<?php
/**
 * Created by PhpStorm.
 * User: rachmann mua
 * Date: 6/16/18
 * Time: 9:35 PM
 */
?>

<?php
//--------------------------------------------------------------------------
// *** remote file inclusion, check for strange characters in $_GET keys
// *** all keys with "/", "\", ":" or "%-0-0" are blocked, so it becomes virtually impossible
// *** to inject other pages or websites

    foreach($_GET as $get_key => $get_value){
        if(is_string($get_value) && (preg_match("/\//", $get_value) || preg_match("/\[\\\]/", $get_value) || preg_match("/:/", $get_value) || preg_match("/%00/", $get_value))){
            if(isset($_GET[$get_key])) unset($_GET[$get_key]);
                die("A hacking attempt has been detected. For security reasons, we're blocking any code execution. <a href='start_up.php'>Click here</a>");
                }
            }



?>