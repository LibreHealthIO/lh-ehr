<?php
//this is the file which will select the portion required to be transferred to the PDF
//and will feed the common part to the PDF

require_once("../../interface/globals.php");
require_once("TCPDF/tcpdf.php");
require_once("$srcdir/report.inc");
require_once("$srcdir/patient.inc");

$titleres = getPatientData($pid, "fname,lname,providerID,DATE_FORMAT(DOB,'%m/%d/%Y') as DOB_TS");   //this section
  if ($_SESSION['pc_facility']) {                                                                   //will fetch 
    $sql = "SELECT * FROM facility WHERE id=" . $_SESSION['pc_facility'];                           //the common
  } else {                                                                                          //data for
    $sql = "SELECT * FROM facility ORDER BY billing_location DESC LIMIT 1";                         //all the
  }                                                                                                 //PDFs like
  /******************************************************************/                              //clinic name,
  $db = $GLOBALS['adodb']['db'];                                                                    //patient name,
  $results = $db->Execute($sql);                                                                    //contact number,
  $facility = array();                                                                              //date of PDF
  if (!$results->EOF) {                                                                             //generation,
    $facility = $results->fields;                                                                   //header text,
}                                                                                                   //footer text
//custom EHRPDF class extended from default TCPDF class
class EHRPDF extends TCPDF {
	//Page header
	public function Header() {
    global $titleres;
    $this->SetY(5);
		// Set font
		$this->SetFont('helvetica', '', 10);
		// Title
    $this->Cell(0, 15, "PATIENT : " . $titleres['lname'] . ", " . 
                $titleres['fname'] . " - " . $titleres['DOB_TS'], 0, false, 'R', 0, '', 0, false, 'M', 'M');
	}

	// Page footer
	public function Footer() {
        global $facility;
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', '', 10);
		
    $this->Cell(0, 10, "Generated on " . oeFormatShortDate() . " - " . 
                        $facility['name'] . ' ' . $facility['phone'], 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

$pdf = new EHRPDF('p', 'mm', array(216, 279), true, 'UTF-8', false);//potrait, pdf unit in mm and height=279mm, width=216mm
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 19);
$pdf->Cell(40, 10, $facility['name']);
$pdf->Ln();
$pdf->SetFont('dejavusans', '', 12);
$pdf->Cell(40, 10, $facility['street'] . ", " .
          $facility['state'] . " " . $facility['postal_code']);
$pdf->Ln(5);
$pdf->Cell(40, 10, $facility['phone']);
$pdf->Ln(5);
$pdf->SetFont('dejavusans', '', 14);
$pdf->Cell(40, 10, $titleres['fname'] . " " . $titleres['lname']);
$pdf->Ln(5);
$pdf->SetFont('dejavusans','',10);
$pdf->Cell(40, 10, "Generated on: " . oeFormatShortDate());
$pdf->Ln(5);

if($_POST['include_demographics'] == 'demographics'){
    $pdf->Ln(5);                                                            //this section
    $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());    //is for inclusion of
    $pdf->Ln(5);                                                            //the patient demographics data
    include('report/demographics.php');                                     //in the PDF
}
if($_POST['include_history'] == 'history'){
    $pdf->Ln(5);                                                            //this section
    $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());    //is for inclusion of 
    $pdf->Ln(5);                                                            //the patient history data
    include('report/history.php');                                          //in the PDF
}
if($_POST['include_insurance'] == 'insurance'){
    $pdf->Ln(5);                                                            //this section
    $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());    //is for inclusion of 
    $pdf->Ln(5);                                                            //the patient insurance data
    include('report/insurance.php');                                        //in the PDF
}
if($_POST['include_billing'] == 'billing'){
  $pdf->Ln(5);                                                              //this section
  $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());      //is for inclusion of 
  $pdf->Ln(5);                                                              //the patient billing data
  include('report/billing.php');                                            //in the PDF
}
if($_POST['include_transactions'] == 'transactions'){
  $pdf->Ln(5);                                                              //this section
  $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());      //is for inclusion of 
  $pdf->Ln(5);                                                              //the patient transactions data
  include('report/transactions.php');                                       //in the PDF
}
if($_POST['include_notes'] == 'notes'){
  $pdf->Ln(5);                                                              //this section
  $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());      //is for inclusion of 
  $pdf->Ln(5);                                                              //the patient notes data
  include('report/patient_notes.php');                                      //in the PDF
}
if($_POST['include_batchcom'] == 'batchcom'){
  $pdf->Ln(5);                                                              //this section
  $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());      //is for inclusion of 
  $pdf->Ln(5);                                                              //the patient communication data
  include('report/communications.php');                                      //in the PDF
}
if($_POST['include_immunizations'] == 'immunizations'){
  $pdf->Ln(5);                                                              //this section
  $pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());      //is for inclusion of 
  $pdf->Ln(5);                                                              //the patient immunization data
  include('report/immunizations.php');                                      //in the PDF
}
//no check is done here because it is done in the procedures.php file
include('report/procedures.php');                                           //this section
$pdf->Ln(5);                                                                //is for inclusion of
$pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth()-10, $pdf->GetY());        //the procedure orders data
$pdf->Ln(5);                                                                //in the PDF


$pdf->Ln(15);
$pdf->Cell(40, 10, "Signature: ________________________________________");
$pdf->Output();

?>
