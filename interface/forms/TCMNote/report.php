<?php

/*
 * For information on Form Development view: README
 */

// libreehr/interface/globals.php
include_once('../../globals.php');

// $src = path to libreehr/library directory
include_once($srcdir.'/api.inc');

include_once(dirname(__FILE__).'/classes/TCMNote.php');

function tcmnote_report($pid, $encounter, $cols, $id) {
	$tcmNote = new TCMNote(dirname(__FILE__));

	$tcmNote->noteId = $id;

	$tcmNote->sqlReportNote();

	$tcmNote->displayReportNote();
}
