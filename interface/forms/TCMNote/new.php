<?php

/*
 * For information on Form Development view: README
 */

// libreehr/interface/globals.php
include_once('../../globals.php');

// $src = path to libreehr/library directory
include_once($srcdir.'/api.inc');
//include_once($srcdir.'/options.inc.php');
//include_once($srcdir.'/forms.inc');

include(dirname(__FILE__).'/classes/TCMNote.php');

$tcmNote = new TCMNote(dirname(__FILE__));

if(array_key_exists('id', $_GET))  
	$tcmNote->noteId = $_GET['id'];

//$tcmNote->noteId = 1;  ////////////  hardcode for test WKR102114
  
$tcmNote->displayNewNote();