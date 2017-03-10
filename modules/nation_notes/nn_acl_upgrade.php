<?php
/**
* Add GACL sections for Nation Notes.

* @package   LibreEHR
* @author    Art Eaton <art@suncoastconnection.com>
* @link      http://LibreHealth.io
*/

$ignoreAuth = true; 

require_once('../../interface/globals.php');
require_once("$srcdir/acl_upgrade_fx.php");

include_once('$srcdir/acl.inc');
if (isset ($phpgacl_location)) {
	include_once("$phpgacl_location/gacl_api.class.php");
	$gacl = new gacl_api();
}
else {
	die("library/acl.inc not found!!");
}

$acl_version = get_acl_version();
if (empty($acl_version)) {
$acl_version = 0;
}

// Upgrade against baseline
require_once('nn_config.inc');

if ($acl_version < $upgrade_acl) {
  echo "<B>Upgrading GACL for Nation Notes :</B></BR>";

 echo "<B>Checking to ensure all the proper ACL(access control list) are present:</B></BR>";
 $admin_write = getAclIdNumber('Administrators', 'write');
 $doc_write = getAclIdNumber('Physicians', 'write');
 $clin_write = getAclIdNumber('Clinicians', 'write');
 $clin_addonly = getAclIdNumber('Clinicians', 'addonly');
 $front_write = getAclIdNumber('Front Office', 'write');
 $back_write = getAclIdNumber('Accounting', 'write');
 echo "<BR/><B>Adding new object sections</B><BR/>";
 addObjectSectionAcl('nationnotes','Nation Notes');
 addObjectAcl('nationnotes', 'Nation Notes', 'nn_configure', 'Nation Notes Configure');
 updateAcl($admin_write, 'Administrators','nationnotes', 'Nation Notes', 'nn_configure', 'Nation Notes Configure','write');
 updateAcl($emergency_write, 'Emergency Login','nationnotes', 'Nation Notes', 'nn_configure', 'Nation Notes Configure','write');

  $acl_version = $upgrade_acl;
}


//All done
set_acl_version($acl_version);
echo "DONE upgrading access controls";

?>
