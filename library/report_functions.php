<?php
/*
 * These functions are common functions used in the reports. They have pulled out of different
 * reports and placed in this library file. This is done to prepare the for building a
 * report generator.
 *
 * Copyright (C) 2018 Tigpezeghe Rodrige <tigrodrige@gmail.com>
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

/*
 * This function builds a dropdown of payors.
 * Used in encounters_report_carrier and collections_report
 * @params None
 * @return void - Simply echo HTML encoded string
 */
function dropDownPayors() {
    $insurancei = getInsuranceProviders();
    echo "   <select name='form_payer_id'>\n";
    echo "    <option value='0'>-- " . xlt('All Payors') . " --</option>\n";
    foreach ($insurancei as $iid => $iname) {
        echo "<option value='" . attr($iid) . "'";
        if ($iid == $_POST['form_payer_id']) echo " selected";
        echo ">" . text($iname) . "</option>\n";
        if ($iid == $_POST['form_payer_id']) $ins_co_name = $iname;
    }
    echo "   </select>\n";
}

/*
 * This function shows From and To dates
 * @return: Echo Html
 */
function showFromAndToDates() {
    echo "<td class='label'>";
    echo xlt('From');
    echo ":
        </td>
         <td>
            <input type='text' name='form_from_date' id='form_from_date' size='10'
                value='"; htmlspecialchars(oeFormatShortDate(attr($from_date))); 
    echo "' />
         </td>
         <td class='label'> ";
    echo xlt('To'); 
    echo ":
         </td>
         <td>
            <input type='text' name='form_to_date' id='form_to_date' size='10'
                value=' "; htmlspecialchars(oeFormatShortDate(attr($to_date)));
    echo "' />
         </td>";
}

/*
 * This function shows the patients' age range
 * in clinical and patient_list_creation reports
 * @return: Echo Html
 */
function showPatientAgeRange() {
    echo "<td class='label'>";
        echo xlt('Age'); 
    echo ":</td>
    <td>"; 
        echo xlt('From');
            echo "<input name='age_from' class='numeric_only' type='text' id='age_from' value='";echo attr($age_from);
            echo "' size='3' maxlength='3'/>";
        echo xlt('To{{range}}');
            echo "<input name='age_to' class='numeric_only' type='text' id='age_to' value='";echo attr($age_to);
            echo "' size='3' maxlength='3'/>
    </td>";
}

/*
 * This function shows the Submit button
 * It equally displays Print and Export to CSV buttons
 * @params form_csvexport : Tells whether to display CSV export button
 * @return void - Simply echo HTML encoded string
 */
function showSubmitPrintButtons($form_csvexport='') {
    echo "<td align='left' valign='middle' height='100%'>
        <table style='border-left:1px solid; width:80%; height:100%; margin-left:3%;'>
            <tr>
                <td>
                    <div style='margin-left:5px'>
                        <a href='#' class='css_button' onclick='$(\"#form_refresh\").attr(\"value\",\"true\"); $(\"#theform\").submit();'>
                            <span>";
                                echo xlt('Submit');
                            echo "</span>
                        </a>";
                        if ($_POST['form_refresh']) {
                            echo "<a href='#' class='css_button' id='printbutton'>
                                <span>";
                                    echo xlt('Print');
                                echo "</span>
                            </a>";
                            if ($form_csvexport == 'form_csvexport') {
                                echo "<a href='#' class='css_button' onclick='$(\"#form_csvexport\").attr(\"value\",\"true\"); $(\"#theform\").submit();'>
                                    <span>";
                                        echo xlt('Export to CSV');
                                    echo "</span>
                                </a>";
                            }
                            if ($form_csvexport == 'form_labels') { // This is for Unique-seen-patients report
                                echo "<a href='#' class='css_button' onclick='$(\"#form_labels\").attr(\"value\",\"true\"); $(\"#theform\").submit();'>
                                    <span>";
                                        echo xl('Labels','e');
                                    echo "</span>
                                </a>";
                            }
                        }
                    echo "</div>
                </td>
            </tr>
        </table>
    </td>";
}

/*
 * This function just displays the report daterange
 * @params None
 * @return void - Simply echo HTML encoded string
 */
function reportParametersDaterange() {
    echo '<div id="report_parameters_daterange">';
            date("d F Y", strtotime(oeFormatDateForPrintReport($_POST['form_from_date'])))
        . " &nbsp; to &nbsp; ". date("d F Y", strtotime(oeFormatDateForPrintReport($_POST['form_to_date'])));
    echo '</div>';
}

/*
 * This function is for csv export of reports
 * @params : $filename - Name of associated report
 * @return :void 
 */
function csvexport($filename) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=" . $filename . ".csv");
    header("Content-Description: File Transfer");
}

?>
