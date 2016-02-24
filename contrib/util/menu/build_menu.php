<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("cli_header.php");

require_once($openemr_root_dir."/interface/main/tabs/menu/menu_data.php");


function db_create_menu_entry(&$entry,$id)
{
    $sqlCreate="INSERT INTO menu_entries (id,label,target,url,requirement,acl_reqs,global_reqs)"
              ." VALUES (?,?,?,?,?,?,?)";
    
    $parameters=array();
    $parameters[0]=$id;
    $parameters[1]=$entry->label;
    $parameters[2]=$entry->target;
    $parameters[3]=$entry->url;
    $parameters[4]=$entry->requirement;
    $parameters[5]=json_encode($entry->acl_req);
    $parameters[6]=json_encode($entry->global_req);
    
    if(! $GLOBALS['database']->Execute($sqlCreate,$parameters))
    {
        var_dump($parameters);
        report_error($GLOBALS['database'],$sqlCreate);
    }
    
}

function db_create_menu_tree($group,$id,$parent_id,$seq)
{
    $sqlCreate="INSERT INTO menu_trees (menu_set,entry_id,parent,seq)"
              ." VALUES (?,?,?,?)";
    $parameters=array();
    $parameters[0]=$group;
    $parameters[1]=$id;
    $parameters[2]=$parent_id;
    $parameters[3]=$seq;

    
    if(! $GLOBALS['database']->Execute($sqlCreate,$parameters))
    {
        var_dump($parameters);
        report_error($GLOBALS['database'],$sqlCreate);
    }
    
}
$found_visits=false;
function parse_menu_entries(&$entries,$parent_id=null,$group_name="default")
{
    global $found_visits;
    for($idx=0;$idx<count($entries);$idx++)
    {
        $entry=$entries[$idx];
        
        if(!isset($entry->url))
        {
            $id=$entry->label.":".$entry->menu_id;
            if($entry->label==="Visits")
            {
                if($found_visits)
                {
                    $id.="reports";
                }
                else
                {
                    $id.="encounter";

                    $found_visits=true;
                    
                }
            }
        }
        else
        {
            $id=$entry->label."|".$entry->url;
        }

        db_create_menu_entry($entry,$id);
        db_create_menu_tree($group_name,$id,$parent_id,$idx*100);
        parse_menu_entries($entry->children,$id);
    }
}


$GLOBALS['database']->Execute("TRUNCATE menu_entries");
$GLOBALS['database']->Execute("TRUNCATE menu_trees");
error_reporting(~E_NOTICE);
$menu_php=json_decode($menu_json);
parse_menu_entries($menu_php,"");