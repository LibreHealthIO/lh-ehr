<?php
/*
 * MIPS table/module installer
 * Copyright (C) 2015 - 2018      Suncoast Connection
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @author  Bryan lee <leebc@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */
require_once '../../interface/globals.php';
include_once("$srcdir/api.inc");
include_once("$srcdir/acl.inc");
?>	
<html>
<?php if (acl_check('admin', 'practice' )) { ?>
<span class='title' visibility: hidden>Install MIPS Module</span>
<h1>Install/Update MIPS reporting database tables</h1>
<b>This tool truncates all data from previous versions and installs the current database tables required for accurate MIPS reporting.</b>
<form action="mips_installer.php" method="post">	
<?php
if($_POST['formSubmit'] == "Submit") 
{

include_once("SQL/1_INSTALL/PQRS_billingcodes.php");
echo "1 <br>";
include_once("SQL/1_INSTALL/PQRS_clinical_rules.php");
echo "2 <br>";
include_once("SQL/1_INSTALL/PQRS_direct_entry_table.php");
echo "3 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_efcc1.php");
echo "4 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_efcc2.php");
echo "5 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_efcc3.php");
echo "6 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_efcc4.php");
echo "7 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_efcc5.php");
echo "8 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_mips.php");
echo "9 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_poph.php");
echo "10 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_ptct.php");
echo "11 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_ptsf.php");
echo "12 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_ccco.php");
echo "13 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_ecr.php");
echo "14 <br>";


echo "MIPS module updated!";
}else{
    echo "<input type='submit' name='formSubmit' value='Submit' />";}
?>
<?php }
else {echo "You do not have access to this feature.";}
?>
</form>
</html>

