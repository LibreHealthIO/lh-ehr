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
    <head>
        <link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
    </head>
<body class="body_top">
<?php if (acl_check('admin', 'practice' )) { ?>
<span class='title' visibility: hidden>Install MIPS Module</span>
<h1>Install/Update MIPS reporting database tables</h1>
<b>This tool truncates all data from previous versions and installs the current database tables required for accurate MIPS reporting.</b>
<form action="mips_installer.php" method="post">	
<?php
if($_POST['formSubmit'] == "Submit") 
{

include_once("SQL/1_INSTALL/PQRS_billingcodes.php");
echo "Installed HCPCS codes <br>";
include_once("SQL/1_INSTALL/PQRS_clinical_rules.php");
echo "Installed Clinical Rules <br>";
include_once("SQL/1_INSTALL/PQRS_direct_entry_table.php");
echo "Installed Direct Entry Table <br>";
include_once("SQL/1_INSTALL/PQRS_codes_efcc1.php");
echo "Installed Effective Clinical Care part 1 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_efcc2.php");
echo "Installed Effective Clinical Care part 2 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_efcc3.php");
echo "Installed Effective Clinical Care part 3 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_efcc4.php");
echo "Installed Effective Clinical Care part 4 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_efcc5.php");
echo "Installed Effective Clinical Care part 5 <br>";
include_once("SQL/1_INSTALL/PQRS_codes_mips.php");
echo "Installed 2018 MIPS additional codes <br>";
include_once("SQL/1_INSTALL/PQRS_codes_poph.php");
echo "Installed Population Health <br>";
include_once("SQL/1_INSTALL/PQRS_codes_ptct.php");
echo "Installed Patient Centered Outcomes <br>";
include_once("SQL/1_INSTALL/PQRS_codes_ptsf.php");
echo "installed Patient Safety <br>";
include_once("SQL/1_INSTALL/PQRS_codes_ccco.php");
echo "installed Continuity and Coordination of Care <br>";
include_once("SQL/1_INSTALL/PQRS_codes_ecr.php");
echo "installed Efficience and Cost Reduction <br>";
include_once("SQL/1_INSTALL/HCC_tables.php");
echo "installed Health Condition Category tables <br>";
include_once("SQL/1_INSTALL/category_rates_table.php");
echo "installed HCC category rates table <br>";


echo "MIPS module updated!";
}else{
    echo "<input type='submit' name='formSubmit' value='Submit' />";}
?>
<?php }
else {echo "You do not have access to this feature.";}
?>
</form>
</body>
</html>

