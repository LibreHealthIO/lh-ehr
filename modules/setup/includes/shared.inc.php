<?php
/**
 * This file is to block sql injections and any hacks within the setup procedure by checking for unwanted tags in the url
 * tab of the browser. Breaks /Kills the page in case it detects such
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
//--------------------------------------------------------------------------
// *** remote file inclusion, check for strange characters in $_GET keys
// *** all keys with "/", "\", ":" or "%-0-0" are blocked, so it becomes virtually impossible
// *** to inject other pages or websites
    foreach($_GET as $get_key => $get_value){
        if(is_string($get_value) && (preg_match("/\//", $get_value) || preg_match("/\[\\\]/", $get_value) || preg_match("/:/", $get_value) || preg_match("/%00/", $get_value))){
            if(isset($_GET[$get_key])) unset($_GET[$get_key]);
                die("A hacking attempt has been detected. For security reasons, we're blocking any code execution. <a href='index.php'>Click here</a>");
                }
            }
?>