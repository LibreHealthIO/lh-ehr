<?php

/*
 * For information on Form Development view: README
 */

// libreehr/interface/globals.php
include_once('../../globals.php');

// $src = path to libreehr/library directory
include_once($srcdir.'/api.inc');
//include_once($srcdir.'/options.inc.php');
include_once($srcdir.'/forms.inc');

include(dirname(__FILE__).'/classes/TCMNote.php');
include(dirname(__FILE__).'/classes/TCMNoteAjax.php');

$tcmNoteAjax = new TCMNoteAjax;

if(array_key_exists('action', $_GET))
	$tcmNoteAjax->action = $_GET['action'];

$tcmNoteAjax->tcmNote = new TCMNote(dirname(__FILE__));

if(array_key_exists('id', $_GET))
	$tcmNoteAjax->tcmNote->noteId = $_GET['id'];

if(array_key_exists('test', $_GET))
	$tcmNoteAjax->testState = (boolean) $_GET['test'];

$tcmNoteAjax->userAuthorized = $userauthorized;

$tcmNoteAjax->formData = json_decode(file_get_contents('php://input'));

echo $tcmNoteAjax->response();	