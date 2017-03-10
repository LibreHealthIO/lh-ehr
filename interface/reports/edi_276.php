<?php
/*
 * main file for the 276 batch creation.
 * This report is the batch report required for batch Claim Status.
 *
 * This program creates the batch for the x12 276 Claim Status file
 *
 * @copyright Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
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
 * along with this program. If not, see http://opensource.org/licenses/gpl-license.php.
 *
 * LICENSE: This Source Code is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Terry Hill <teryhill@librehealth.io>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */
    $sanitize_all_escapes=true;
    $fake_register_globals=false;
    require_once("../globals.php");
    require_once("$srcdir/forms.inc");
    require_once("$srcdir/billing.inc");
    require_once("$srcdir/patient.inc");
    require_once("$srcdir/formatting.inc.php");
    require_once "$srcdir/options.inc.php";
    require_once "$srcdir/formdata.inc.php";
    include_once("$srcdir/calendar.inc");
    include_once("$srcdir/edi_276.inc.php");

    $DateFormat = DateFormatRead();
    $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

    // Element data seperator
    $eleDataSep     = "*";

    // Segment Terminator
    $segTer         = "~";

    // Component Element seperator
    $compEleSep     = ":";

    // filter conditions for the report and batch creation

    $from_date      = fixDate($_POST['form_from_date'], date($DateFormat));
    $to_date        = fixDate($_POST['form_to_date'], date($DateFormat));
    $form_facility  = $_POST['form_facility'] ? $_POST['form_facility'] : '';
    $form_provider  = $_POST['form_users'] ? $_POST['form_users'] : '';
    $exclude_policy = $_POST['removedrows'] ? $_POST['removedrows'] : '';
    $X12info        = $_POST['form_x12'] ? explode("|",$_POST['form_x12']) : '';

    //Set up the sql variable binding array (this prevents sql-injection attacks)
    $sqlBindArray = array();

    $where  = "e.pid IS NOT NULL AND e.Date >= ?";
    array_push($sqlBindArray, $from_date);

    if ($to_date) {
        $where .= " AND e.Date <= ?";
        array_push($sqlBindArray, $to_date);
    }

    if($form_facility != "") {
        $where .= " AND f.id = ? ";
        array_push($sqlBindArray, $form_facility);
    }

    if($form_provider != "") {
        $where .= " AND d.id = ? ";
        array_push($sqlBindArray, $form_provider);
    }

    if($exclude_policy != ""){  $arrayExplode   =   explode(",", $exclude_policy);
                                array_walk($arrayExplode, 'arrFormated');
                                $exclude_policy = implode(",",$arrayExplode);
                                $where .= " AND i.policy_number not in (".stripslashes($exclude_policy).")";
                            }

    $where .= " AND (i.policy_number is not null and i.policy_number != '')";

    $query = sprintf("      SELECT DATE_FORMAT(e.Date, '%%Y%%m%%d') as enc_dosDate,
                                   e.billing_facility,
                                   p.lname,
                                   p.fname,
                                   p.mname,
                                   DATE_FORMAT(p.dob, '%%Y%%m%%d') as dob,
                                   p.ss,
                                   p.sex,
                                   p.pid,
                                   p.pubpid,
                                   i.policy_number,
                                   i.provider as payer_id,
                                   i.subscriber_relationship,
                                   i.subscriber_lname,
                                   i.subscriber_fname,
                                   i.subscriber_mname,
                                   DATE_FORMAT(i.subscriber_dob, '%%m/%%d/%%Y') as subscriber_dob,
                                   i.subscriber_ss,
                                   i.subscriber_sex,
                                   DATE_FORMAT(i.date,'%%Y%%m%%d') as date,
                                   d.lname as provider_lname,
                                   d.fname as provider_fname,
                                   d.npi as provider_npi,
                                   d.upin as provider_pin,
                                   f.federal_ein as federal_ein,
                                   f.facility_npi as facility_npi,
                                   f.name as facility_name,
                                   c.name as payer_name
                            FROM form_encounter AS e
                            LEFT JOIN users AS d on (e.provider_id is not null and e.provider_id = d.id)
                            LEFT JOIN facility AS f on (f.id = e.billing_facility)
                            LEFT JOIN patient_data AS p ON p.pid = e.pid
                            LEFT JOIN insurance_data AS i ON (i.id =(
                                                                    SELECT id
                                                                    FROM insurance_data AS i
                                                                    WHERE pid = p.pid AND type = 'primary'
                                                                    ORDER BY date DESC
                                                                    LIMIT 1
                                                                    )
                                                                )
                            LEFT JOIN insurance_companies as c ON (c.id = i.provider)
                            WHERE %s ", $where );

    // Run the query
    $res            = sqlStatement($query, $sqlBindArray);

    // Get the facilities information
    $facilities     = getUserFacilities($_SESSION['authId']);

    // Get the Providers information
    $providers      = getUsernames();

    //Get the x12 partners information
    $clearinghouses = getX12Partner();


    if (isset($_POST['form_savefile']) && !empty($_POST['form_savefile']) && $res) {
        header('Content-Type: text/plain');
        header(sprintf('Content-Disposition: attachment; filename="stat-276..%s.%s.txt"',
            $from_date,
            $to_date
        ));
        print_clstr($res,$X12info,$segTer,$compEleSep);
        exit; 
    }
?>

<html>

    <head>

        <?php html_header_show();?>

        <title><?php echo xlt('276 Claim Status Request Batch'); ?></title>
        
        <link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
        <link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">

        <style type="text/css">

            /* specifically include & exclude from printing */
            @media print {
                #report_parameters {
                    visibility: hidden;
                    display: none;
                }
                #report_parameters_daterange {
                    visibility: visible;
                    display: inline;
                }
                #report_results table {
                   margin-top: 0px;
                }
            }

            /* specifically exclude some from the screen */
            @media screen {
                #report_parameters_daterange {
                    visibility: hidden;
                    display: none;
                }
            }

        </style>

        <script type="text/javascript" src="../../library/textformat.js"></script>
        <script type="text/javascript" src="../../library/dialog.js"></script>
        <script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>

        <script type="text/javascript">

            var stringDelete = "<?php echo xlt('Do you want to remove this record?'); ?>?";
            var stringBatch  = "<?php echo xlt('Please select X12 partner this is required to create the 276 batch'); ?>";

            // for form refresh

            function refreshme() {
                document.forms[0].submit();
            }

            //  To delete the row from the reports section
            function deletetherow(id){
                var suredelete = confirm(stringDelete);
                if(suredelete == true){
                    document.getElementById('PR'+id).style.display="none";
                    if(document.getElementById('removedrows').value == ""){
                        document.getElementById('removedrows').value = "'" + id + "'";
                    }else{
                        document.getElementById('removedrows').value = document.getElementById('removedrows').value + ",'" + id + "'";                  
                    }
                }

            }

            //  To validate the batch file generation - for the required field [clearing house/x12 partner]
            function validate_batch()
            {
                if(document.getElementById('form_x12').value=='')
                {
                    alert(stringBatch);
                    return false;
                }
                else
                {
                    document.getElementById('form_savefile').value = "true";
                    document.theform.submit();

                }


            }

            // To Clear the hidden input field

            function validate_policy()
            {
                document.getElementById('removedrows').value = "";
                document.getElementById('form_savefile').value = "";
                return true;
            }

            // To toggle the clearing house empty validation message
            function toggleMessage(id,x12){

                var spanstyle = new String();

                spanstyle       = document.getElementById(id).style.visibility;
                selectoption    = document.getElementById(x12).value;

                if(selectoption != '')
                {
                    document.getElementById(id).style.visibility = "hidden";
                }
                else
                {
                    document.getElementById(id).style.visibility = "visible";
                    document.getElementById(id).style.display = "inline";
                }
                return true;

            }

        </script>

    </head>
    <body class="body_top">

        <!-- Required for the popup date selectors -->
        <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

        <span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('276 Claim Status Request Batch'); ?></span>

        <div id="report_parameters_daterange">
            <?php echo attr(date("d F Y", strtotime($form_from_date))) .
                " &nbsp; " . xlt('to') .
                "&nbsp; ". attr(date("d F Y", strtotime($form_to_date))); ?>
        </div>

        <form method='post' name='theform' id='theform' action='edi_276.php' onsubmit="return top.restoreSession()">
            <input type="hidden" name="removedrows" id="removedrows" value="">
            <div id="report_parameters">
                <table>
                    <tr>
                        <td width='550px'>
                            <div style='float:left'>
                                <table class='text'>
                                    <tr>
                                        <td class='label'>
                                           <?php echo xlt('From'); ?>:
                                        </td>
                                        <td>
                                        <input type='text' name='form_from_date' id="form_from_date" size='10'
                                           value='<?php echo attr(oeFormatShortDate($from_date)) ?>'>
                                        </td>
                                        <td class='label'>
                                           <?php echo xlt('To'); ?>:
                                        </td>
                                        <td>
                                        <input type='text' name='form_to_date' id="form_to_date" size='10'
                                           value='<?php echo attr(oeFormatShortDate($to_date)) ?>'>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td class='label'>
                                            <?php echo xlt('Facility'); ?>:
                                        </td>
                                        <td>
                                            <?php dropdown_facility($form_facility,'form_facility',false);  ?>
                                        </td>
                                        <td class='label'>
                                           <?php echo xlt('Provider'); ?>:
                                        </td>
                                        <td>
                                            <select name='form_users' onchange='form.submit();'>
                                                <option value=''>-- <?php echo xlt('All'); ?> --</option>
                                                <?php foreach($providers as $user): ?>
                                                    <option value='<?php echo attr($user['id']); ?>'
                                                        <?php echo $form_provider == $user['id'] ? xla(" selected ") : null; ?>
                                                    ><?php echo attr($user['fname']." ".$user['lname']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>&nbsp;
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class='label'>
                                            <?php echo xlt('X12 Partner'); ?>:
                                        </td>
                                        <td colspan='5'>
                                            <select name='form_x12' id='form_x12' onchange='return toggleMessage("emptyVald","form_x12");' >
                                                        <option value=''>--<?php echo xlt('select'); ?>--</option>
                                                        <?php
                                                            if(isset($clearinghouses) && !empty($clearinghouses))
                                                            {
                                                                foreach($clearinghouses as $clearinghouse): ?>
                                                                    <option value='<?php echo attr($clearinghouse['id']."|".$clearinghouse['id_number']."|".$clearinghouse['x12_sender_id']."|".$clearinghouse['x12_receiver_id']."|".$clearinghouse['x12_version']."|".$clearinghouse['processing_format']); ?>'
                                                                        <?php echo $clearinghouse['id'] == $X12info[0] ? xla(" selected ") : null; ?>
                                                                    ><?php echo attr($clearinghouse['name']); ?></option>
                                                        <?php   endforeach;
                                                            }

                                                        ?>
                                                </select>
                                                <span id='emptyVald' style='color:red;font-size:12px;'> * <?php echo xlt('Clearing house info required for 276 Claim Status Request Batch creation.'); ?></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td align='left' valign='middle' height="100%">
                            <table style='border-left:1px solid; width:100%; height:100%' >
                                <tr>
                                    <td>
                                        <div style='margin-left:15px'>
                                            <a href='#' class='css_button' onclick='validate_policy(); $("#theform").submit();'>
                                            <span>
                                                <?php echo xlt('Refresh'); ?>
                                            </span>
                                            </a>
                                            <a href='#' class='css_button' onclick='return validate_batch();'>
                                                <span>
                                                    <?php echo xlt('Create batch'); ?>
                                                    <input type='hidden' name='form_savefile' id='form_savefile' value=''></input>
                                                </span>
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div> 

            <div class='text'>
                <?php echo xlt('Please choose date range criteria above and click Refresh to view results.'); ?>
            </div>

        </form>

        <?php
            if ($res){
                show_clstr($res,$X12info,$segTer,$compEleSep);
            }
        ?>
    </body>

    <script language='JavaScript'>
        <?php if ($alertmsg) { echo " alert('$alertmsg');\n"; } ?>
    </script>
<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
<script>
    $(function() {
        $("#form_from_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $("#form_to_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });
</script>
</html>
