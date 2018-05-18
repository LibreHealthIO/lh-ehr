<?php
/*
 * These functions are common functions used in Patient Ledger report.
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

$sanitize_all_escapes=true;
$fake_register_globals=false;
require_once('../globals.php');
require_once("../../library/report_functions.php");
require_once($GLOBALS['srcdir'].'/patient.inc');
require_once($GLOBALS['srcdir'].'/acl.inc');
require_once("$srcdir/headers.inc.php");
require_once($GLOBALS['srcdir'].'/formatting.inc.php');
require_once($GLOBALS['srcdir'].'/options.inc.php');
require_once($GLOBALS['srcdir'].'/formdata.inc.php');
require_once($GLOBALS['srcdir'].'/appointments.inc.php');
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

$enc_units = $total_units = 0;
$enc_chg = $total_chg = 0;
$enc_pmt = $total_pmt = 0;
$enc_adj = $total_adj = 0;
$enc_bal = $total_bal = 0;
$bgcolor = "#FFFFDD";
$orow = 0;

$pat_pid = $_GET['patient_id'];
$type_form = $_GET['form'];

if (! acl_check('acct', 'rep')) die(xlt("Unauthorized access."));

function GetAllUnapplied($pat='',$from_dt='',$to_dt='') {
  $all = array();
  if(!$pat) return($all);
  $sql = "SELECT ar_session.*, ins.name, " .
      "pat.lname, pat.fname, pat.mname, " .
      "(SELECT SUM(ar_activity.pay_amount) FROM ar_activity WHERE " .
      "ar_activity.session_id = ar_session.session_id) AS applied " .
      "FROM ar_session " .
      "LEFT JOIN insurance_companies AS ins on ar_session.payer_id = ins.id " .
      "LEFT JOIN patient_data AS pat on ar_session.patient_id = pat.pid " .
      "WHERE " .
      "ar_session.created_time >= ? AND ar_session.created_time <= ? " .
      "AND ar_session.patient_id=?";
  $result = sqlStatement($sql, array($from_dt, $to_dt, $pat));
  $iter = 0;
  while($row = sqlFetchArray($result)) {
    $all[$iter] = $row;
    $iter++;
  }
    return($all);
}

function User_Id_Look($thisField) {
  if(!$thisField) return '';
  $ret = '';
  $rlist= sqlStatement("SELECT lname, fname, mname FROM users WHERE id=?",array($thisField));
  $rrow= sqlFetchArray($rlist);
  if($rrow) {
    $ret = $rrow{'lname'}.', '.$rrow{'fname'}.' '.$rrow{'mname'};
  }
  return $ret;
}

function List_Look($thisData, $thisList) {
  if($thisList == 'occurrence') {
    if(!$thisData || $thisData == '') return xl('Unknown or N/A');
  }
  if($thisData == '') return '';
  $fres=sqlStatement("SELECT title FROM list_options WHERE list_id=? ".
        "AND option_id=?", array($thisList, $thisData));
  if($fres) {
    $rret=sqlFetchArray($fres);
    $dispValue= xl_list_label($rret{'title'});
    if($thisList == 'occurrence' && $dispValue == '') {
      $dispValue = xl('Unknown or N/A');
    }
  } else {
    $dispValue= xl('Not Found');
  }
  return $dispValue;
}

function GetAllCredits($enc = '', $pat='') {
    $all = array();
    if(!$enc || !$pat) return($all);
    $sql = "SELECT activity.*, session.*, ins.name FROM ar_activity AS ".
    "activity LEFT JOIN ar_session AS session USING (session_id) ".
    "LEFT JOIN insurance_companies AS ins ON session.payer_id = ".
    "ins.id WHERE encounter=? AND pid=? ".
    "ORDER BY sequence_no";
    $result = sqlStatement($sql, array($enc, $pat));
    $iter = 0;
    while($row = sqlFetchArray($result)) {
      $all[$iter] = $row;
      $iter++;
    }
    return($all);
}
function PrintEncHeader($dt, $rsn, $dr) {
    global $bgcolor, $orow;
    $bgcolor = (($bgcolor == "#FFFFDD") ? "#FFDDDD" : "#FFFFDD");
    echo "<tr bgcolor='#FFFFFF'>";
    if(strlen($rsn) > 50) $rsn = substr($rsn,0,50).'...';
    echo "<td colspan='4'><span class='bold'>".xlt('Encounter Dt / Rsn'). ": </span><span class='detail'>".text(substr($dt,0,10))." / ".text($rsn)."</span></td>";
    echo "<td colspan='5'><span class='bold'>" . xlt('Provider'). ": </span><span class='detail'>".text(User_Id_Look($dr))."</span></td>";
    echo "</tr>\n";
    $orow++;
}
function PrintEncFooter() {
    global $enc_units, $enc_chg, $enc_pmt, $enc_adj, $enc_bal;
    echo "<tr bgcolor='#DDFFFF'>";
    echo "<td colspan='3'>&nbsp;</td>";
    echo "<td class='detail'>". xlt('Encounter Balance').":</td>";
    echo "<td class='detail' style='text-align: right;'>".text($enc_units)."</td>";
    echo "<td class='detail' style='text-align: right;'>".text(oeFormatMoney($enc_chg))."</td>";
    echo "<td class='detail' style='text-align: right;'>".text(oeFormatMoney($enc_pmt))."</td>";
    echo "<td class='detail' style='text-align: right;'>".text(oeFormatMoney($enc_adj))."</td>";
    echo "<td class='detail' style='text-align: right;'>".text(oeFormatMoney($enc_bal))."</td>";
    echo "</tr>\n";
}
function PrintCreditDetail($detail, $pat, $unassigned=false) {
    global $enc_pmt, $total_pmt, $enc_adj, $total_adj, $enc_bal, $total_bal;
    global $bgcolor, $orow, $enc_units, $enc_chg;
    foreach($detail as $pmt) {
        if($unassigned) {
           if(($pmt['pay_total'] - $pmt['applied']) == 0) continue;
        }
    $bgcolor = (($bgcolor == "#FFFFDD") ? "#FFDDDD" : "#FFFFDD");
    $print = "<tr bgcolor='" . attr($bgcolor) . "'>";
    $print .= "<td class='detail'>&nbsp;</td>";
    $method = List_Look($pmt['payment_method'],'payment_method');
    $desc = $pmt['description'];
    $ref = $pmt['reference'];
    if($unassigned) {
      $memo = List_Look($pmt['adjustment_code'],'payment_adjustment_code');
    } else {
          $memo = $pmt['memo'];
    }
        $description = $method;
        if($ref) {
            if($description) { $description .= ' - '; }
            $description .= $ref;
        }
        if($desc) {
            if($description) { $description .= ': '; }
            $description .= $desc;
        }
        if($memo) {
            if($description) { $description .= ' '; }
            $description .= '['.$memo.']';
        }
        $print .= "<td class='detail' colspan='2'>".
                                      text($description)."&nbsp;</td>";
        $payer = ($pmt['name'] == '') ? xl('Patient') : $pmt['name'];
    if($unassigned) {
      $pmt_date = substr($pmt['post_to_date'],0,10);
    } else {
      $pmt_date = substr($pmt['post_time'],0,10);
    }
    $print .= "<td class='detail'>".
    text($pmt_date)."&nbsp;/&nbsp;".text($payer)."</td>";
    $type = List_Look($pmt['payment_type'],'payment_type');
    $print .= "<td class='detail'>".text($type)."&nbsp;</td>";
    if($unassigned) {
          $pmt_amt = $pmt['pay_total'] - $pmt['applied'];
          $uac_bal = oeFormatMoney($pmt_amt * -1);
          $uac_appl = oeFormatMoney($pmt['applied']);
          $uac_total = oeFormatMoney($pmt['pay_total']);
          $pmt_amt = $pmt['pay_total'];
          $total_pmt = $total_pmt - $uac_bal;
    } else {
          $uac_total = '';
          $uac_bal = '';
          $uac_appl = '';
          $pmt_amt = $pmt['pay_amount'];
          $adj_amt = $pmt['adj_amount'];
          $enc_pmt = $enc_pmt + $pmt['pay_amount'];
          $total_pmt = $total_pmt + $pmt['pay_amount'];
          $enc_adj = $enc_adj + $pmt['adj_amount'];
          $total_adj = $total_adj + $pmt['adj_amount'];
    }
        $print_pmt = '';
        if($pmt_amt != 0) $print_pmt = oeFormatMoney($pmt_amt);
        $print_adj = '';
        if($adj_amt != 0) $print_adj = oeFormatMoney($adj_amt);
        $print .= "<td class='detail' style='text-align: right;'>".text($uac_appl)."&nbsp;</td>";
        $print .= "<td class='detail' style='text-align: right;'>".text($print_pmt)."&nbsp;</td>";
        $print .= "<td class='detail' style='text-align: right;'>".text($print_adj)."&nbsp;</td>";
        $print .= "<td class='detail' style='text-align: right;'>".text($uac_bal)."&nbsp;</td>";
        $print .= "</tr>\n";
        echo $print;
        if($pmt['follow_up_note'] != '') {
          $bgcolor = (($bgcolor == "#FFFFDD") ? "#FFDDDD" : "#FFFFDD");
          $print = "<tr bgcolor='". attr($bgcolor) ."'>";
          $print .= "<td class='detail' colspan='2'>&nbsp;</td>";
          $print .= "<td colspan='7'>". xlt('Follow Up Note') .": ";
          $print .= text($pmt['follow_up_note']);
          $print .= "</td></tr>\n";
          echo $print;
        }
        if($unassigned) {
          $total_bal = $total_bal + $uac_bal;
        } else {
          $enc_bal = $enc_bal - $pmt_amt - $adj_amt;
          $total_bal = $total_bal - $pmt_amt - $adj_amt;
        }
        $orow++;
  }
  $bgcolor = (($bgcolor == "#FFFFDD") ? "#FFDDDD" : "#FFFFDD");
}
if(!isset($_REQUEST['form_from_date'])) { $_REQUEST['form_from_date'] = ''; }
if(!isset($_REQUEST['form_to_date'])) { $_REQUEST['form_to_date'] = ''; }
if(!isset($_REQUEST['form_facility'])) { $_REQUEST['form_facility'] = ''; }
if(!isset($_REQUEST['form_provider'])) { $_REQUEST['form_provider'] = ''; }
if($type_form=='0') {
    if(!isset($_REQUEST['form_patient'])) { $_REQUEST['form_patient'] = ''; }
    if(!isset($_REQUEST['form_pid'])) { $_REQUEST['form_pid'] = ''; }
}
else
{
    if(!isset($_REQUEST['form_patient'])) { $_REQUEST['form_patient'] = $pat_pid; }
    if(!isset($_REQUEST['form_pid'])) { $_REQUEST['form_pid'] = $pat_pid; }
}
if(!isset($_REQUEST['form_csvexport'])) { $_REQUEST['form_csvexport'] = ''; }
if(!isset($_REQUEST['form_refresh'])) { $_REQUEST['form_refresh'] = ''; }
if(!isset($_REQUEST['$form_dob'])) { $_REQUEST['$form_dob'] = ''; }

if (substr($GLOBALS['ledger_begin_date'],0,1) == 'Y') {
   $ledger_time = substr($GLOBALS['ledger_begin_date'],1,1);
   $last_year = mktime(0,0,0,date('m'),date('d'),date('Y')-$ledger_time);
}
elseif (substr($GLOBALS['ledger_begin_date'],0,1) == 'M') {
   $ledger_time = substr($GLOBALS['ledger_begin_date'],1,1);
   $last_year = mktime(0,0,0,date('m')-$ledger_time ,date('d'),date('Y'));
}
elseif (substr($GLOBALS['ledger_begin_date'],0,1) == 'D') {
   $ledger_time = substr($GLOBALS['ledger_begin_date'],1,1);
   $last_year = mktime(0,0,0,date('m') ,date('d')-$ledger_time,date('Y'));
}

$from_date = date('Y-m-d', $last_year);
if($_REQUEST['form_from_date']) {
  $from_date = fixDate($_REQUEST['form_from_date'], $last_year);
}
$to_date   = fixDate($_REQUEST['form_to_date']  , date('Y-m-d'));
$form_facility  = $_REQUEST['form_facility'];
$form_provider  = $_REQUEST['form_provider'];
$form_patient   = $_REQUEST['form_patient'];
$form_pid       = $_REQUEST['form_pid'];
$form_dob       = $_REQUEST['form_dob'];

/* This prepares and displays results for Patient Ledger report
 * @params: None
 * @return: Echo HTML
 * @author: Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * */
function prepareAndShowResults() {
	$rows = array();
  	$sqlBindArray = array();

    $query = "select b.code_type, b.code, b.code_text, b.pid, b.provider_id, ".
        "b.billed, b.payer_id, b.units, b.fee, b.bill_date, b.id, ".
        "ins.name, ".
        "fe.encounter, fe.date, fe.reason, fe.provider_id ".
        "FROM form_encounter AS fe ".
        "LEFT JOIN billing AS b ON b.pid=fe.pid AND b.encounter=fe.encounter ".
        "LEFT JOIN insurance_companies AS ins ON b.payer_id = ins.id ".
        "LEFT OUTER JOIN code_types AS c ON c.ct_key = b.code_type ".
        "WHERE fe.date >= ? AND fe.date <= ? AND fe.pid = ? ";

    array_push($sqlBindArray,$from_date,$to_date,$form_pid);
    if ($form_facility) {
      	$query .= "AND fe.facility_id = ? ";
      	array_push($sqlBindArray,$form_facility);
    }

    if ($form_provider) {
      	$query .= "AND b.provider_id = ? ";
     	array_push($sqlBindArray,$form_provider);
    }

    $query .= "AND c.ct_proc = '1' ";
    $query .= "AND activity > 0 ORDER BY fe.date, fe.id ";
    $res = sqlStatement($query,$sqlBindArray);

    if ($_REQUEST['form_csvexport']) {
      	// CSV headers:
      	if (true) {
	        echo '"Code/Enc Dt",';
	        echo '"Description",';
	        echo '"Billed/Who",';
	        echo '"Type/Units",';
	        echo '"Chg/Pmt Amount",'."\n";
      	}
    } else {
      	if(!$form_facility) $form_facility = '3';
      	$facility = sqlQuery("SELECT * FROM facility WHERE id=?", array($form_facility));
      	$patient = sqlQuery("SELECT * from patient_data WHERE pid=?", array($form_patient));
      	$pat_dob = $patient['DOB'];
      	$pat_name = $patient['fname']. ' ' . $patient['lname'];

		echo '<div id="report_header">';
			echo '<table width="98%"  border="0" cellspacing="0" cellpadding="0">';
			  	echo '<tr>';
			    	echo '<td class="title" >'; echo text($facility{'name'}); echo '</td>';
			  	echo '</tr>';
			  	echo '<tr>';
			    	echo '<td class="title" >'; echo text($facility{'street'}); echo '</td>';
			  	echo '</tr>';
			  	echo '<tr>';
			    	echo '<td class="title" >'; echo text($facility{'city'}).", ".text($facility{'state'})." ".text($facility{'postal_code'}); echo '</td>';
			  	echo '</tr>';
			  	echo '<tr>';
			    	echo '<td class="title" >'; echo xlt('Phone').': ' .text($facility{'phone'}); echo '</td>';
			  	echo '</tr>';
			  	echo '<tr>';
			    	echo '<td class="title" >'; echo xlt('Tax Id').': ' .text($facility{'federal_ein'}); echo '</td>';
			  	echo '</tr>';
			  	echo '<tr><td>&nbsp;</td></tr>';
			  	echo '<tr>';
			    	echo '<td class="title" >'; echo xlt('Patient Ledger'); echo '</td>';
			  	echo '</tr>';
			    echo '<tr>';
			        $title = xl('All Providers');
			        if($form_provider) { $title = xl('For Provider') . ': '.User_Id_Look($form_provider); }
			    	echo '<td class="title" >'; echo text($title); echo '</td>';
			    echo '</tr>';
			    echo '<tr> ';
			        $title = xl('For Dates') . ': '.$from_date.' - '.$to_date;
			    echo '<td class="title" >'; echo text($title); echo '</td>';
			    echo '</tr>';
			echo '</table>';
			echo '<br/>';
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
			  	echo '<tr>';
			    	echo '<td class="bold" >'; echo xlt('Date') . ':';
			        	echo text(date('Y-m-d')); echo '</td>';
			    	echo '<td class="bold" >'; echo xlt('Patient') . ':';
			        	if($type_form == '1') {
			           		echo text($pat_name); echo '</td>';
			        	}else{
			           		echo text($form_patient); echo '</td>';
			        	}
			    	echo '<td class="bold" >'; echo xlt('DOB') . ':';
			        	if($type_form == '1') {
			           		echo text($pat_dob);echo '</td>';
			        	}else{
			           		echo text($form_dob); echo '</td>';
			        	}
			    	echo '<td class="bold" >'; echo xlt('ID') . ':';
			        	echo text($form_pid);echo '</td>';
			  	echo '</tr>';
			echo '</table>';
		echo '</div>';
		echo '<div id="report_results">';
			echo '<table id="report_table">';
			 	echo '<tr>';
			    	echo '<td class="bold" >'; echo xlt('Code'); echo '</td>';
				    echo '<td colspan="2" class="bold"'; echo xlt('Description'); echo '</td>';
				    echo '<td class="bold" >'; echo xlt('Billed Date') . '/';  echo xlt('Payor'); echo '</td>';
				    echo '<td class="bold" >'; echo xlt('Type'); echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				    	echo xlt('Units'); echo '</td>';
				    echo '<td class="bold" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; echo xlt('Charge'); echo '</td>';
				    echo '<td align="right" class="bold" >&nbsp;&nbsp;'; echo xlt('Payment'); echo '</td>';
				    echo '<td align="right" class="bold" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; echo xlt('Adjustment'); echo '</td>';
				    echo '<td align="right" class="bold" >&nbsp;&nbsp;&nbsp;'; echo xlt('Balance'); echo '</td>';
			 	echo '</tr>';
			 	echo '<tr>';
				    echo '<td>&nbsp;&nbsp;&nbsp;</td>';
				    echo '<td colspan="2" >&nbsp;&nbsp;&nbsp;</td>';
				    echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				    echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				    &nbsp;&nbsp;&nbsp;</td>';
				    echo '<td class="bold" >&nbsp;&nbsp;&nbsp;'; echo xlt('UAC Appl'); echo '</td>';
				    echo '<td align="right" class="bold" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; echo xlt('UAC Tot'); echo '</td>';
				    echo '<td>&nbsp;</td>';
				    echo '<td>&nbsp;</td>';
			 	echo '</tr>';
    }

    $orow = 0;
    $prev_encounter_id = -1;
    $hdr_printed = false;
    $prev_row = array();

    while ($erow = sqlFetchArray($res)) {
      	$print = '';
      	$csv = '';
        if($erow['encounter'] != $prev_encounter_id) {
            if($prev_encounter_id != -1) {
                $credits = GetAllCredits($prev_encounter_id, $form_pid);
                if(count($credits) > 0) {
                    if(!$hdr_printed) {
                        PrintEncHeader($prev_row{'date'},
                        $prev_row{'reason'}, $prev_row{'provider_id'});
                    }
                    PrintCreditDetail($credits, $form_pid);
                }
                if($hdr_printed) PrintEncFooter();
                $hdr_printed = false;
            }
            $enc_units = $enc_chg = $enc_pmt = $enc_adj = $enc_bal = 0;
        }

        if($erow{'id'}) {
            // Now print an encounter heading line -
            if(!$hdr_printed) {
                PrintEncHeader($erow{'date'},
                    $erow{'reason'}, $erow{'provider_id'});
                $hdr_printed = true;
            }

            $code_desc = $erow['code_text'];
            if(strlen($code_desc) > 50) $code_desc = substr($code_desc,0,50).'...';
            $bgcolor = (($bgcolor == "#FFFFDD") ? "#FFDDDD" : "#FFFFDD");
            $print = "<tr bgcolor='". attr($bgcolor) ."'>";
            $print .= "<td class='detail'>".text($erow['code'])."</td>";
            $print .= "<td class='detail' colspan='2'>".text($code_desc)."</td>";
            $who = ($erow['name'] == '') ? xl('Self') : $erow['name'];
            $bill = substr($erow['bill_date'],0,10);
            if($bill == '') { $bill = 'unbilled'; }
            $print .= "<td class='detail'>".text($bill)."&nbsp;/&nbsp;".text($who)."</td>";
            $print .= "<td class='detail' style='text-align: right;'>". text($erow['units'])."</td>";
            $print .= "<td class='detail' style='text-align: right;'>". text(oeFormatMoney($erow['fee']))."</td>";
            $print .= "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
            $print .= "</tr>\n";

            $total_units  += $erow['units'];
            $total_chg += $erow['fee'];
            $total_bal += $erow['fee'];
            $enc_units  += $erow['units'];
            $enc_chg += $erow['fee'];
            $enc_bal += $erow['fee'];
            $orow++;
			if ($_REQUEST['form_csvexport']) {
                echo $csv;
            } else {
                echo $print;
            }
        }
        $prev_encounter_id = $erow{'encounter'};
        $prev_row = $erow;
    }

    if($prev_encounter_id != -1) {
        $credits = GetAllCredits($prev_encounter_id, $form_pid);
        if(count($credits) > 0) {
            if(!$hdr_printed) {
                PrintEncHeader($prev_row{'date'},
                $prev_row{'reason'}, $prev_row{'provider_id'});
            }
            PrintCreditDetail($credits, $form_pid);
        }
        if($hdr_printed) PrintEncFooter();
    }

    // This is the end of the encounter/charge loop -
    $uac = GetAllUnapplied($form_pid,$from_date,$to_date);
    if(count($uac) > 0) {
        if($orow) {
            $bgcolor = (($bgcolor == "#FFFFDD") ? "#FFDDDD" : "#FFFFDD");
            echo "<tr bgcolor='#FFFFFF'><td colspan='9'>&nbsp;</td></tr>\n";
        }
        PrintCreditDetail($uac, $form_pid, true);
    }

    if (!$_REQUEST['form_csvexport'] && $orow) {
      	echo "<tr bgcolor='#DDFFFF'>\n";
      	echo " <td colspan='2'>&nbsp;</td>";
     	echo " <td class='bold' colspan='2'>" . xlt("Grand Total") ."</td>\n";
      	echo " <td class='bold' style='text-align: right;'>". text($total_units) ."</td>\n";
      	echo " <td class='bold' style='text-align: right;'>". text(oeFormatMoney($total_chg)) ."</td>\n";
      	echo " <td class='bold' style='text-align: right;'>". text(oeFormatMoney($total_pmt)) ."</td>\n";
      	echo " <td class='bold' style='text-align: right;'>". text(oeFormatMoney($total_adj)) ."</td>\n";
      	echo " <td class='bold' style='text-align: right;'>". text(oeFormatMoney($total_bal)) . "</td>\n";
      	echo " </tr>\n";
    	echo '</table>';

  		echo '<tr><td>&nbsp;</td></tr><br><br>';
    	if($GLOBALS['print_next_appointment_on_ledger'] == 1) {
            $next_day = mktime(0,0,0,date('m'),date('d')+1,date('Y'));
            # add one day to date so it will not get todays appointment
            $current_date2 = date('Y-m-d', $next_day);
            $events = fetchNextXAppts($current_date2,$form_pid);
            $next_appoint_date = oeFormatShortDate($events[0]['pc_eventDate']);
            $next_appoint_time = substr($events[0]['pc_startTime'],0,5);
            if(strlen(umname) != 0 ) {
                $next_appoint_provider = $events[0]['ufname'] . ' ' . $events[0]['umname'] . ' ' .  $events[0]['ulname'];
            } else {
                $next_appoint_provider = $events[0]['ufname'] . ' ' .  $events[0]['ulname'];
            }
            if(strlen($next_appoint_time) != 0) {
		  		echo '<tr>';
		    		echo '<td class="title" >'; echo xlt('Next Appointment Date') . ': ' . text($next_appoint_date) . ' ' . xlt('Time') . ' ' . text($next_appoint_time) . ' ' . xlt('Provider') . ' ' . text($next_appoint_provider); echo '</td>';
		  		echo '</tr>';
		    }
        } // end ($GLOBALS['print_next_appointment_on_ledger'] == 1)
    } // end (!$_REQUEST['form_csvexport'] && $orow)
    echo "</div>\n";
}

?>
