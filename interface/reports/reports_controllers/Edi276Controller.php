<?php
/*
 * These functions are common functions used in Eligibility Response (EDI_271 File Upload) report.
 * They have been pulled out and placed in this file. This is done to prepare
 * the for building a report generator.
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
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

//	START - INCLUDE STATEMENTS
require_once("../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/formdata.inc.php";
include_once("$srcdir/calendar.inc");
include_once("$srcdir/edi_276.inc.php");
require_once("../../library/report_functions.php");

$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

// Element data seperator
$eleDataSep     = "*";

// Segment Terminator
$segTer         = "~";

// Component Element seperator
$compEleSep     = ":";

// filter conditions for the report and batch creation

$from_date      = fixDate($_POST['form_from_date'], date($DateFormat));
$to_date        = fixDate($_POST['form_to_date'], date($DateFormat));
$form_facility  = $_POST['form_facility'] ? $_POST['form_facility'] : '';
$form_provider  = $_POST['form_users'] ? $_POST['form_users'] : '';
$exclude_policy = $_POST['removedrows'] ? $_POST['removedrows'] : '';
$X12info        = $_POST['form_x12'] ? explode("|",$_POST['form_x12']) : '';

//Set up the sql variable binding array (this prevents sql-injection attacks)
$sqlBindArray = array();

$where  = "e.pid IS NOT NULL AND e.Date >= ?";
array_push($sqlBindArray, $from_date);

if ($to_date) {
    $where .= " AND e.Date <= ?";
    array_push($sqlBindArray, $to_date);
}

if($form_facility != "") {
    $where .= " AND f.id = ? ";
    array_push($sqlBindArray, $form_facility);
}

if($form_provider != "") {
    $where .= " AND d.id = ? ";
    array_push($sqlBindArray, $form_provider);
}

if($exclude_policy != "") {
	$arrayExplode   =   explode(",", $exclude_policy);
    array_walk($arrayExplode, 'arrFormated');
    $exclude_policy = implode(",",$arrayExplode);
    $where .= " AND i.policy_number not in (".stripslashes($exclude_policy).")";
}

$where .= " AND (i.policy_number is not null and i.policy_number != '')";

$query = sprintf("      SELECT DATE_FORMAT(e.Date, '%%Y%%m%%d') as enc_dosDate,
                                e.billing_facility,
                                p.lname,
                                p.fname,
                                p.mname,
                                DATE_FORMAT(p.dob, '%%Y%%m%%d') as dob,
                                p.ss,
                                p.sex,
                                p.pid,
                                p.pubpid,
                                i.policy_number,
                                i.provider as payer_id,
                                i.subscriber_relationship,
                                i.subscriber_lname,
                                i.subscriber_fname,
                                i.subscriber_mname,
                                DATE_FORMAT(i.subscriber_dob, '%%m/%%d/%%Y') as subscriber_dob,
                                i.subscriber_ss,
                                i.subscriber_sex,
                                DATE_FORMAT(i.date,'%%Y%%m%%d') as date,
                                d.lname as provider_lname,
                                d.fname as provider_fname,
                                d.npi as provider_npi,
                                d.upin as provider_pin,
                                f.federal_ein as federal_ein,
                                f.facility_npi as facility_npi,
                                f.name as facility_name,
                                c.name as payer_name
                        FROM form_encounter AS e
                        LEFT JOIN users AS d on (e.provider_id is not null and e.provider_id = d.id)
                        LEFT JOIN facility AS f on (f.id = e.billing_facility)
                        LEFT JOIN patient_data AS p ON p.pid = e.pid
                        LEFT JOIN insurance_data AS i ON (i.id =(
                                                                SELECT id
                                                                FROM insurance_data AS i
                                                                WHERE pid = p.pid AND type = 'primary'
                                                                ORDER BY date DESC
                                                                LIMIT 1
                                                            )
                                                        )
                        LEFT JOIN insurance_companies as c ON (c.id = i.provider)
                        WHERE %s ", $where );

// Run the query
$res            = sqlStatement($query, $sqlBindArray);

// Get the facilities information
$facilities     = getUserFacilities($_SESSION['authId']);

// Get the Providers information
$providers      = getUsernames();

//Get the x12 partners information
$clearinghouses = getX12Partner();


if (isset($_POST['form_savefile']) && !empty($_POST['form_savefile']) && $res) {
    header('Content-Type: text/plain');
    header(sprintf('Content-Disposition: attachment; filename="stat-276..%s.%s.txt"',
        $from_date,
        $to_date
    ));
    print_clstr($res,$X12info,$segTer,$compEleSep);
    exit;
}

?>
