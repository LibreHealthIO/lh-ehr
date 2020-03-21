<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("$srcdir/role.php");

function menu_entry_to_object($row)
{
    $retval=new stdClass();
    foreach($row as $key=>$value)
    {
        $retval->$key=$value;
    }
    $retval->requirement=intval($retval->requirement);
    $retval->children=array();
    if($retval->url==null)
    {
        unset($retval->url);
    }
    return $retval;
}
function load_menu($menu_set)
{
 
    if ($GLOBALS['role_based_menu_status']) {

        $role = new Role();
    $userQuery = sqlQuery("select menu_role from users where username= ? ", array($_SESSION{"authUser"}));
        $role_data = $role->getRole($userQuery["menu_role"]);
        return $role_data->menu_data;  
    } else {
        return [];
    }
    
}