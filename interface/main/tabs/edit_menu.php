<?php
/**
 *  JSON Menu Editor
 *
 *  This program displays the information entered in the Calendar program ,
 *  allowing the user to change status and view those changed here and in the Calendar
 *  Will allow the collection of length of time spent in each status
 *
 * Copyright (C) 12/15/2016 - Tony McCormick (or LibreHealth.io Project)
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0 and the following
 * Healthcare Disclaimer
 *
 * In the United States, or any other jurisdictions where they may apply, the following additional disclaimer of
 * warranty and limitation of liability are hereby incorporated into the terms and conditions of MPL 2.0:
 *
 * No warranties of any kind whatsoever are made as to the results that You will obtain from relying upon the covered code
 *(or any information or content obtained by way of the covered code), including but not limited to compliance with privacy
 * laws or regulations or clinical care industry standards and protocols. Use of the covered code is not a substitute for a
 * health care provider’s standard practice or professional judgment. Any decision with regard to the appropriateness of treatment,
 * or the validity or reliability of information or content made available by the covered code, is the sole responsibility
 * of the health care provider. Consequently, it is incumbent upon each health care provider to verify all medical history
 * and treatment plans with each patient.
 *
 * Under no circumstances and under no legal theory, whether tort (including negligence), contract, or otherwise,
 * shall any Contributor, or anyone who distributes Covered Software as permitted by the license,
 * be liable to You for any indirect, special, incidental, consequential damages of any character including,
 * without limitation, damages for loss of goodwill, work stoppage, computer failure or malfunction,
 * or any and all other damages or losses, of any nature whatsoever (direct or otherwise)
 * on account of or associated with the use or inability to use the covered content (including, without limitation,
 * the use of information or content made available by the covered code, all documentation associated therewith,
 * and the failure of the covered code to comply with privacy laws and regulations or clinical care industry
 * standards and protocols), even if such party shall have been informed of the possibility of such damages.
 *
 * See the Mozilla Public License for more details.
 *
 * @package LibreHealth EHR
 * @author Name <tony@mi-squared.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 *
 * JSON EDITOR is included from:
 *   https://github.com/josdejong/jsoneditor
 *   Copyright (C) 2011-2015 Jos de Jong
 *   usage docs etc in js/jsoneditor/docs 
 */

 /* Include our required headers */

require_once('../../globals.php');
require_once("$srcdir/acl.inc");
require_once $GLOBALS['srcdir'].'/headers.inc.php';

if (!acl_check('admin', 'super')) die(xl('Not authorized','','','!'));

// Load Disk file
require_once "menu/menu_data.php";
$menu_json_fixed = preg_replace("/\r|\n/", "", $menu_temp);

// Process POST / Save changes
if (!empty($_POST['menuEdits'])) {
    // save a backup copy
    $today = date('Y-m-d');
    $usermenubackup = $usermenufile . ".bkup-$today";
    copy($usermenufile, $usermenubackup);
    //Pretty up the output
    $menu_json_pretty =  json_encode(json_decode($_POST['menuEdits']), JSON_PRETTY_PRINT);
    // save the new file
    file_put_contents($usermenufile,$menu_json_pretty);

    echo "<script type='text/javascript'>alert('$usermenufile and $usermenubackup saved');</script>";
    $menu_json_fixed = preg_replace("/\r|\n/", "", $menu_json_pretty);
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo xlt("Site Menu Editor") ?></title>
    <link href="js/jsoneditor/jsoneditor.css" rel="stylesheet" type="text/css">
    <script src="js/jsoneditor/jsoneditor.js"></script>

    <style>       
        body {
            font: 10.5pt arial;
            color: #4d4d4d;
            line-height: 150%;
            width: 500px;
        }

        #jsoneditor {
            width: 500px;
            height: 500px;
        }
    </style>
</head>
<body>
<h1><?php echo xlt("Site Menu Editor") ?></h1>

<form id="menuData" name="menuData" method="post" action="edit_menu.php">
    <input type="hidden" id="menuEdits" name="menuEdits" value="">
</form>

<?php echo xlt("Save Menu Changes for site ID") . ': ' . $_SESSION['site_id'];?> <input type="button" id="saveDocument" class='cp-submit' value="Save" />

<div id="jsoneditor"></div>

<script type="text/javascript">
    // create the editor
    var editor = new JSONEditor(document.getElementById('jsoneditor'));

    // Load a JSON document
    var menujson = '<?php echo $menu_json_fixed; ?>';
    editor.setText(menujson);

    // Save a JSON document
    document.getElementById('saveDocument').onclick = function () {
        // Save Dialog
        document.menuData.menuEdits.value = editor.getText();
        document.forms["menuData"].submit();
    };
</script>

</body>
</html>
