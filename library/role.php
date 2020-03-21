<?php
/**
 *  Role class
 *
 * This class manages all the operations that are performed on the
 * roles.json file which contains the data of all the roles
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

class Role {


    public $file;


    /**
     * constructor
     * initializes the file handler for the roles json file
     * 
     * @param string $location
     */
    public function __construct() {
        $this->file =  $GLOBALS['OE_SITE_DIR'] . "/roles.json";
    }

    /**
     * createNewRole
     * 
     * Adds a new role entry in the JSON file with the given data
     * 
     * @param string $role
     * @param array $data
     * @param array $item_list
     * @return bool
     */
    public function createNewRole($role, $data, $item_list) {
        $role = [
            'title' => $role,
            'menu_data' => $data,
            'item_list' => $item_list
        ];
        
        $current_roles = file_get_contents($this->file);
        $roles = [];
        
        if (!$current_roles) {
            $roles[] = $role;
            $roles_json = json_encode($roles,  JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            if (file_put_contents($this->file, $roles_json)) {
                return true;
            } else {
                return false;
            }
        } else {

            $current_roles_decoded = json_decode($current_roles, true);
            $roles = $current_roles_decoded;
            $roles[] = $role;
            $roles_json = json_encode($roles,  JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            if (file_put_contents($this->file, $roles_json)) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * editRole
     *
     * Changes the data in the specified role to the given data
     * @param string $role
     * @param array $data
     * @param array $item_list
     * @return bool
     */
    public function editRole($role_title, $data, $item_list) {
        $role = [
            'title' => $role_title,
            'menu_data' => $data,
            'item_list' => $item_list
        ];
        $current_roles = file_get_contents($this->file);
        $roles = [];
        
        if (!$current_roles) {
            return false;
        } else {
            $current_roles_decoded = json_decode($current_roles, true);
            $roles = $current_roles_decoded;
            foreach ($roles as $key => $roleToSearch) {
                if ($roleToSearch['title'] == $role['title']) {
                    $roles[$key] = $role;
                }
            }
            $roles_json = json_encode($roles,  JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            if (file_put_contents($this->file, $roles_json)) {
                return true;
            } else {
                return false;
            }
            
        }
        return true;
    }

    /**
     * deleteRole
     *
     * Deletes the specified role from the json file
     * 
     * @param string $role
     * @return bool
     */
    public function deleteRole($role_title) {
        $current_roles = file_get_contents($this->file);
        $roles = [];
        
        if (!$current_roles) {
            return false;
        } else {
            $current_roles_decoded = json_decode($current_roles, true);
            foreach($current_roles_decoded as $rolesToSearch) {
                if ($rolesToSearch['title'] != $role_title) {
                    $roles[] = $rolesToSearch;
                }
            }
            $roles_json = json_encode($roles,  JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            if (file_put_contents($this->file, $roles_json)) {
                return true;
            } else {
                return false;
            }
            
        }
        return true;
    }

    /**
     * getRole
     * 
     * Retrieves the data related to the given role
     * 
     * @param string $role
     * @return array
     */
    public function getRole($role) {

        $current_roles = file_get_contents($this->file);
        $current_roles_decoded = json_decode($current_roles);

        foreach($current_roles_decoded as $current_role) {
            if ($current_role->title == $role) {
                return $current_role;
            }
        }

        return null;
    }

    /**
     * getRoleList
     * Returns a list containing titles of all the currently existing roles
     *
     * @return array $roles
     */
    public function getRoleList() {

        $current_roles = file_get_contents($this->file);
        $current_roles_decoded = json_decode($current_roles);

        $roles = [];

        foreach($current_roles_decoded as $current_role) {
            $roles[] = $current_role->title;
        }

        return $roles;
    }





    
}

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

    public function getData() {

        return [
            "label" => $this->label ?: null,
            "menu_id" => $this->menu_id ?: null,
            "target" => $this->target ?: null,
            "url" => $this->url ?: null,
            "children" => $this->children ?: [],
            "requirement" => $this->requirement ?: 0,
            "global_req" => $this->global_req ?:null,
            "parent" => $this->parent ?: null,
            "mainParent" => $this->mainParent ?: null,
        ];
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


function getMenuJSONString($array, $json_data) {
    $itemArray = parsePOSTtoMenuData($array, $json_data);
    
    if(count($itemArray) > 0) {
        $finalJsonString = [];
        foreach($itemArray as $item) {
            $string = $item->getData();
            //var_dump($string);
            $finalJsonString[] = $string;
        }
        return $finalJsonString;
    }
}