<?php
/**
 * 
 * Controller to handle user password change requests.
 * 
 * <pre>
 * Expected REQUEST parameters
 * $_REQUEST['pk'] - The primary key being used for encryption. The browser would have requested this previously
 * $_REQUEST['curPass'] - ciphertext of the user's current password
 * $_REQUEST['newPass'] - ciphertext of the new password to use
 * $_REQUEST['newPass2']) - second copy of ciphertext of the new password to confirm proper user entry.
 * </pre>
 * Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com> and OEMR <www.oemr.org>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package LibreEHR
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @link    http://librehealth.io
 */

    //SANITIZE ALL ESCAPES
    $sanitize_all_escapes=true;

    //STOP FAKE REGISTER GLOBALS
    $fake_register_globals=false;

    include_once("../globals.php");
    require_once("$srcdir/authentication/password_change.php");

    $curPass=$_REQUEST['curPass'];
    $newPass=$_REQUEST['newPass'];
    $newPass2=$_REQUEST['newPass2'];

        // array for storing the message and status of the response
        // 400 for error and 200 for success
        $messageArray = array();

    if($newPass!=$newPass2)
    {
        $messageArray["status"] = 400;
        $messageArray["message"] = xlt("Pass Phrases Do not match!");
        echo json_encode($messageArray);
        exit;
    }
    $errMsg='';
    $success=update_password($_SESSION['authId'],$_SESSION['authId'],$curPass,$newPass,$errMsg);
    if($success)
    {
        $messageArray["status"] = 200;
        $messageArray["message"] = xlt("Pass Phrase change successful");
        echo json_encode($messageArray);
    }
    else
    {
        // If update_password fails the error message is returned
        $messageArray["status"] = 400;
        $messageArray["message"] =  text($errMsg);
        echo json_encode($messageArray);
    }
?>
