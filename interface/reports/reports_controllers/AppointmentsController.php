<?php
/*
 * These functions are common functions used in the Appointments reports. They have pulled out 
 * and placed in this file. This is done to prepare the for building a
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

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../globals.php");
require_once("../../library/patient.inc");
require_once("../../library/report_functions.php");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/formdata.inc.php";
require_once "$srcdir/appointments.inc.php";
require_once "$srcdir/clinical_rules.php";
require_once("$srcdir/headers.inc.php");
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

# Clear the pidList session whenever load this page.
# This session will hold array of patients that are listed in this 
# report, which is then used by the 'Superbills' and 'Address Labels'
# features on this report.
unset($_SESSION['pidList']);

$alertmsg = ''; // not used yet but maybe later

if ($patient && ! $_POST['form_from_date']) {
    // If a specific patient, default to 2 years ago.
    $tmp = date('Y') - 2;
    $from_date = date("$tmp-m-d");
} else {
    $from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
    $to_date = fixDate($_POST['form_to_date'], date('Y-m-d'));
}

$show_available_times = false;
if ( $_POST['form_show_available'] ) {
    $show_available_times = true;
}

$chk_with_out_provider = false;
if ( $_POST['with_out_provider'] ) {
    $chk_with_out_provider = true;
}

$chk_with_out_facility = false;
if ( $_POST['with_out_facility'] ) {
    $chk_with_out_facility = true;
}

//$to_date   = fixDate($_POST['form_to_date'], '');
$provider  = $_POST['form_provider'];
$facility  = $_POST['form_facility'];  //(CHEMED) facility filter
$form_orderby = getComparisonOrder( $_REQUEST['form_orderby'] ) ?  $_REQUEST['form_orderby'] : 'date';

// Reminders related stuff
$incl_reminders = isset($_POST['incl_reminders']) ? 1 : 0;

/* Attribution: 2015-2017 Terry Hill <teryhill@librehealth.io> 
 * and 2005-2010 Rod Roark <rod@sunsetsystems.com>*/
function fetch_rule_txt ($list_id, $option_id) {
    $rs = sqlQuery('SELECT title, seq from list_options WHERE list_id=? AND option_id=?',
            array($list_id, $option_id));
    $rs['title'] = xl_list_label($rs['title']);
    return $rs;
}

/* Attribution: 2015-2017 Terry Hill <teryhill@librehealth.io> 
 * and 2005-2010 Rod Roark <rod@sunsetsystems.com>*/
function fetch_reminders($pid, $appt_date) {
    $rems = test_rules_clinic('','passive_alert',$appt_date,'reminders-due',$pid);
    $seq_due = array();
    $seq_cat = array();
    $seq_act = array();
    foreach ($rems as $ix => $rem) {
        $rem_out = array();
        $rule_txt = fetch_rule_txt ('rule_reminder_due_opt', $rem['due_status']);
        $seq_due[$ix] = $rule_txt['seq'];
        $rem_out['due_txt'] = $rule_txt['title'];
        $rule_txt = fetch_rule_txt ('rule_action_category', $rem['category']);
        $seq_cat[$ix] = $rule_txt['seq'];
        $rem_out['cat_txt'] = $rule_txt['title'];
        $rule_txt = fetch_rule_txt ('rule_action', $rem['item']);
        $seq_act[$ix] = $rule_txt['seq'];
        $rem_out['act_txt'] = $rule_txt['title'];
        $rems_out[$ix] = $rem_out;
    }
    array_multisort($seq_due, SORT_DESC, $seq_cat, SORT_ASC, $seq_act, SORT_ASC, $rems_out);
    $rems = array();
    foreach ($rems_out as $ix => $rem) {
        $rems[$rem['due_txt']] .= (isset($rems[$rem['due_txt']]) ? ', ':'').
            $rem['act_txt'].' '.$rem['cat_txt'];
    }
    return $rems;
}

/*
 * This function displays a drop down the different categories.
 * @params: None
 * @return: void
 * @author: Tigpezeghe Rodrige <tigrodrige@gmail.com>
 */
function dropDownCategories() {
	$categories=fetchAppointmentCategories();
    echo "<option value='ALL'>".xlt("All Categories")."</option>";
    while($cat=sqlFetchArray($categories))
    {
        echo "<option value='".attr($cat['id'])."'";
        if($cat['id']==$_POST['form_apptcat'])
        {
            echo " selected='true' ";
        }
        echo ">".text(xl_appt_category($cat['category']))."</option>";
    }
}

/*
 * This function prepares appointments for printing.
 * @params: None
 * @return: void
 * @author: Tigpezeghe Rodrige <tigrodrige@gmail.com>
 */
function prepareAppointments() {}


?>
