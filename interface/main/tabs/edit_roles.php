<?php
/**
 *  Role Editor
 *
 *  This program displays the page that controls 
 *  everything related to roles and the menus based
 *  on them
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

require_once('../../globals.php');
require_once("$srcdir/acl.inc");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/role.php");

if (!acl_check('admin', 'super')) die(xl('Not authorized','','','!'));

$role = new Role();
$role_list = $role->getRoleList();
?>

<!DOCTYPE HTML>
<html>
<head>
<?php call_required_libraries(array("jquery-min-3-1-1","bootstrap","font-awesome","iziModalToast"));
      resolveFancyboxCompatibility();
?>
    <title><?php echo xlt("Role Management") ?></title>
    <link href="js/jsoneditor/jsoneditor.css" rel="stylesheet" type="text/css">
    <script src="js/jsoneditor/jsoneditor.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
    <script type="text/javascript">

    $(document).ready(function(){

//initialization of iziModal
    $(".addRole").click(function () {
         $("#addRole-iframe").iziModal('open');
     });


    $(".editRole").click(function (e) {
        e.preventDefault();
       var link = $(this).attr("href");
       console.log(link);
       initIziLink(link);
     });

    function initIziLink(link) {
         $("#editRole-iframe").iziModal({
             title: 'Edit Role',
             subtitle: 'Edit administrative roles',
             headerColor: '#88A0B9',
             closeOnEscape: true,
             fullscreen:true,
             overlayClose: false,
             closeButton: true,
             theme: 'light',  // light
             iframe: true,
             width:750,
             focusInput: true,
             padding:5,
             iframeHeight: 400,
             iframeURL:'../../roles/role_edit.php?title='+link,
             onClosed:function () {
                 location.reload();
            }
         });
 
         setTimeout(function () {
             call_izi();
         },200);
     }


  function call_izi() {
           $("#editRole-iframe").iziModal('open');
       }

    $("#addRole-iframe").iziModal({
        title: 'Add Roles',
        subtitle: 'Mananage administrative roles',
        headerColor: '#88A0B9',
        closeOnEscape: true,
        fullscreen:true,
        overlayClose: false,
        closeButton: true,
        theme: 'light',  // light
        iframe: true,
        width:750,
        focusInput: true,
        padding:5,
        iframeHeight: 400,
        iframeURL: "../../roles/role_create.php",
        onClosed:function () {
            location.reload();
        }
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
      <!-- izi-frames to popup modals-->
 <div id="addRole-iframe"></div>
 <div id="editRole-iframe"></div>
<h1 style="padding-left: 10px;"><?php echo xlt("Manage Roles") ?></h1>
<div style="padding-left: 10px;">

    <div class="table-responsive">
        <table class="table table-hover">
            <tbody>
            <tr height="22">
                <th><b>Role title</b></th>
            </tr>
            <?php foreach($role_list as $role_title) { ?>
            <tr>
                <td> <span class="text">  <?php echo $role_title; ?> </span> </td>
                <td> <a href="<?php echo $role_title; ?>"  class="editRole" onclick="top.restoreSession()"> Edit  </a> </td>
                <td> <a href="../../roles/role_delete.php?title=<?php echo $role_title; ?>"  class="iframe_medium" onclick="top.restoreSession()"> Delete </a> </td>

            </tr>
            <?php 
            } ?>
    </tbody></table>
    </div>
    <a href="#" class="css_button cp-positive addRole"><span><?php echo xlt('Add Role'); ?></span></a>
    

</div>


</body>
</html>
