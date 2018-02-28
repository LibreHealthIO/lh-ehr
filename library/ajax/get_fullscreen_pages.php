<?php

/**
 * get_fullscreen_pages.php
 * This page responds to an AJAX request to retrieve the
 * pages available for a particular fullscreen role.
 * Returns an array of the available pages
 */

$sanitize_all_escapes=true;

//STOP FAKE REGISTER GLOBALS

$fake_register_globals=false;

//
require_once("../../interface/globals.php");
require_once("$srcdir/sql.inc");

$fQuery = sqlStatement("select entry_id from menu_trees where menu_set='".$_POST['role_name']."'");

if($fQuery) {
    
    $pages = [];
    for($iter = 0;$frow = sqlFetchArray($fQuery); $iter++)
      $result[$iter] = $frow;
    
      // another posibility to get the "labels" is to segregate the entry_id with a "|" as the delimiter and use the first half
      foreach($result as $iter) {
        $fres2 = sqlStatement("select id,label from menu_entries where id='".$iter{'entry_id'}."'");
        $frow2 = sqlFetchArray($fres2);
        
        $pages[] = ["id" => $frow2{'id'}, "label" => $frow2{'label'}];
        
      }
  }

  echo json_encode($pages);