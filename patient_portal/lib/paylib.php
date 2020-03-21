<?php
/**
 *
 * Patient Portal paylib.php
 *
 * Copyright (C) 2016-2017 Jerry Padgett <sjpadgett@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreEHR
 * @author Jerry Padgett <sjpadgett@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the authors and to the LibreHealth EHR community.
 *
 */
session_start();
if( isset( $_SESSION['pid'] ) && isset( $_SESSION['patient_portal_onsite'] ) ){
    $pid = $_SESSION['pid'];
    $ignoreAuth = true;
    $fake_register_globals=false;
    $sanitize_all_escapes=true;
    require_once ( dirname( __FILE__ ) . "/../../interface/globals.php" );
} else{
    session_destroy();
    $ignoreAuth = false;
    $sanitize_all_escapes = true;
    $fake_register_globals = false;
    require_once ( dirname( __FILE__ ) . "/../../interface/globals.php" );
    if( ! isset( $_SESSION['authUserID'] ) ){
        $landingpage = "index.php";
        header( 'Location: ' . $landingpage );
        exit();
    }
}

require_once("./appsql.class.php");
//$_SESSION['whereto'] = 'paymentpanel';
if ($_SESSION['portal_init'] != 'true') {
    $_SESSION['whereto'] = 'paymentpanel';
}

$_SESSION['portal_init'] = false;

if ($_POST['mode'] == 'portal-save') {
    $form_pid = $_POST['form_pid'];
    $form_method = trim($_POST['form_method']);
    $form_source = trim($_POST['form_source']);
    $upay = isset($_POST['form_upay']) ? $_POST['form_upay'] : '';
    $cc = isset($_POST['extra_values']) ? $_POST['extra_values'] : '';
    $amts = isset($_POST['inv_values']) ? $_POST['inv_values'] : '';
    $s = SaveAudit( $form_pid, $amts, $cc );
    if ($s) {
        echo 'failed';
}

echo true;
} else if ($_POST['mode'] == 'review-save') {
    $form_pid = $_POST['form_pid'];
    $form_method = trim($_POST['form_method']);
    $form_source = trim($_POST['form_source']);
    $upay = isset($_POST['form_upay']) ? $_POST['form_upay'] : '';
    $cc = isset($_POST['extra_values']) ? $_POST['extra_values'] : '';
    $amts = isset($_POST['inv_values']) ? $_POST['inv_values'] : '';
    $s = CloseAudit( $form_pid, $amts, $cc );
    if ($s) {
        echo 'failed';
    }

echo true;
}

function SaveAudit($pid, $amts, $cc)
{
    $appsql = new ApplicationTable();
    try{
        $audit = array ();
        $audit['patient_id'] = $pid;
        $audit['activity'] = "payment";
        $audit['require_audit'] = "1";
        $audit['pending_action'] = "review";
        $audit['action_taken'] = "";
        $audit['status'] = "waiting";
        $audit['narrative'] = "Authorize online payment.";
        $audit['table_action'] = '';
        $audit['table_args'] =  $amts;
        $audit['action_user'] = "0";
        $audit['action_taken_time'] = "";
        $audit['checksum'] = aes256Encrypt($cc);

        $edata = $appsql->getPortalAudit( $pid, 'review', 'payment' );
        $audit['date'] = $edata['date'];
        if ($edata['id'] > 0) {
            $appsql->portalAudit('update', $edata['id'], $audit);
        } else {
            $appsql->portalAudit( 'insert', '', $audit );
        }
    } catch( Exception $ex ){
        return $ex;
    }
    return 0;
}
function CloseAudit($pid, $amts, $cc, $action = 'payment posted', $paction = 'notify patient')
{
    $appsql = new ApplicationTable();
    try{
        $audit = array ();
        $audit['patient_id'] = $pid;
        $audit['activity'] = "payment";
        $audit['require_audit'] = "1";
        $audit['pending_action'] = $paction;//'review';//
        $audit['action_taken'] = $action;
        $audit['status'] = "closed";//'waiting';
        $audit['narrative'] = "Payment authorized.";
        $audit['table_action'] = "update";
        $audit['table_args'] = $amts;
        $audit['action_user'] = isset( $_SESSION['authUserID'] ) ? $_SESSION['authUserID'] : "0";
        $audit['action_taken_time'] = date( "Y-m-d H:i:s" );
        $audit['checksum'] = aes256Encrypt($cc);

        $edata = $appsql->getPortalAudit( $pid, 'review', 'payment' );
        $audit['date'] = $edata['date'];
        if ($edata['id'] > 0) {
            $appsql->portalAudit('update', $edata['id'], $audit);
        }
    } catch( Exception $ex ){
        return $ex;
    }
    return 0;
}
function OnlinePayPost($type, $auditrec)
{
 // start of port for payments
    $extra = json_decode($_POST['extra_values'], true);
    $form_pid = $_POST['form_pid'];
    $form_method = trim($_POST['form_method']);
    $form_source = trim($_POST['form_source']);
    $patdata = getPatientData($form_pid, 'fname,mname,lname,pubpid');
    $NameNew=$patdata['fname'] . " " .$patdata['lname']. " " .$patdata['mname'];
}
?>