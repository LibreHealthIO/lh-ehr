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
 require_once('../globals.php');
 require_once("$srcdir/acl.inc");
 require_once("$srcdir/headers.inc.php");
 require_once("$srcdir/role.php");


 if (!acl_check('admin', 'super')) die(xl('Not authorized','','','!'));

$role = new Role();
$menu_data = file_get_contents( $GLOBALS['OE_SITE_DIR'] . "/menu_data.json");
$json_data = json_decode($menu_data, true);

if (!isset($_GET['title']) || $_GET['title'] == '') {
    echo "Invalid title";
}

if ($roleData = $role->getRole($_GET['title'])) {
    $roleDataJson = json_decode(json_encode(($roleData->menu_data)), true);
} else {
    echo "Invalid title";
}

$roleData->item_list = json_encode($roleData->item_list);

if ($_POST) {
    $json_string = getMenuJSONString($_POST, $json_data);
    if($role->getRole($_GET['title']) != null) {
        $menu_item_list = explode(',', $_POST['checkedList']);
        
        $result = $role->editRole($_GET['title'], $json_string, $menu_item_list);
        if($result) {
            header("Location: ".$_SERVER['PHP_SELF']."?title=".$_GET['title']."&edited=true");
        } else {
            echo " Unable to edit role ";
        }
    }
}

 ?>


 
<!DOCTYPE HTML>
<html>
<head>
<?php call_required_libraries(array("jquery-min-3-1-1","bootstrap","fancybox-custom"));
      resolveFancyboxCompatibility();
?>
    <title><?php echo xlt("Role Management") ?></title>

    <script type="text/javascript">

    $(document).ready(function(){

        // fancy box


        // special size for
        $(".iframe_medium").fancybox( {
            'overlayOpacity' : 0.0,
            'showCloseButton' : true,
            'frameHeight' : 450,
            'frameWidth' : 660
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
<?php if (isset($_GET['edited']) && $_GET['edited'] == true) {
    ?> <h2><span class="text" style="color: green;"> Role edited successfully </span></h2> <?php
}
?>
<h1 style="padding-left: 10px;"><?php echo xlt("Edit a role") ?></h1>
<div style="padding-left: 10px;">
    <form method="POST" action="">
        <label> Role name:  </label>
        <span class="text"> <?php echo $roleData->title; ?></span>
        <br />
        <label> Accessible Menus: </label> <br />
        <?php
            echo "<div style='color: green;'>\n";
                foreach($json_data as $json) {
                    echo $json["label"]." <input type='checkbox' name='cb-parent-".$json["menu_id"]."-".$json["label"]."' id='cb-parent-".$json["menu_id"]."-".$json["label"]."' /><br /><br />\n";
                    if($json["children"] != null) {
                        echo "<div style='padding-left: 30px; display: none;color: red;' id='child1-".$json["menu_id"]."-".$json["label"]."'>\n";
                        foreach($json["children"] as $child) {
                            echo $child["label"]."<input type='checkbox' name='cb-child1-".$json["menu_id"]."-".$child["label"]."' id='cb-child1-".$json["menu_id"]."-".$child["label"]."' /><br /><br />\n";
                            if($child["children"] != null) {
                                echo "<div style='padding-left: 60px; color: blue; display: none;' id='child2-".$json["menu_id"]."-".$child["label"]."'>\n";
                                foreach($child["children"] as $child1) {
                                    echo $child1["label"]."<input type='checkbox' name='cb-child2-".$json["menu_id"]."-".$child["label"]."-".$child1["label"]."' id='cb-child2-".$json["menu_id"]."-".$json["label"]."-".$child1["label"]."' /><br /><br />\n";
                                    if ($child1["children"] != null) {
                                        echo "<div style='padding-left: 90px; color: black;' >\n";
                                        foreach($child1["children"] as $child2) {
                                            echo $child2["label"]. "<br />\n";
                                        }
                                        echo "</div>\n";
                                    }
                                }
                                echo "</div>\n";
                            }
                        }
                        echo "</div>\n";
                    }
                }
            echo "</div>\n";
        ?>
        <input type="hidden" name="checkedList" id="checkedList" value="<?php echo $roleData->item_list; ?>" />
        <input type="submit" name="edit" value="Edit role" />
    </form>
    
<script>
$(document).ready(function()  {

    function toObject(arr) {
        var rv = {};
        for (var i = 0; i < arr.length; ++i)
            if (arr[i] !== undefined) {
                rv[arr[i]] = true;
            } 
        return rv;
    }

   var checkedList = <?php echo $roleData->item_list; ?>;
   $("[id^=cb-").each(function() {
       if ($.inArray( this.id, checkedList) != -1) {
           document.getElementById(this.id).checked = true;
           var parentId =  this.id.slice(10);
           if($('[id="child1-'+parentId+'"]').length > 0 ) {
                $('[id="child1-'+parentId+'"]').show();
           }
           if( $('[id="child2-'+parentId+'"]').length > 0 ) {
                $('[id="child2-'+parentId+'"]').show();
           }

       }
   });
   checkedList = toObject(checkedList);
   var initialCheckedListValue =  Object.keys(checkedList).map(function (key) { return key; });
   $('input[name="checkedList"]').val(initialCheckedListValue);
    $('[id^=cb-parent]').change(function(e) {
            var parentId = e.target.id.slice(10);
            if($(this).is(':checked')){
                $('[id="child1-'+parentId+'"]').show();
                checkedList["cb-parent-" + parentId] = true;
            } else {
                $('[id="child1-'+parentId+'"]').hide();
                delete checkedList["cb-parent-" + parentId];
            }
            var array =  Object.keys(checkedList).map(function (key) { return key; });
            $('input[name="checkedList"]').val(array);
        });

        $('[id^=cb-child1]').change(function(e) {
            var parentId = e.target.id.slice(10)
            if($(this).is(':checked')){
                $('[id="child2-'+parentId+'"]').show();
                checkedList["cb-child1-" + parentId] = true;
            } else {
                $('[id="child2-'+parentId+'"]').hide();
                delete checkedList["cb-child1-" + parentId];
            }
            var array =  Object.keys(checkedList).map(function (key) { return key; });
            $('input[name="checkedList"]').val(array);
        });

        $('[id^=cb-child2]').change(function(e) {
            var parentId = e.target.id.slice(10)
            if($(this).is(':checked')){
                checkedList["cb-child2-" + parentId] = true;
            } else {
                delete checkedList["cb-child2-" + parentId];
            }
            var array =  Object.keys(checkedList).map(function (key) { return key; });
            $('input[name="checkedList"]').val(array);
        });

});
</script>
</div>


</body>
</html>
