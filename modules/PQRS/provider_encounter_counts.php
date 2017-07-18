<?php
/*
 * Provider Encounter report
 *
 * Copyright (C) 2017      Suncoast Connection
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package LibreEHR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 * @author  Bryan Lee  leebc11 at acm dot org
*/
require_once("../../globals.php");
include_once("$srcdir/api.inc");

$totalMedicare=0;
$totalEncounters=0;
?>	
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Provider Totals','e'); ?></title>
<script type="text/javascript" src="../../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<script type="text/javascript" src="../../../library/js/jquery.1.3.2.js"></script>

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

#echo ( var_dump($myProviderList) ) ;
//echo "myProviderList = ".$myProviderList;

foreach($myProviderList as $row) {
	$myProvider=$row['provider'];
	//echo "<p> provider_id = ".$myProvider;
	
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
	" WHERE c.freeb_type = 2 ".
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
echo "\n  <td colspan='1'>\n   ".$totalEncounters."\n  </td>
 </tr>
";

?>
 </tbody>
</table>

</html>
