<?php
/**
 *
 * Patient Portal Doc_lib.php
 *
 * Copyright (C) 2016-2017 Jerry Padgett <sjpadgett@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Jerry Padgett <sjpadgett@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the authors and to the LibreHealth EHR community.
 *
 */
$ignoreAuth = true;
session_start();
if ( isset($_SESSION['pid']) && isset($_SESSION['patient_portal_onsite']) ) {
    $pid = $_SESSION['pid'];
    $ignoreAuth = true;
    $sanitize_all_escapes = true;
    $fake_register_globals = false;
    require_once ( dirname( __FILE__ ) . "/../../interface/globals.php" );
} else {
    session_destroy();
    $ignoreAuth = false;
    $sanitize_all_escapes = true;
    $fake_register_globals = false;
    require_once ( dirname( __FILE__ ) . "/../../interface/globals.php" );
    if ( ! isset($_SESSION['authUserID']) ){
        $landingpage = "index.php";
        header('Location: '.$landingpage);
        exit;
    }
}

require_once ( "$srcdir/classes/Document.class.php" );
require_once ( "$srcdir/classes/Note.class.php" );
require_once ( "$srcdir/formatting.inc.php" );
require_once ( "$srcdir/htmlspecialchars.inc.php" );

require_once ($GLOBALS['modules_dir'] . "html2pdf/vendor/autoload.php");

require_once (dirname( __FILE__ )."/appsql.class.php" );

$logit = new ApplicationTable();
$htmlin = $_REQUEST['content'];
$dispose = $_POST['handler'];

try{
    $form_filename = $_REQUEST['docid'] . '_' . $GLOBALS['pid'] . '.pdf';
    $templatedir = $GLOBALS['OE_SITE_DIR'] . "/documents/onsite_portal_documents/patient_documents";
    $templatepath = "$templatedir/$form_filename";
    $htmlout = '';
    $pdf = new HTML2PDF(
        $GLOBALS['pdf_layout'],
        $GLOBALS['pdf_size'],
        $GLOBALS['pdf_language'],
        true,
        'UTF-8',
        array ($GLOBALS['pdf_left_margin'],$GLOBALS['pdf_top_margin'],$GLOBALS['pdf_right_margin'],$GLOBALS['pdf_bottom_margin']
        )
    );
    $pdf->writeHtml( $htmlin, false );
    if( $dispose == 'download' ){
        header( 'Content-type: application/pdf' );
        header( 'Content-Disposition: attachment; filename=$form_filename' );
        $pdf->Output( $form_filename, 'D' );
        $logit->portalLog('download document',$_SESSION['pid'],('document:'.$form_filename));
    }
    if( $dispose == 'view' ){
        Header( "Content-type: application/pdf" );
        $pdf->Output( $templatepath, 'I' );
    }
    if( $dispose == 'chart' ){
        $data = $pdf->Output( $form_filename, 'S' );
        ob_start();
        $d = new Document();
        $rc = $d->createDocument( $GLOBALS['pid'], 29, $form_filename, 'application/pdf', $data );
        ob_clean();
        echo $rc;
        $logit->portalLog('chart document',$_SESSION['pid'],('document:'.$form_filename));
    exit(0);
    };
}
catch(Exception $e){
    echo 'Message: ' .$e->getMessage();
    die(xlt("no signature in document"));
}
// not currently used but meant to be.
function doc_toDoc($htmlin)
{
    header( "Content-type: application/vnd.oasis.opendocument.text" );
    header( "Content-Disposition: attachment;Filename=document_name.html" );
    echo "<html>";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
    echo "<body>";
    echo $htmlin;
    echo "</body>";
    echo "</html>";
    ob_clean();
    flush();
    readfile( $fname );
};

?>
