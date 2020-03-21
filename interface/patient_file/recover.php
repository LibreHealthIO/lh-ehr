<?php 
/**
 * Recover tool, used for recovering patient data after deleting.
 * It Access data with tables prefixed with recycler_
 *
 * Copyright (C) 2018 Naveen Muthusamy <kmnaveen101@gmail.com>
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Naveen Muthusamy <kmnaveen101@gmail.com>
 * @link    http://librehealth.io
 */

require_once('../globals.php');
require_once('../../library/headers.inc.php');
require_once("../../library/sql.inc");
$library_array = array("bootstrap", "font-awesome", "jquery-ui", "jquery-min-3-1-1");

call_required_libraries($library_array);

//missed tables but recover created
//drug sales
//
//show a empty dialog.
function show_empty_recycle_bin() {
echo "<div class='col-xs-12 text-center'>
	  <h1><i class='fa fa-trash'></i></h1>
	  <h2>Recycle Bin is Empty</h2>
	  </div>
";
}

/* show the list of patients present in recycle bin in the table*/
function show_recycle_bin() {
$sql = "SELECT * FROM recycle_patient_data";
$query = sqlQ($sql);
$row_count = sqlNumRows($query);
	if ($row_count > 0) {
	echo "<table class='table table-striped text-left'>";
	echo "<th>Fname</th>";
	echo "<th>Lname</th>";
	echo "<th>DOB</th>";
	echo "<th>Sex</th>";
	echo "<th>Restore</th>";	
		while ($patient_row = sqlFetchArray($query)) {
			echo "<tr>";
			echo "<td><b 'style='color: blue; cursor:pointer;'>".$patient_row['fname']."</b></td>";
			echo "<td><b>".$patient_row['lname']."</b></td>";
			echo "<td><b>".$patient_row['DOB']."</b></td>";
			echo "<td><b>".$patient_row['sex']."</b></td>";
			echo "<td><a href='recover.php?pid=".$patient_row['pid']."'><i class='fa fa-recycle' style='cursor:pointer;'></i></a></td>";
			echo "</tr>";
		}
	echo "</table>";
			
	}
	else {
		show_empty_recycle_bin();
	}
}

//used to reactivate the deactivated fields in deleter.php
 function row_modify($table, $set, $where) {
  if (sqlQuery("SELECT * FROM $table WHERE $where")) {
   newEvent("activate", $_SESSION['authUser'], $_SESSION['authProvider'], 1, "$table: $where");
   $query = "UPDATE $table SET $set WHERE $where";
   echo $query . "<br>\n";
   sqlStatement($query);
  }
 }

 function row_recover($table, $where) {
  $tres = sqlStatement("SELECT * FROM $table WHERE $where");
  $original_table = explode("recycle_", $table)[1];
  if (sync_tables ($original_table, $table)) {
	  sqlStatement("INSERT INTO $original_table SELECT * FROM $table WHERE $where;");
	   $query = "DELETE FROM $table WHERE $where";
	   sqlStatement($query);
	}
 }

// Restore all the forms of the patient and its data to the original table
//
function form_recover($formdir, $formid) {
  $formdir = ($formdir == 'patient_encounter') ? 'encounter' : $formdir;
  if (substr($formdir,0,3) == 'LBF') {
    row_recover("recycle_lbf_data", "form_id = '$formid'");
  }
  else if ($formdir == 'procedure_order') {
    $tres = sqlStatement("SELECT procedure_report_id FROM procedure_report " .
      "WHERE procedure_order_id = ?", array($formid));
    while ($trow = sqlFetchArray($tres)) {
      $reportid = 0 + $trow['procedure_report_id'];
      row_recover("recycle_procedure_result", "procedure_report_id = '$reportid'");
    }
    row_recover("recycle_procedure_report", "procedure_order_id = '$formid'");
    row_recover("recycle_procedure_order_code", "procedure_order_id = '$formid'");
    row_recover("recycle_procedure_order", "procedure_order_id = '$formid'");
  }
  else if ($formdir == 'physical_exam') {
    row_recover("recycle_form_$formdir", "forms_id = '$formid'");
  }
  else {
    row_recover("recycle_form_$formdir", "id = '$formid'");
  }
}

// recover a specified document including its associated relations and file.
//
function recover_document($document) {
  $trow = sqlQuery("SELECT url FROM recycle_documents WHERE id = ?", array($document));
  $url = $trow['url'];
  row_recover("recycle_categories_to_documents", "document_id = '" . add_escape_custom($document) . "'");
  row_recover("recycle_documents", "id = '" . add_escape_custom($document) . "'");
  row_recover("recycle_gprelations", "type1 = 1 AND id1 = '" . add_escape_custom($document) . "'");
  if (substr($url, 0, 7) == 'file://') {
    //@unlink(substr($url, 7));
    //dont delete the file. only the records can be deleted.
  }
}


$sql = "SHOW TABLES LIKE 'recycle_patient_data'";
$query = sqlQ($sql);
$row_count = sqlNumRows($query);
?>

<!--HTML LAYOUT-->
<div class="col-xs-12 text-left body_top">
<title>Recycle Bin</title>
<h4><b><i class='fa fa-recycle'></i> Recycle Bin</b></h4>
</div>
<!--ENDS HERE-->

<?php 
if ($row_count > 0) {
	//it means first delete of patient has been done. And now we are displaying the table contain all the deleted details.
	show_recycle_bin();
}
else {
	//it means no recovery tables are present.so lets show empty recycle bin
	show_empty_recycle_bin();
}
?>
<style type="text/css">
	.fa-trash {
		font-size: 200px;
	}
</style>
<?php 
//This part of the code will get the rows from table with prefix recycle_ and replace them to original tables, it is referred by pid
if (isset($_GET['pid'])) {

	if (!empty($_GET['pid'])) {

		//use pid to put all data to original tables from recycle tables

		$pid = $_GET['pid'];

		$patient = $pid;

		   row_modify("billing"       , "activity = 1", "pid = '$patient'");
   		   row_modify("pnotes"        , "deleted = 0" , "pid = '$patient'");
		   row_recover("recycle_prescriptions"  , "patient_id = '$patient'");
   		   row_recover("recycle_claims"         , "patient_id = '$patient'");
   		   row_recover("recycle_libreehr_postcalendar_events", "pc_pid = '$patient'");
		   row_recover("recycle_payments"       , "pid = '$patient'");
		   row_recover("recycle_ar_activity"    , "pid = '$patient'");
		   row_recover("recycle_issue_encounter", "pid = '$patient'");
		   row_recover("recycle_lists"          , "pid = '$patient'");
		   row_recover("recycle_transactions"   , "pid = '$patient'");
		   row_recover("recycle_employer_data"  , "pid = '$patient'");
		   row_recover("recycle_history_data"   , "pid = '$patient'");
		   row_recover("recycle_insurance_data" , "pid = '$patient'");
		   row_recover("recycle_immunizations"  , "patient_id = '$patient'");
		   row_recover("recycle_patient_data", "pid = '$patient'");
		   row_recover("recycle_drug_sales", "pid = '$patient_id'");
		   $res = sqlStatement("SELECT * FROM recycle_forms WHERE pid = ?", array($patient));
		   while ($row = sqlFetchArray($res)) {
		    form_recover($row['formdir'], $row['form_id']);
		   }
		   // restore all the deleted documents using pid
		   $sql = "SHOW TABLES LIKE 'recycle_documents'";
		   $query = sqlQ($sql);
		   $row_count = sqlNumRows($query);
		   if ($row_count > 0) {
			   $res = sqlStatement("SELECT id FROM recycle_documents WHERE foreign_id = ?", array($patient));
			   while ($row = sqlFetchArray($res)) {
			    recover_document($row['id']);
			   }
		   }
		   row_recover("recycle_forms", "pid = '$patient'");
		   
		   echo '<script>var bool = confirm("Successfully restored.");
			    if (bool == true){
			    	//we are refreshing in order to prevent pid being circulated again.
			      window.location = "recover.php";
			    }
			    else {
			    	window.location = "recover.php";
			    }

			    </script>';
	}

}
?>