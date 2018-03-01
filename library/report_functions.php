<?php
/*
 * These functions are common functions used in the reports. They have pulled out of different
 * reports and placed in this library file. This is done to prepare the for building a
 * report generator.
 *
 * Copyright (C) 2018 Tigpezeghe Rodrige <tigrodrige@gmail.com>
 * Copyright (C) 2015-2017 Terry Hill <teryhill@librehealth.io> 
 * Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Tigpezeghe Rodrige <tigrodrige@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */

/*
 * This function builds a dropdown list of providers
 * @params None
 * @return void - Simply echo HTML encoded string
 */
function dropDownProviders() {
	$query = "SELECT id, lname, fname FROM users WHERE ".
             "authorized = 1  ORDER BY lname, fname"; #(CHEMED) facility filter
    $ures = sqlStatement($query);

    echo "   <select name='form_provider'>\n";
    echo "    <option value=''>-- " . xlt('All Providers') . " --\n";

    while ($urow = sqlFetchArray($ures)) {
        $provid = $urow['id'];
        echo "    <option value='" . attr($provid) . "'";
        if ($provid == $_POST['form_provider']) echo " selected";
        echo ">" . text($urow['lname']) . ", " . text($urow['fname']) . "\n";
        if ($provid == $_POST['form_provider']) $provider_name = $urow['lname'] . ", " . $urow['fname'];
    }

	echo "   </select>\n";
}

/*
 * This function builds a dropdown list of facilities
 * @params None
 * @return void - Simply echo HTML encoded string
 */
function dropDownFacilities() {                 
	$query = "SELECT id, name FROM facility ORDER BY name";
    $fres = sqlStatement($query);

    echo "   <select name='form_facility'>\n";
    echo "    <option value=''>-- " . xlt('All Facilities', 'e') . " --\n";

    while ($frow = sqlFetchArray($fres)) {
        $facid = $frow['id'];
        echo "    <option value='$facid'";
        if ($facid == $form_facility) echo " selected";
        echo ">" . htmlspecialchars($frow['name']) . "\n";
    }

    echo "    <option value='0'";
    if ($form_facility === '0') echo " selected";
    echo ">-- " . xl('Unspecified') . " --\n";
    echo "   </select>\n";
}

?>
