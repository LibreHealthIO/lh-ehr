<?php
/**
 *  Role Creator
 *
 *  This program displays the information entered in the Calendar program ,
 *  allowing the user to change status and view those changed here and in the Calendar
 *  Will allow the collection of length of time spent in each status
 *
 * Copyright (C) 2018 Anirudh Singh
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
 * @package Librehealth EHR 
 * @author Anirudh (anirudh.s.c.96@hotmail.com)
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 *
 */

 /* Include our required headers */

require_once('../globals.php');
require_once("$srcdir/acl.inc");
require_once("$srcdir/headers.inc.php");

if (!acl_check('admin', 'super')) die(xl('Not authorized','','','!'));

?>

<!DOCTYPE HTML>
<html>
<head>
<?php call_required_libraries(array("jquery-min-3-1-1","bootstrap","fancybox-custom"));
      resolveFancyboxCompatibility();
?>
    <title><?php echo xlt("Role Management") ?></title>
    <link href="js/jsoneditor/jsoneditor.css" rel="stylesheet" type="text/css">
    <script src="js/jsoneditor/jsoneditor.js"></script>

    <script type="text/javascript">

    $(document).ready(function(){

        // fancy box
        enable_modals();

        tabbify();

        // special size for
        $(".iframe_medium").fancybox( {
            'overlayOpacity' : 0.0,
            'showCloseButton' : true,
            'frameHeight' : 450,
            'frameWidth' : 660
        });

        $(function(){
            // add drag and drop functionality to fancybox
            $("#fancy_outer").easydrag();
        });
    });

    </script>
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
<h1 style="padding-left: 10px;"><?php echo xlt("Manage Roles") ?></h1>
<div style="padding-left: 10px;">
    <a href="../../roles/role_create.php" class="iframe_medium css_button"><span><?php echo xlt('Add Role'); ?></span></a>
    

</div>


</body>
</html>
