<?php
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/pnotes.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/report.inc");
require_once(dirname(__file__) . "/../../../library/classes/Document.class.php");
require_once(dirname(__file__) . "/../../../library/classes/Note.class.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formatting.inc.php");

 $N = 6;
 $first_issue = 1;

 function postToGet($arin) {
  $getstring="";
  foreach ($arin as $key => $val) {
   $getstring.=urlencode($key)."=".urlencode($val)."&";
  }
  return $getstring;
 }
?>
<html>
<head>
<?php html_header_show();?>
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
</head>

<body bgcolor="#ffffff" topmargin=0 rightmargin=0 leftmargin=2 bottommargin=0 marginwidth=2 marginheight=0>
<p>
<?php
 if (sizeof($_GET) > 0) {
  $ar = $_GET;
 } else {
  $ar = $_POST;
 }

 $titleres = getPatientData($pid, "fname,lname,providerID");
 // $sql = "select * from facility where billing_location = 1";
 $sql = "select f.* from facility f ".
  "LEFT JOIN form_encounter fe on fe.facility_id = f.id ".
  "where fe.encounter = " . $encounter;
 $db = $GLOBALS['adodb']['db'];
 $results = $db->Execute($sql);
 $facility = array();
 if (!$results->EOF) {
  $facility = $results->fields;
 }

 $practice_logo = "../../../custom/practice_logo.png";
 if (file_exists($practice_logo)) {
  echo "<img src='$practice_logo' align='left'>\n";
 }
?>
<h2><?php echo text($facility['name']); ?></h2>
<?php echo text($facility['street']); ?><br>
<?php echo text($facility['city']); ?>, <?php echo text($facility['state']); ?> <?php echo text($facility['postal_code']); ?><br clear='all'>
<?php echo text($facility['phone']); ?><br>

</p>

<a href="javascript:window.close();"><font class=title><?php print text($titleres{"fname"}) . " " . text($titleres{"lname"});?></font></a><br><br>

<table>
<tr><td><?php echo xlt('Generated on'); ?>:</td><td> <?php print text(oeFormatShortDate(date("Y-m-d")));?></td></tr>
<?php
if ($date_result = sqlQuery("select date from form_encounter where encounter='" .
$encounter . "' and pid='$pid'"))
{
	$encounter_date = date("D F jS", strtotime($date_result{"date"}));
	$raw_encounter_date = date("Y-m-d", strtotime($date_result{"date"}));

}
?>
<tr><td><?php echo xlt('Date Of Service'); ?>: </td><td> <?php print text(oeFormatShortDate($raw_encounter_date));?></td></tr>
</table>
<br><br>
<?php
 //$provider = getProviderName($titleres['providerID']);

 //print "Provider: " . $provider  . "</br>";

 $inclookupres = sqlStatement("select distinct formdir from forms where pid='$pid'");
 while($result = sqlFetchArray($inclookupres)) {
  include_once("{$GLOBALS['incdir']}/forms/" . $result{"formdir"} . "/report.php");
 }

 $printed = false;

 foreach ($ar as $key => $val) {
  if (!$printed && strpos($key, "patient_encounter_") === 0) {
   $billing = getPatientBillingEncounter($pid, $val);
   foreach ($billing as $b) {
    if(!empty($b['provider_name'])) {
     echo xlt('Provider') . ": " . text($b['provider_name']) . "<br>";
     $printed = true;
     break;
    }
   }
  }
 }

 foreach ($ar as $key => $val) {

  /****
  // WTF??  Redo this.
  if (!empty($ar['patient_encounter'])){
   foreach ($ar['patient_encounter'] as $be) {
    $ta = split(":", $be);
    $billing = getPatientBillingEncounter($pid, $ta[1]);
    if(!$printed) {
     foreach ($billing as $b) {
      if(!empty($b['provider_name'])) {
       echo "Provider: " . $b['provider_name'] . "<br>";
       $printed = true;
       break;
      }
     }
    }
   }
  }
  ****/

  if (stristr($key,"include_")) {
  //print "include: $val<br>\n";

   if ($val == "demographics") {

    print "<br><font class=bold>".xlt('Patient Data').":</font><br>";
    printRecDataOne($patient_data_array, getRecPatientData ($pid), $N);

   } elseif ($val == "history") {

    print "<br><font class=bold>".xlt('History Data').":</font><br>";
    printRecDataOne($history_data_array, getRecHistoryData ($pid), $N);

   } elseif ($val == "employer") {

    print "<br><font class=bold>".xlt('Employer Data').":</font><br>";
    printRecDataOne($employer_data_array, getRecEmployerData ($pid), $N);

   } elseif ($val == "insurance") {

    print "<br><font class=bold>".xlt('Primary Insurance Data').":</font><br>";
    printRecDataOne($insurance_data_array, getRecInsuranceData ($pid,"primary"), $N);		
    print "<font class=bold>".xlt('Secondary Insurance Data').":</font><br>";
    printRecDataOne($insurance_data_array, getRecInsuranceData ($pid,"secondary"), $N);
    print "<font class=bold>".xlt('Tertiary Insurance Data').":</font><br>";
    printRecDataOne($insurance_data_array, getRecInsuranceData ($pid,"tertiary"), $N);

   } elseif ($val == "billing") {

    print "<br><font class=bold>".xlt('Billing Information').":</font><br>";
    if (count($ar['patient_encounter']) > 0) {
     $billings = array();
     echo "<table>";
     echo "<tr><td width=\"400\" class=bold>Code</td><td class=bold>".xlt('Fee')."</td></tr>\n";
     $total = 0.00;
     $copays = 0.00;
     foreach ($ar['patient_encounter'] as $be) {
      $ta = explode(":",$be);
      $billing = getPatientBillingEncounter($pid,$ta[1]);
      $billings[] = $billing;
      foreach ($billing as $b) {
       echo "<tr>\n";
       echo "<td class=text>";
       echo text($b['code_type']) . ":\t" . text($b['code']) . "&nbsp;&nbsp;&nbsp;" . text($b['code_text']) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
       echo "</td>\n";
       echo "<td class=text>";
       echo text(oeFormatMoney($b['fee']));
       echo "</td>\n";
       echo "</tr>\n";
       $total += $b['fee'];
       if ($b['code_type'] == "COPAY") {
        $copays += $b['fee'];
       }
      }
     }
     echo "<tr><td>&nbsp;</td></tr>";
     echo "<tr><td class=bold>".xlt('Sub-Total')."</td><td class=text>" . text(oeFormatMoney($total)) . "</td></tr>";
     echo "<tr><td class=bold>".xlt('Paid')."</td><td class=text>" . text(oeFormatMoney($copays)) . "</td></tr>";
     echo "<tr><td class=bold>".xlt('Total')."</td><td class=text>" . text(oeFormatMoney($total - $copays)) . "</td></tr>";
     echo "</table>";
     echo "<pre>";
     //print_r($billings);
     echo "</pre>";
    }
    else {
     printPatientBilling($pid);
    }


    /****
		} elseif ($val == "allergies") {
			print "<font class=bold>Patient Allergies:</font><br>";
			printListData($pid, "allergy", "1");
		} elseif ($val == "medications") {
			print "<font class=bold>Patient Medications:</font><br>";
			printListData($pid, "medication", "1");
		} elseif ($val == "medical_problems") {
				print "<font class=bold>Patient Medical Problems:</font><br>";
				printListData($pid, "medical_problem", "1");
    ****/

   } elseif ($val == "immunizations") {
    print "<font class=bold>".xlt('Patient Immunization').":</font><br>";
    $sql = "select i1.immunization_id as immunization_id, if(i1.administered_date,concat(i1.administered_date,' - ') ,substring(i1.note,1,20) ) as immunization_data from immunizations i1 where i1.patient_id = $pid order by administered_date desc";
    $result = sqlStatement($sql);
    while ($row=sqlFetchArray($result)) {
     echo "<span class=text> " . text($row{'immunization_data'}) .
	  generate_display_field(array('data_type'=>'1','list_id'=>'immunizations'), $row['immunization_id']) .
	  "</span><br>\n";
    }

   } elseif ($val == "notes") {

    print "<font class=bold>".xlt('Patient Notes').":</font><br>";
    printPatientNotes($pid);

   } elseif ($val == "transactions") {

    print "<font class=bold>".xlt('Patient Transactions').":</font><br>";
    printPatientTransactions($pid);

   }

  } else {

   if ($key == "documents") {
    echo "<br><br>";
    foreach($val as $valkey => $valvalue) {
     $document_id = $valvalue;
     if (!is_numeric($document_id)) continue;
     $d = new Document($document_id);
     $fname = basename($d->get_url());
     $extension = substr($fname, strrpos($fname,"."));
     echo xlt('Document') . " '" . text($fname). "'<br>";
     $n = new Note();
     $notes = $n->notes_factory($d->get_id());
     echo "<table>";
     foreach ($notes as $note) {
      echo '<tr>';
      echo '<td>'.xlt('Note').' #' . text($note->get_id()) . '</td>';
      echo '</tr>';
      echo '<tr>';
      echo '<td>' . xlt('Date') . ': ' . text(oeFormatShortDate($note->get_date())) . '</td>';
      echo '</tr>';
      echo '<tr>';
      echo '<td>'.text($note->get_note()).'<br><br></td>';
      echo '</tr>';
     }
     echo "</table>";
     if ($extension == ".png" || $extension == ".jpg" || $extension == ".jpeg" || $extension == ".gif") {
      echo '<img src="' . $GLOBALS['webroot'] . "/controller.php?document&retrieve&patient_id=&document_id=" . attr($document_id) . '"><br><br>';
     }
     else {
      echo "<b>".xlt('NOTE')."</b>: ".xlt('Document')." '" . text($fname) ."' ".xlt('cannot be displayed inline because its type is not supported by the browser').".<br><br>";
     }
    }
   }

   else if (strpos($key, "issue_") === 0) {

    if ($first_issue) {
     $first_issue = 0;
     echo "<br>\n";
    }
    preg_match('/^(.*)_(\d+)$/', $key, $res);
    $rowid = $res[2];
    $irow = sqlQuery("SELECT type, title, comments, diagnosis " .
     "FROM lists WHERE id = '$rowid'");
    $diagnosis = $irow['diagnosis'];
    echo "<span class='bold'>" . text($irow['title']) . ":</span><span class='text'> " .
     $irow['comments'] . "</span><br>\n";
    // Show issue's chief diagnosis and its description:
    if ($diagnosis) {
     $crow = sqlQuery("SELECT code_text FROM codes WHERE " .
      "code = '$diagnosis' AND " .
      "(code_type = 2 OR code_type = 4 OR code_type = 5)" .
      "LIMIT 1");
     echo "<span class='bold'>&nbsp;Diagnosis: </span><span class='text'>" .
      text($irow['diagnosis']) . " " . text($crow['code_text']) . "</span><br>\n";
    }

   }

   // Otherwise we have an "encounter form" form field whose name is like
   // dirname_formid, with a value which is the encounter ID.
   //
   else {

    $form_encounter = $val;
    preg_match('/^(.*)_(\d+)$/', $key, $res);
    $form_id = $res[2];
    $formres = getFormNameByFormdir($res[1]);
    $dateres = getEncounterDateByEncounter($form_encounter);
    if ($res[1] == 'patient_encounter') print "<br>\n";
    print "<span class='bold'>" . text($formres{"form_name"}) .
     "</span><span class=text>(" . text(oeFormatShortDate(strtotime($dateres{"date"}))) .
     ")" . "</span><br>\n";
    call_user_func($res[1] . "_report", $pid, $form_encounter, $N, $form_id);
    if ($res[1] == 'patient_encounter') {
     $bres = sqlStatement("SELECT date, code, code_text FROM billing WHERE " .
      "encounter = '$form_encounter' AND activity = 1 AND " .
      "(code_type = 'CPT4' OR code_type = 'OPCS') " .
      "ORDER BY date");
     while ($brow=sqlFetchArray($bres)) {
      echo "<span class='bold'>&nbsp;Procedure: </span><span class='text'>" .
        text($brow['code']) . " " . text($brow['code_text']) . "</span><br>\n";
     }
    }

   }
  }
 }

//borrowed from diagnosis.php

?>
<table border="1" cellpadding=5>
<?php
if ($result = getBillingByEncounter($pid,$encounter,"*") ) {
	$billing_html = array();
        $total = 0.0;
	$copay = 0.0;

//test
//	foreach ($result as $key => $val) {
//		print "<h2>$key</h2>";
//		foreach($val as $key2 => $val2) {
//			print "<p> $key2 = $val2 </p>\n";
//		}
//	}
//end test

	foreach ($result as $iter) {
		$html = '';
		if ($iter["code_type"] == "ICD9") {
			$html .= "<tr><td>".text($iter[code_type]).
				"</td><td>".text($iter[code])."</td><td>"
				.text($iter["code_text"])."</td></tr>\n";
			$billing_html[$iter["code_type"]] .= $html;
			$counter++;
		}
		elseif ($iter["code_type"] == "COPAY") { 
			$html .= "<tr><td>".xlt('Payment').":</td><td>".xlt('Thank You')."!</td><td>"
				.text($iter["code_text"])."</td><td>"
				. text(oeFormatMoney($iter["code"])) . "</td></tr>\n";
			if ($iter["code"] > 0.00) {
				$copay += $iter["code"];
				$billing_html[$iter["code_type"]] .= $html;
			}
		}
		else {
			$html .= "<tr><td>".text($iter[code_type]).
				"</td><td>".text($iter[code])."</td><td>"
				.text($iter["code_text"]).' '.text($iter['modifier'])
				."</td><td>" . text(oeFormatMoney($iter['fee'])) . "</td></tr>\n";
			$billing_html[$iter["code_type"]] .= $html;
			$total += $iter['fee'];
			$js = explode(":",$iter['justify']);
			$counter = 0;
			foreach ($js as $j) {
				if(!empty($j)) {
					if ($counter == 0) {
						$billing_html[$iter["code_type"]] .= " (<b>".text($j)."</b>)";
					}
					else {
						$billing_html[$iter["code_type"]] .= " (".text($j).")";
					}
					$counter++;
				}		
			}
			
		        	
			$billing_html[$iter["code_type"]] .= "</span></td></tr>\n";
		}
			
	}
	
$billing_html["CPT4"] .= "<tr><td>".xlt('total')."</td><td></td><td></td><td>" . text(oeFormatMoney($total)) . "</td></tr>\n";
?>
<tr><td><?php echo xlt('code type'); ?></td><td><?php echo xlt('code'); ?></td><td><?php echo xlt('description'); ?></td><td><?php echo xlt('fee'); ?></td></tr>
<?php
	$key = "ICD9"; $val = $billing_html[$key];
		print text($val);
	$key = "CPT4"; $val = $billing_html[$key];
		print text($val);
	$key = "COPAY"; $val = $billing_html[$key];
		print text($val);
$balance = $total-$copay;
if ($balance != 0.00) {
	print "<tr><td>".xlt('balance')."</td><td></td><td>".xlt('Please pay this amount').":</td><td>" . text(oeFormatMoney($balance)) . "</td></tr>\n";
}

}
?>
</tr></table>
<?php
//if ($balance != 0.00) {
//	print "<p>Note: The balance recorded above only reflects the encounter described by this statement.  It does not reflect the balance of the entire account.  A negative number in the balance field indicates a credit due to overpayment</p>";
//}
?>

</body>
</html>
