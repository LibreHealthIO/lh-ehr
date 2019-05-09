<?php
/**
 * This file is responsible for grabbing the current status of the setup process in step 4 of the installation.
 * It reads from the tmp/ajaxprocess.txt file and returns the state of the setup process.
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
// Tract down all processes of the ajax call one after another and reads them here
// reads the temp/123213.txt and gets the json format (decodes and encodes it again).
// Then sends the result to our step4.php process.


    // The file has JSON type.
    header('Content-Type: application/json');

    // Prepare the file name from the query string.
    // Don't use session_start here. Otherwise this file will be only executed after the process.php execution is done.

   // $file = str_replace(".", "", $_GET['file']);
    $file = "tmp/ajaxprocess.txt";


    // Make sure the file is exist.
    if (file_exists($file)) {
        // Get the content and echo it.
        $text = file_get_contents($file);
        echo $text;
        // Convert to JSON to read the status.
        $obj = json_decode($text);
        // If the process is finished, delete the file.
        if ($obj->percentage == 100) {
            unlink($file);
        }
    }
    else {
        echo json_encode(array("percent" => null, "message" => null));
    }













?>