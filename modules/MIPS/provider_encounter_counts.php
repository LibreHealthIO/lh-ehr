<?php
/*
 * Provider Encounter report
 *
 * Copyright (C) 2015 - 2017      Suncoast Connection
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

$totalMedicare=0;
$totalEncounters=0;
?>	
<html>
<head>
<?php html_header_show();?>
<title><?php xlt('Provider Totals'); ?></title>


<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">

/* specifically include & exclude from printing */
@media print {
    #report_parameters {
        visibility: hidden;
        display: none;
    }
    #report_parameters_daterange {
        visibility: visible;
        display: inline;
		margin-bottom: 10px;
    }
    #report_results table {
       margin-top: 0px;
    }
}

/* specifically exclude some from the screen */
@media screen {
	#report_parameters_daterange {
		visibility: hidden;
		display: none;
	}
	#report_results {
		width: 100%;
	}
}

</style>

</head>

<body class="body_top">

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Rendering Provider Encounter Totals Report','e'); ?></span>

<div id="report_results">

<table>
 <thead>
  <th> <?php xl('NPI','e'); ?> </th>
  <th> <?php xl('Provider Last Name','e'); ?> </th>
  <th> <?php xl('Provider First Name','e'); ?> </th>
  <th> <?php xl('Medicare Encounters','e'); ?> </th>
  <th> <?php xl('Total Encounters','e'); ?> </th>
 </thead>
 <tbody>
<?php
$query = 
" select distinct (provider_id) as provider from form_encounter " .
";";
$myProviderList=sqlStatement($query);


foreach($myProviderList as $row) {
	$myProvider=$row['provider'];
	
	echo "  <tr>\n";
	$query=
	"SELECT `id` as id, `fname` as fname, `lname` as lname FROM users ".
	" WHERE id = ".$myProvider.";";
	$result = sqlFetchArray(sqlStatement($query));
	echo "   <td>".htmlspecialchars($myProvider)."</td>\n";
	echo "   <td>".htmlspecialchars($result['lname'])."</td>\n";
	echo "   <td>".htmlspecialchars($result['fname'])."</td>\n"; 

// Total Medicare Encounters
	$query1=
	"SELECT COUNT(  DISTINCT fe.encounter ) as count ".
	" FROM form_encounter AS fe ".
	" LEFT OUTER JOIN insurance_data i on (i.pid=fe.pid) ".
	" INNER JOIN insurance_companies c on (c.id = i.provider) ".
	" WHERE c.ins_type_code = 2 ".
	" AND i.type='primary' ".
	" AND fe.provider_id = ".$myProvider.";";
	$result=sqlFetchArray(sqlStatement($query1));
	$myCount1=$result['count'];
	echo "   <td>".$myCount1."</td>\n"; 
	$totalMedicare+=$result['count'];

// Total Encounters
	$query2=
	"SELECT COUNT(*) as count ".
	" FROM form_encounter AS fe ".
	" WHERE fe.provider_id = ".$myProvider.";";
	$result=sqlFetchArray(sqlStatement($query2));
	$myCount2=$result['count'];
	echo "   <td>".$myCount2."</td>\n";

	echo "  </tr>\n";
	$totalEncounters+=$result['count'];
}
echo " <tr class=\"report_totals\">\n";
echo "  <td colspan='3'>\n";
echo xl('Total Number of Encounters','e');
echo ":\n  </td>\n  <td colspan='1'>\n   ".$totalMedicare."\n  </td> ";
echo "\n  <td colspan='1'>\n   ".$totalEncounters."\n  </td> </tr>";

?>
 </tbody>
</table>

</html>
