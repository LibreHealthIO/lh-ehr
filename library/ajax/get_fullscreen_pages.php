<?php
/** 
 * Fullscreen page retriever
 * 
 * Copyright (C) 2018 Anirudh Singh
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @package Librehealth EHR 
 * @author Anirudh (anirudh.s.c.96@hotmail.com)
 * @link http://librehealth.io
 *  
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 * 
 */

/**
 * get_fullscreen_pages.php
 * This page responds to an AJAX request to retrieve the
 * pages available for a particular fullscreen role.
 * 
 * Returns an array of the available pages
 */

$sanitize_all_escapes=true;

//STOP FAKE REGISTER GLOBALS

$fake_register_globals=false;

//
require_once("../../interface/globals.php");
require_once("$srcdir/sql.inc");
require_once("$srcdir/CsrfToken.php");

if (!empty($_POST)) {
  if (!isset($_POST['token'])) {
    CsrfToken::noTokenFoundError();
  } else if (!(CsrfToken::verifyCsrfToken($_POST['token']))) {
      die('Authentication failed.');
  }
}

$fQuery = sqlStatement("select entry_id from menu_trees where menu_set= ?", array($_POST['role_name']));

if($fQuery) {
    
    $pages = [];
    for($iter = 0;$frow = sqlFetchArray($fQuery); $iter++)
      $result[$iter] = $frow;
    
      // another posibility to get the "labels" is to segregate the entry_id with a "|" as the delimiter and use the first half
      foreach($result as $iter) {
        $fres2 = sqlStatement("select id,label from menu_entries where id=?", array($iter{'entry_id'}));
        $frow2 = sqlFetchArray($fres2);
        
        $pages[] = ["id" => $frow2{'id'}, "label" => $frow2{'label'}];
        
      }
  }

  echo json_encode($pages);