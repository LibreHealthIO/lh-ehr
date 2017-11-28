<?php
// Software version identification.
// This is used for display purposes, and also the major/minor/patch
// numbers are stored in the database and used to determine which sql
// upgrade file is the starting point for the next upgrade.
$v_major = '2';
$v_minor = '0';
$v_patch = '0';
$v_tag   = ''; // Tag should be empty for production releases

//use of this is a temporary work around for a bad descision in the past -tdm
$v_realpatch = $v_patch;

// Database version identifier, this is to be incremented whenever there
// is a database change in the course of development.  It is used
// internally to determine when a database upgrade is needed.
//
$v_database = '2';

// Access control version identifier, this is to be incremented whenever there
// is a access control change in the course of development.  It is used
// during installation to determine what the installed version of the access
// controls is (subsequently the acl_upgrade.php script then is used to
// upgrade and track this value)
//
$v_acl = 0;

?>
