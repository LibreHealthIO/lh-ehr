<?php

/**
 * /interface/main/messages/save.php
 *
 * Copyright (C) 2017 MedEx <support@MedExBank.com>
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package LibreHealth EHR
 * @author MedEx <support@MedExBank.com>
 * @link http:LibreHealth.io
 */
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/lists.inc");
require_once("$srcdir/api.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/MedEx/API.php");
//require_once(dirname(__FILE__). "/log.inc");

$hb = new MedExApi\MedEx('MedExBank.com');
//you need admin privileges to update this.
if ($_REQUEST['go'] =='Preferences') {
    $query = "SELECT * FROM users where id = ?";
    $user_data = sqlQuery($query,array($_SESSION['authUserID']));

    if (acl_check('admin', 'super')) {
        $sql = "UPDATE `MedEx_prefs` set `ME_facilities`=?,`ME_providers`=?,`ME_hipaa_default_override`=?,
            `PHONE_country_code`=? ,`MSGS_default_yes`=?,
            `POSTCARDS_local`=?,`POSTCARDS_remote`=?,
            `LABELS_local`=?,`LABELS_choice`=?,
            `combine_time`=?";

        $facilities     = implode("|",$_REQUEST['facilities']);
        $providers      = implode("|",$_REQUEST['providers']);
        $HIPAA          = ($_REQUEST['ME_hipaa_default_override'] ? $_REQUEST['ME_hipaa_default_override'] : '');
        $MSGS           = ($_REQUEST['MSGS_default_yes'] ? $_REQUEST['MSGS_default_yes'] : '');
        $country_code   = ($_REQUEST['PHONE_country_code'] ? $_REQUEST['PHONE_country_code'] : '1');

        $myValues = array($facilities,$providers,$HIPAA,$country_code,$MSGS,$_REQUEST['POSTCARDS_local'],$_REQUEST['POSTCARDS_remote'],$_REQUEST['LABELS_local'],$_REQUEST['chart_label_type'],$_REQUEST['combine_time']);
        $_GLOBALS['chart_label_type'] = $_REQUEST['chart_label_type'];
        sqlStatement( 'UPDATE `globals` set gl_value = ? where gl_name like "chart_label_type" ', array( $_REQUEST['chart_label_type'] ) );

        $result = sqlQuery($sql,$myValues);


        echo json_encode($result);
    }
    exit;
}
if ($_REQUEST['MedEx']=="start") {
    if (acl_check('admin', 'super')) {
        $query = "SELECT * FROM users where id = ?";
        $user_data = sqlQuery($query,array($_SESSION['authUserID']));
        $query = "SELECT * from facility where primary_business_entity='1' limit 1";
        $facility = sqlFetchArray(sqlStatement($query));
        $data['firstname']      = $user_data['fname'];
        $data['lastname']       = $user_data['lname'];

        $data['username']       = $_SESSION['authUser'];
        $data['password']       = $_REQUEST['new_password'];
        $data['email']          = $_REQUEST['new_email'];
        $data['telephone']      = $facility['phone'];
        $data['fax']            = $facility['fax'];
        $data['company']        = $facility['name'];
        $data['address_1']      = $facility['street'];
        $data['city']           = $facility['city'];
        $data['state']          = $facility['state'];
        $data['postcode']       = $facility['postal_code'];
        $data['country']        = $facility['country_code'];

        $data['sender_name']    = $user_data['fname']. " " .$user_data['lname'];
        $data['sender_email']   = $facility['email'];
        $data['callerid']       = $facility['phone'];
        $data['MedEx']          = "1";
        $data['ipaddress']      = $_SERVER['REMOTE_ADDR'];

        $prefix ='http://';
        if ($_SERVER["SSL_TLS_SNI"]) $prefix = "https://";
        $data['website_url']    = $prefix.$_SERVER['HTTP_HOST'].$web_root;
        $practice_logo = "$OE_SITE_DIR/images/practice_logo.gif";
        if (!file_exists($practice_logo)) {
            $data['logo_url'] = $prefix.$_SERVER['HTTP_HOST'].$web_root."/sites/".$_SESSION["site_id"]."/images/practice_logo.gif";
        } else {
            $data['logo_url'] = $prefix.$_SERVER['HTTP_HOST']."/openemr/public/images/menu-logo.png";
        }

        $response = $hb->setup->autoReg($data);
        if (($response['API_key']>'')&&($response['customer_id'] > '')) {
            sqlQuery("Delete from MedEx_prefs");
            $sqlUPDATE = "INSERT into `MedEx_prefs` (MedEx_id,ME_api_key,ME_username) VALUES (?,?,?)";
            sqlStatement($sqlUPDATE,array($response['customer_id'],$response['API_key'],$_POST['new_email']));
        }
        $logged_in = $hb->login();


        if ($logged_in) {
            $token      = $logged_in['token'];
            $practice   = $hb->practice->sync($token);
            $token      = $logged_in['token'];

            $response   = $hb->practice->sync($token);
            $campaigns  = $hb->campaign->events($token);
            $response   = $hb->events->generate($token,$campaigns['events']);

            $response['success'] = "OK BABY!";
            $response['show'] =  xlt("Sign-up successful for")." ".$data['company']. ".<br />".xlt("Proceeding to Preferences").".<br />".
                xlt("If this page does not refresh, reload the Messages page manually").".<br />
                ";
                //get js to reroute user to preferences.
            echo json_encode($response);
        }   else {
            $response_prob=array();
            $response_prob['show'] = xlt("We ran into some problems connecting to the MedEx servers from your EHR").".<br>".xlt('Proceed to')." <a href='https://medexbank.com/cart/upload/''>MedEx Bank</a>.
                <br />
                <div class='center left' >
                    <ul><li> ".xlt('Login')." (".xlt('or register if required').") ".xlt('using your email/password')." </li>
                        <li> ".xlt('Confirm your practice information')."</li>
                        <li> ".xlt('Refine the stock message templates as needed')."</li>
                        <li> ".xlt('Activate your subscription plan')."</li>
                        <li> ".xlt('Contact support if your server\'s IP address is dynamic')."</li>
                    </ul>
                </div>
                ";
            echo json_encode($response_prob);
        }   exit;
                    //then redirect user to preferences with a success message!
    } else {
        echo "Sorry you are not privileged enough...";
    }
    exit;
}

if (($_REQUEST['pid'])&&($_REQUEST['action']=="new_recall")) {
    $query = "select * from patient_data where pid=?";
    $result = sqlQuery($query, array($_REQUEST['pid']) );
    $result['age'] = getAge($result['DOB']);
    //if they are not using the eye form then this makes no sense
    //As forms mature, there should be a uniform way to find the PLAN?
    //And when that day comes we'll put it here...
    //For now, we will have to test if the form_eye_mag exists at all, or send NADA.
    //now get the last visit eye_mag PLAN and put it here...
    //use the default form if set in globals in the future...
   # $query = "select PLAN from form_eye_mag where PID=? and date < NOW() ORDER by date desc LIMIT 1";
   # $result2 = sqlQuery($query, array($_REQUEST['pid']) );
   # if ($result2) $result['PLAN'] = $result2['PLAN'];
    $query = "select pc_eventDate from libreehr_postcalendar_events where pc_pid =? order by pc_eventDate DESC LIMIT 1";
    $result2 = sqlQuery($query, array($_REQUEST['pid']) );
    $result['DOLV'] = $result2['pc_eventDate'];
    echo json_encode($result);
    exit;
}

if (($_REQUEST['action']=='addRecall')||($_REQUEST['add_new'])) {
    $saved = $_REQUEST;
    save_recall($saved);
    return "saved";
    exit;
}

if (($_REQUEST['action']=='delete_Recall')&&($_REQUEST['pid'])) {
    delete_recall($_REQUEST);
    return "deleted";
    exit;
}

# Clear the pidList session whenever this page is loaded.
# $_SESSION['pidList'] will hold array of patient ids
# which is then used to print 'postcards' and 'Address Labels'
# Thanks Terry!
unset($_SESSION['pidList']);
$pid_list = array();

if ($_REQUEST['action'] == "process") {
    $new_pid = json_decode($_POST['parameter'],true);
    $new_pc_eid = json_decode($_POST['pc_eid'],true);

    if (($_POST['item']=="phone")||(($_POST['item']=="notes")&&($_POST['msg_notes']>''))) {
        $sql ="INSERT INTO MedEx_outgoing (msg_pc_eid, msg_type, msg_reply, msg_extra_text) VALUES (?,?,?,?)";
        echo $sql.'recall_'.$new_pid[0]." - ".$_POST['item']. " - ". $_SESSION['authUserID'] ." - ".$_POST['msg_notes']. "\n";
        sqlQuery($sql,array('recall_'.$new_pid[0], $_POST['item'], $_SESSION['authUserID'], $_POST['msg_notes']));
        return "done";
    }
    $pc_eidList = json_decode($_POST['pc_eid'],true);
    $_SESSION['pc_eidList'] = $pc_eidList[0];
    $pidList = json_decode($_POST['parameter'],true);
    $_SESSION['pidList'] = $pidList;
    if ($_POST['item']=="SMS") {
        $sql ="update hipaa_reminders set r_phone_done=NOW(),r_phone_bywhom=? where r_pid in (".$_SESSION['pidList'].")";
        sqlQuery($sql,array($_SESSION['authUser']));
    }
    if ($_POST['item']=="AVM") {
        $sql ="update hipaa_reminders set r_vm_sent=NOW(),r_vm_sent_by=? where r_pid in (".$_SESSION['pidList'].")";
        sqlQuery($sql,array($_SESSION['authUser']));
    }
    if ($_POST['item']=="postcards") {
        foreach($pidList as $pid) {
            $sql ="INSERT INTO MedEx_outgoing (msg_pc_eid, msg_type, msg_reply, msg_extra_text) VALUES (?,?,?,?)";
            sqlQuery($sql,array('recall_'.$pid, $_POST['item'], $_SESSION['authUserID'], 'Postcard printed locally'));
        }
    }
    if ($_POST['item']=="labels") {
        foreach($pidList as $pid) {
            $sql ="INSERT INTO MedEx_outgoing (msg_pc_eid, msg_type, msg_reply, msg_extra_text) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE msg_extra_text='Label repeat'";
            sqlQuery($sql,array('recall_'.$pid, $_POST['item'], $_SESSION['authUserID'], 'Label printed locally'));
        }
    }
    echo json_encode($pidList);
    exit;
}
if ($_REQUEST['go'] == "Messages") {
    if ($_REQUEST['msg_id']) {
        $result = updateMessage($_REQUEST['msg_id']);
        echo json_encode($result);
        exit;
    }
}
if ($_REQUEST['add_msg']) {
}
if ($_REQUEST['action'] == "remove_rule") {
    $result['msg'] = "Removing the Rule";
    echo json_encode($result);
    exit;
}
echo "now exiting";
exit;
function save_recall($saved) {
    $mysqldate = date( 'Y-m-d', $_REQUEST['datepicker2'] );
    $mysqldate = DateTime::createFromFormat('Y-m-d', $_REQUEST['datepicker2']);
    $mysqldate = date('Y-m-d', strtotime($_REQUEST['datepicker2']));
    $queryINS = "INSERT into MedEx_recalls (r_pid,r_reason,r_eventDate,r_provider,r_facility)
                    VALUES (?,?,?,?,?)
                    on DUPLICATE KEY
                    UPDATE r_reason=?, r_eventDate=?, r_provider=?,r_facility=?";
    $result = sqlStatement($queryINS,array($_REQUEST['new_pid'],$_REQUEST['new_reason'],$mysqldate,$_REQUEST['new_provider'],$_REQUEST['new_facility'],$_REQUEST['new_reason'],$mysqldate,$_REQUEST['new_provider'],$_REQUEST['new_facility']));
    $query = "UPDATE patient_data
                set phone_home=?,phone_cell=?,email=?,
                    hipaa_allowemail=?,hipaa_voice=?,hipaa_allowsms=?,
                    street=?,postal_code=?,city=?,state=?
                where pid=?";
    $sqlValues = array($_REQUEST['new_phone_home'],$_REQUEST['new_phone_cell'],$_REQUEST['new_email'],
                        $_REQUEST['new_email_allow'],$_REQUEST['new_voice'],$_REQUEST['new_allowsms'],
                        $_REQUEST['new_address'],$_REQUEST['new_postal_code'],$_REQUEST['new_city'],$_REQUEST['new_state'],
                        $_REQUEST['new_pid']);
    $result = sqlStatement($query,$sqlValues);
    return;
}
function delete_Recall($saved) {
    $sqlQuery = "DELETE FROM MedEx_recalls where r_pid=? and r_ID=?";
    $result = sqlStatement($sqlQuery,array($_POST['pid'],$_POST['r_ID']));
    return $result;
}
function getAge($dob, $asof='') {
    if (empty($asof)) $asof = date('Y-m-d');
    $a1 = explode('-', substr($dob , 0, 10));
    $a2 = explode('-', substr($asof, 0, 10));
    $age = $a2[0] - $a1[0];
    if ($a2[1] < $a1[1] || ($a2[1] == $a1[1] && $a2[2] < $a1[2])) --$age;
    return $age;
}

?>
