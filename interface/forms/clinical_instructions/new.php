<?php
// +-----------------------------------------------------------------------------+ 
// Copyright (C) 2015 Z&H Consultancy Services Private Limited <sam@zhservices.com>
//
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
//
// A copy of the GNU General Public License is included along with this program:
// libreehr/interface/login/GnuGPL.html
// For more information write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// Author:   Jacob Paul <jacob@zhservices.com>
//
// +------------------------------------------------------------------------------+
//SANITIZE ALL ESCAPES
$sanitize_all_escapes = true;

//STOP FAKE REGISTER GLOBALS
$fake_register_globals = false;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formsoptions.inc.php");
require_once("$srcdir/headers.inc.php");

formHeader("Clinical Instructions Form");
$returnurl = 'encounter_top.php';
$form_name = 'form_clinical_instructions';
$formid = 0 + (isset($_GET['id']) ? $_GET['id'] : '');
if (empty($formid)) {
    $formid = checkFormIsActive($form_name,$encounter);
}
$check_res = $formid ? formFetch("form_clinical_instructions", $formid) : array();
?>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
        <?php call_required_libraries(['bootstrap']); ?>
    </head>
    <body class="body_top">
        <h2><?php echo xlt('Clinical Instructions Form'); ?></h2>
        <h3><?php echo xlt('Instructions').':'; ?></h3>

        <!-- Begin form -->
        <?php echo "<form method='post' name='my_form' " . "action='$rootdir/forms/clinical_instructions/save.php?id=" . attr($formid) . "'>\n"; ?>
            <div class="container" style="width: 500px; float: left;">
                <textarea class="form-control" name="instruction" id ="instruction" rows=3>
                    <?php echo text($check_res['instruction']); ?>
                </textarea>
                <br/>
                <input type='submit' value='<?php echo xla('Save'); ?>' class="button-css cp-submit" style="float: right">&nbsp;
            </div>

        </form>
    <!-- formFooter() closes body and html tags -->
    <?php
    formFooter();
    ?>
