<?php
/**
 * interface/patient_file/ccr_pending_approval.php Approval screen for uploaded CCR XML.
 *
 * Approval screen for uploaded CCR XML.
 *
 * Copyright (C) 2013 Z&H Consultancy Services Private Limited <sam@zhservices.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package LibreEHR
 * @author  Eldho Chacko <eldho@zhservices.com>
 * @author  Ajil P M <ajilpm@zhservices.com>
 * @link    http://librehealth.io
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once(dirname(__FILE__) . "/../globals.php");
require_once(dirname(__FILE__) . "/../../library/options.inc.php");
require_once(dirname(__FILE__) . "/../../library/patient.inc");
require_once(dirname(__FILE__) . "/../../library/parse_patient_xml.php");
require_once("$srcdir/headers.inc.php");

if($_REQUEST['approve'] == 1){
	insert_patient($_REQUEST['am_id']);
?>
  <html>
		<head>
			<title><?php echo xlt('CCR Approve');?></title>
			<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css" >
		</head>
		<body class="body_top" >
			<center><?php echo xlt('Approved Successfully'); ?></center>
		</body>
	</html>
	<?php
	exit;
}

?>
<html>
	<head>
		<?php call_required_libraries(['bootstrap', 'jquery-min-1-9-1']); ?>
		<span class="title" style="display: none;"><?php echo xlt('Pending Approval');?></span>
		<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
	</head>
	<body class="body_top" >
		<center>
			<h2><?php echo xlt('Pending Approval');?></h2>
		</center>
		<form method="post" name="approve" "onsubmit='return top.restoreSession()'" >
			<center>
				<table class="table table-bordered table-hover" style="width:80%;">
					<tr>
						<th>
							<?php echo xlt('Patient Name'); ?>
						</th>
						<th>
							<?php echo xlt('Match Found'); ?>
						</th>
						<th>
							<?php echo xlt('Action'); ?>
						</th>
					</tr>
					<?php
					$query = sqlStatement("SELECT *,am.id amid,CONCAT(ad.field_value,' ',ad1.field_value) as pat_name FROM audit_master am JOIN audit_details ad ON
						ad.audit_master_id = am.id AND ad.table_name = 'patient_data' AND ad.field_name = 'lname' JOIN audit_details ad1 ON
						ad1.audit_master_id = am.id AND ad1.table_name = 'patient_data' AND ad1.field_name = 'fname' WHERE type='11' AND approval_status='1'");
					if(sqlNumRows($query) > 0){
						while($res = sqlFetchArray($query)){
						$dup_query = sqlStatement("SELECT * FROM audit_master am JOIN audit_details ad ON ad.audit_master_id = am.id AND ad.table_name = 'patient_data'
							AND ad.field_name = 'lname' JOIN audit_details ad1 ON ad1.audit_master_id = am.id AND ad1.table_name = 'patient_data' AND
							ad1.field_name = 'fname' JOIN audit_details ad2 ON ad2.audit_master_id = am.id AND ad2.table_name = 'patient_data' AND ad2.field_name = 'DOB'
							JOIN patient_data pd ON pd.lname = ad.field_value AND pd.fname = ad1.field_value AND pd.DOB = DATE(ad2.field_value) WHERE am.id = ?",
						array($res['amid']));
					?>
					<tr>
						<td class="bold" >
							<?php echo text($res['pat_name']); ?>
						</td>
							<?php
							if(sqlNumRows($dup_query)>0){
								$dup_res = sqlFetchArray($dup_query);
							?>
						<td align="center" class="bold" >
							<?php echo xlt('Yes'); ?>
						</td>
						<td align="center" >
							<a href="ccr_review_approve.php?revandapprove=1&amid=<?php echo attr($res['amid']); ?>&pid=<?php echo attr($dup_res['pid']); ?>" class="button-link" onclick="top.restoreSession()" ><?php echo xlt('Review & Approve'); ?></a>
						</td>
						<?php
							}else{
						?>
						<td align="center" class="bold" >
							<?php echo xlt('No'); ?>
						</td>
						<td align="center" >
							<a href="ccr_pending_approval.php?approve=1&am_id=<?php echo attr($res['amid']); ?>" class="button-link" onclick="top.restoreSession()" ><?php echo xlt('Approve'); ?></a>
						</td>
						<?php
							}
						?>
					</tr>
					<?php
						}
					}else{
					?>
						<tr>
							<td colspan="3" >
								<?php echo xlt('Nothing Pending for Approval')."."; ?>
							</td>
						</tr>
					<?php
					}
				?>
				</table>
			</center>
		</form>
	</body>
</html>
