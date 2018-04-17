<?php
/**
 *  Role Creator
 *
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


$menu_data = file_get_contents( $GLOBALS['OE_SITE_DIR'] . "/menu_data.json");
$json_data = json_decode($menu_data, true);

class MenuItem {

    public $label;
    public $menu_id;
    public $target;
    public $url;
    public $children;
    public $requirement;
    public $mainParent;
    public $parent;
    public $global_req;


    public function __construct($item) {
       // var_dump($item);
        $this->label = $item["label"];
        $this->menu_id = $item["menu_id"];
        $this->target = $item["target"];
        $this->url = $item["url"];
        $this->children = $item["children"];
        $this->requirement = $item["requirement"];
        $this->global_req = $item["global_req"];
        $this->parent = $item["parent"];
        $this->mainParent = $item["mainParent"];
    }


    public function printJson() {
        return $this->toJson();
    }


    public function toJson() {
        return json_encode([

            'label' => $this->label ?: '',
            'menu_id' => $this->menu_id ?: '',
            'target' => $this->target ?: '',
            'url' => $this->url ?: '',
            'children' => $this->children ?: '',
            'requirement' => $this->requirement ?: '',
            'global_req' => $this->global_req ?: '',
            'parent' => $this->parent ?: '',
            'mainParent' => $this->mainParent ?: '',

        ], JSON_PRETTY_PRINT);
    }
}

function parsePOSTtoMenuData($array, $json_data) {
    $menuItemsArray = [];
    $filteredArray = array_filter(array_keys($array), function ($k){ return (strpos($k, "cb-") !== false); }); 
    $array = array_intersect_key($array, array_flip($filteredArray));
    foreach($array as $item => $val) {
        $returnVal = findMenuItem($item, ($json_data));
        $menuItem = json_encode($returnVal);
        // check whether the retrieved item is a child or a parent
        // level 2 child
        if ($returnVal["mainParent"] != null) {
            foreach($menuItemsArray as $menuItemParent) {
                if ($menuItemParent->menu_id == $returnVal["mainParent"]) {
                    foreach($menuItemParent->children as $menuItemChild) {
                        if ($menuItemChild->label == $returnVal["parent"]) {
                            $menuItemChild->children[] = $returnVal;
                        }
                    }
                }
            }
        }
        // level 1 child
        else if ($returnVal["parent"] != null && $returnVal["mainParent"] == null) {
            foreach($menuItemsArray as $menuItemParent) {
                if ($menuItemParent->menu_id == $returnVal["parent"]) {
                    $menuItemParent->children[] = new MenuItem($returnVal);
                }
            }
        }
        else {
            $menuItemsArray[] = new MenuItem($returnVal);

        }

    }

    return $menuItemsArray;
   
}

function findMenuItem($item, $json_data) {

    // check if the menu item is a main parent 
    if (strpos($item, "parent") !== false) {
        $items = explode('-', $item);
        $id = $items[2];
        $title = $items[3];
        foreach($json_data as $json) {
            //echo "Title: ".$json["label"]. ", id: ".$json["menu_id"]."<br />";
            if ( ($json["label"] == $title || $json["label"] == str_replace('_', ' ', $title)) && $json["menu_id"] == $id) {
                $returnJson = $json;
                $returnJson["children"] = [];
                return $returnJson;
            }
        }
    }

    // check if the menu item is a first-child
    if (strpos($item, "child1") !== false) {
        $items = explode('-', $item);
        $parentId = $items[2];
        $title = $items[3];
        foreach($json_data as $json) {
            if (($json["menu_id"] == $parentId)) {
                foreach($json["children"] as $child) {
                    if( $child["label"] == $title || $child["label"] == str_replace('_', ' ', $title)) {
                        $returnJson = $child;
                        $returnJson["children"] = [];
                        $returnJson["parent"] = $parentId;
                        return $returnJson;
                    }
                }
            }
        }
    }

    // check if the menu item is a second-child
    if (strpos($item, "child2") !== false) {
        $items = explode('-', $item);
        $parentId = $items[2];
        $child1Label = $items[3];
        $title = $items[4];
        foreach($json_data as $json) {
            if(($json["menu_id"] == $parentId)) {
                foreach($json["children"] as $child1) {
                    if ( $child1["label"] == $child1Label || $child["label"] == str_replace('_', ' ', $child1Label)) {
                        foreach($child1["children"] as $child2) {
                            if ($child2["label"] == $title || $child2["label"] == str_replace('_', ' ', $title)) {
                                $returnJson = $child2;
                                $returnJson["children"] = [];
                                $returnJson["parent"] = $child1Label;
                                $returnJson["mainParent"] = $parentId;
                                return $returnJson;
                            }
                        }
                    }
                }
            }
        }
        return $items;
    }
    
}


if($_POST) {
    //var_dump($_POST);
    $itemArray = parsePOSTtoMenuData($_POST, $json_data);
    if(count($itemArray) > 0) {
        $finalJsonString = "[";
        foreach($itemArray as $item) {
            $string = $item->printJson();
            $finalJsonString = $finalJsonString . $string;
            if ($item != end($itemArray))
                $finalJsonString = $finalJsonString . ",";
        }
        $finalJsonString = $finalJsonString . "]";

        print($finalJsonString);
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
<h1 style="padding-left: 10px;"><?php echo xlt("Create a role") ?></h1>
<div style="padding-left: 10px;">
    <form method="POST" action="">
        <label> Role name:  </label>
        <input type="text" name="title" id="title" placeholder="Role name" />
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
        <input type="submit" name="create" value="Create role" />
    </form>
    
<script>
$(document).ready(function()  {
    $('[id^=cb-parent]').change(function(e) {
            var parentId = e.target.id.slice(10);
            //console.log(parentId);
            if($(this).is(':checked')){
                $('[id="child1-'+parentId+'"]').show();
            } else {
                $('[id="child1-'+parentId+'"]').hide();
            }
        });

        $('[id^=cb-child1]').change(function(e) {
            var parentId = e.target.id.slice(10)
            //console.log(parentId);
            //$('[id="child2-repimg-Procedures"]').show();
            if($(this).is(':checked')){
                console.log($('[id="child2-repimg-Procedures"]').html());
                $('[id="child2-'+parentId+'"]').show();
            } else {
                $('[id="child2-'+parentId+'"]').hide();
            }
        });
});
</script>
</div>


</body>
</html>
