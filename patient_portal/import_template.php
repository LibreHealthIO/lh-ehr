<?php
/**
 *
 * Patient Portal Inport Template
 *
 * Copyright (C) 2016-2017 Jerry Padgett <sjpadgett@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Jerry Padgett <sjpadgett@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */
$sanitize_all_escapes=true;
$fake_register_globals=false;
require_once("../interface/globals.php"); 
require_once("../library/CsrfToken.php");

if (!empty($_POST)) {
    if (!isset($_POST['token'])) {
        CsrfToken::noTokenFoundError();
    } else if (!(CsrfToken::verifyCsrfToken($_POST['token']))) {
        CsrfToken::incorrectToken();
    }
}
$rebuilt = '';
if (isset($_POST['docid'])) {
    $rebuilt = validateFile($_POST['docid']);
}
// die($rebuilt);
if($_POST['mode'] == 'get'){
    echo file_get_contents($rebuilt);
    exit;
} else if ($_POST['mode'] == 'save') {
    file_put_contents($rebuilt, $_POST['content']);
    exit(true);
} else if ($_POST['mode'] == 'delete') {
    unlink($rebuilt);
    exit(true);
}
// so it is an import
if(!isset($_POST['up_dir'])){

    define("UPLOAD_DIR", $GLOBALS['OE_SITE_DIR'] .  '/documents/onsite_portal_documents/templates/');
} else {
    if ($_POST['up_dir'] > 0) {
        $dir = preg_replace("/[^A-Z0-9._-]/i", "_", $_POST['up_dir']);
        define("UPLOAD_DIR", $GLOBALS['OE_SITE_DIR'] .  '/documents/onsite_portal_documents/templates/'. $dir . '/');
    } else {
        define("UPLOAD_DIR", $GLOBALS['OE_SITE_DIR'] .  '/documents/onsite_portal_documents/templates/');
}
}

if (!empty($_FILES["tplFile"])) {
    $tplFile = $_FILES["tplFile"];

    if ($tplFile["error"] !== UPLOAD_ERR_OK) {
        header( "refresh:2;url= import_template_ui.php" );
        echo "<p>". xlt("An error occurred: Missing file to upload: Use back button!") . "</p>";
        exit;
    }
    // ensure a safe filename
    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $tplFile["name"]);
    $parts = pathinfo($name);
    $name = $parts["filename"].'.tpl';
    // don't overwrite an existing file
    while (file_exists(UPLOAD_DIR . $name)) {
        $i = rand(0, 128);
        $newname = $parts["filename"] . "-" . $i . "." . $parts["extension"].".replaced";
        rename(UPLOAD_DIR .$name,UPLOAD_DIR .$newname);
    }

    // preserve file from temporary directory
    $success = move_uploaded_file($tplFile["tmp_name"], UPLOAD_DIR . $name);
    if (!$success) {
        echo "<p>". xlt("Unable to save file: Use back button!") . "</p>";
        exit;
    }
    // set proper permissions on the new file
    chmod(UPLOAD_DIR . $name, 0644);
    header("location: " . $_SERVER['HTTP_REFERER']);
}
function validateFile($filename = '')
{
    $knownPath = $GLOBALS['OE_SITE_DIR'] . '/documents/onsite_portal_documents/templates/'; // default path
    $unknown = str_replace("\\", "/", realpath($filename)); // normalize requested path
    $parts = pathinfo($unknown);
    $unkParts = explode('/', $parts['dirname']);
    $ptpid = $unkParts[count($unkParts) - 1]; // is this a patient or global template
    $ptpid = ($ptpid == 'templates') ? '' : ($ptpid . '/'); // last part should be pid or template
    $rebuiltPath = $knownPath . $ptpid . $parts['filename'] . '.tpl';
    if (file_exists($rebuiltPath) === false || $parts['extension'] != 'tpl') {
        redirect();
    } elseif (realpath($rebuiltPath) != realpath($filename)) { // these need to match to be valid request
        redirect();
    } elseif (stripos(realpath($filename), realpath($knownPath)) === false) { // this needs to pass be a valid request
        redirect();
    }

    return $rebuiltPath;
}

function redirect()
{
    header('HTTP/1.0 404 Not Found');
    die();
}
?>