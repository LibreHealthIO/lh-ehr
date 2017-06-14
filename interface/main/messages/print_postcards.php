<?php
/**
 * /library/MedEx/print_postcards.php
 *
 * This file is executed as a background service
 * either through ajax or cron.
 *
 * Copyright (C) 2017 MedEx <magauran@MedExBank.com>
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Portions of this were developed using Terry Hill's addr_label code.
 *
 * @package LibreHealth EHR
 * @author MedEx <support@MedExBank.com>
 * @link http:Librehealth.io
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../globals.php");
require_once("$srcdir/fpdf/fpdf.php");
require_once("$srcdir/formatting.inc.php");

# This is based on session array.
$pid_list = array();
$pid_list = $_SESSION['pidList'];

$pdf = new FPDF('L', 'mm', array(148,105));
$last = 1;
$pdf->SetFont('Arial','',14);

#Get the data to place on labels
#and output each label
foreach ($pid_list as $pid) {
$pdf->AddPage();
$patdata = sqlQuery("SELECT " .
  "p.fname, p.mname, p.lname, p.pubpid, p.DOB, " .
  "p.street, p.city, p.state, p.postal_code, p.pid " .
  "FROM patient_data AS p " .
  "WHERE p.pid = ? LIMIT 1", array($pid));

# sprintf to print data
$text = sprintf("  %s %s\n  %s\n  %s %s %s\n ", $patdata['fname'], $patdata['lname'], $patdata['street'], $patdata['city'], $patdata['state'], $patdata['postal_code']);

$sql = "SELECT * FROM facility ORDER BY billing_location DESC LIMIT 1";
$facility = sqlQuery($sql);

$postcard_message1 ="It's time to get your EYES checked!";
$postcard_message2 ="Please call our office to schedule";
$postcard_message3 ="your eye exam at ".$facility['phone'];
$postcard_message4 ="Our office is now located at";
$postcard_message5 =$facility['street'];
$postcard_message6 =$facility['city']. ' ' .$facility['state']. ' ' .$facility['postal_code'];
# Add these lines for more information.
#$postcard_message7 ="It's time to get your EYES checked!";
#$postcard_message8 ="Please call our office to schedule";
#$postcard_message9 ="your eye exam at ".$facility['phone'];


$pdf->SetFont('Arial','',9);
$pdf->Cell(74,10,$facility['name'],1,1,'C');
$pdf->MultiCell(74, 55, '', 1 ,'C');// [, boolean fill]]])


$pdf->Text(22,30,$postcard_message1);
$pdf->Text(23,35,$postcard_message2);
$pdf->Text(25,40,$postcard_message3);
$pdf->Text(25,45,$postcard_message4);
$pdf->Text(25,50,$postcard_message5);
$pdf->Text(25,55,$postcard_message6);
# Add these lines for more information.
#$pdf->Text(25,60,$postcard_message7);
#$pdf->Text(25,65,$postcard_message8);
#$pdf->Text(25,70,$postcard_message9);

$pdf->Text(100,40,$patdata['fname']." ".$patdata['lname']);
$pdf->Text(100,50,$patdata['street']);
$pdf->Text(100,60,$patdata['city']." ".$patdata['state']."  ".$patdata['postal_code']);
$pdf->SetFont('Arial','',8);
$pdf->Text(15,80,$facility['street']." is at the bottom of None Street,");

$pdf->Text(18,85,"where it intersects with Main Street.");
$pdf->Text(15,90,"We are across from the Baptist Church");
$pdf->Text(18,95,"and next to the Bob's Magic Touch car wash.");

}
$pdf->Output('postcards.pdf','D');
//D forces the file download instead of showing it in browser
//isn't there an openEMR global for this?

?>
