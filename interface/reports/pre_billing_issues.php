<?php
/*
* Copyright (C) 2015-2017 Tony McCormick <tony@mi-squared.com> 
*
* LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
* See the Mozilla Public License for more details. 
* If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
*
* @package LibreEHR
* @author  Tony McCormick <tony@mi-squared.com>
* @author  Terry Hill <teryhill@librehealth.io>
* @link    http://www.libreehr.org
*
* Added date parameters 2016 Terry Hill <teryhill@librehealth.io>
*/

  $fake_register_globals=false;
  $sanitize_all_escapes=true;

  require_once(dirname(__FILE__) . "/../globals.php");
  require_once("$srcdir/formatting.inc.php");
  require_once("$srcdir/formatting.inc.php");
  require_once "$srcdir/formdata.inc.php";
  require_once("$srcdir/patient.inc");

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// render page - main
//////////////////////////////////////////////////////////////////////////////////////////////////////////

  $form_from_date  = fixDate($_POST['form_from_date'], date('Y-m-01'));
  $form_to_date    = fixDate($_POST['form_to_date'], date('Y-m-d'));
 

?>
<html>
    <head>
        <?php if (function_exists('html_header_show')) html_header_show(); ?>
        <link rel=stylesheet href="<?php echo $css_header; ?>" type="text/css">
        <title><?php echo xlt('Pre-billing Issues Report') ?></title>
        <style type="text/css">
            .highlight {
                color: white;
                text-decoration: underline;
                background-color: black;
            }
        </style>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>
        <script language="javascript">
            var concurrentLayout = <?php echo $GLOBALS['concurrent_layout'] ? true : false ?>;
            
            function toencounter(pid, pubpid, pname, enc, datestr, dobstr) {
                dobstr = dobstr ? dobstr : '';
                top.restoreSession();
                if ( concurrentLayout ) {
                    var othername = (window.name == 'RTop') ? 'RBot' : 'RTop';
                    parent.left_nav.setPatient(pname,pid,pubpid,'',dobstr);
                    parent.left_nav.setEncounter(datestr, enc, othername);
                    parent.left_nav.setRadio(othername, 'enc');
                    parent.frames[othername].location.href =
                    '../patient_file/encounter/encounter_top.php?set_encounter='
                        + enc + '&pid=' + pid;
                } else {
                     location.href = '../patient_file/encounter/patient_encounter.php?set_encounter='
                        + enc + '&pid=' + pid;
                }
            }
            
            function topatient(pid, pubpid, pname, enc, datestr, dobstr) {
                dobstr = dobstr ? dobstr : '';
                top.restoreSession();
                if ( concurrentLayout ) {
                    var othername = (window.name == 'RTop') ? 'RBot' : 'RTop';
                    parent.left_nav.setPatient(pname,pid,pubpid,'',dobstr);
                    parent.frames[othername].location.href =
                     '../patient_file/summary/demographics_full.php?pid=' + pid;
                } else {
                      location.href = '../patient_file/summary/demographics_full.php?pid=' + pid;
                }
            }
            
            $(document).ready(function () {
                $(".reportrow").mouseover(function () {
                    $(this).addClass("highlight");
                });
                $(".reportrow").mouseout(function () {
                    $(this).removeClass("highlight");
                });
                $(".encrow").click(function () {
                    toencounter(
                        $(this).attr('pid'),
                        $(this).attr('pubpid'),
                        $(this).attr('pname'),
                        $(this).attr('encid'),
                        $(this).attr('encdate'),
                        $(this).attr('pdob')
                    );
                });
                $(".ptrow").click(function () {
                    topatient(
                        $(this).attr('pid'),
                        $(this).attr('pubpid'),
                        $(this).attr('pname'),
                        $(this).attr('encid'),
                        $(this).attr('encdate'),
                        $(this).attr('pdob')
                    );
                });
                
                $(".reportrow").attr('title', '<?php echo xla('Click through to correct this record') ?>');
            });
        </script>
    </script>

</head>

<body class="body_top">
    <span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Pre-billing Issues'); ?></span>

 <form method='post' action='pre_billing_issues.php' id='theform'>   
 <div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<table>
 <tr>
  <td width='660px'>
    <div style='float:left'>

    <table class='text'>
        
        <tr>
            <td class='label'>
               <?php xl('Check encounters From','e'); ?>:
            </td>
            <td>
               <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php  echo $form_from_date; ?>'
                title='Date of appointments mm/dd/yyyy' >
               <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
                id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
                title='<?php xl('Click here to choose a date','e'); ?>'>
            </td>
            <td class='label'>
               <?php xl('To','e'); ?>:
            </td>
            <td>
               <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php  echo $form_to_date; ?>'
                title='Optional end date mm/dd/yyyy' >
               <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
                id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
                title='<?php xl('Click here to choose a date','e'); ?>'>
            </td>
            <td>&nbsp;</td>
        </tr>
        
    </table>

    </div>

  </td>
  <td align='left' valign='middle' height="100%">
    <table style='border-left:1px solid; width:100%; height:100%' >
        <tr>
            <td>
                <div style='margin-left:15px'>
                    <a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#theform").submit();'>
                    <span>
                        <?php xl('Submit','e'); ?>
                    </span>
                    </a>

                    <?php if ($_POST['form_refresh']) { ?>
                    <a href='#' class='css_button' onclick='window.print()'>
                        <span>
                            <?php xl('Print','e'); ?>
                        </span>
                    </a>
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>
  </td>
 </tr>
</table>
</div>    
    
    
    
    
        
    <p>
        <?php echo xlt('Use this report to discover billing errors. You may click through each row to drill into the record that requires an update.') ?>
    </p>

 <?php
 if ($_POST['form_refresh']) {
   require("api/PreBillingIssuesAPI.php");  
     //////////////////////////////////////////////////////////////////////////////////////////////////////////
// main
//////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// private
//////////////////////////////////////////////////////////////////////////////////////////////////////////
function computeReport() {
    $preBillingIssuesAPI = new PreBillingIssuesAPI();
    $reportData = array();
    $reportData['encountersMissingProvider'] = $preBillingIssuesAPI->findEncountersMissingProvider();
    $reportData['patientInsuranceMissingSubscriberFields'] = $preBillingIssuesAPI->findPatientInsuranceMissingSubscriberFields();
    $reportData['patientInsuranceMissingSubscriberRelationship'] = $preBillingIssuesAPI->findPatientInsuranceMissingSubscriberRelationship();
    $reportData['patientInsuranceMissingInsuranceFields'] = $preBillingIssuesAPI->findPatientInsuranceMissingInsuranceFields();
    return $reportData;
    
}
$reportData = computeReport();
?>   
    
    
    
    <h5><?php echo xlt('Encounters without rendering provider (The Date Range controles what is Displayed here)') ?></h5>
    <div id="report_results">
        <table>
            <thead>
               <th>&nbsp;<?php echo xlt('Patient Name')?></th>
               <th>&nbsp;<?php echo xlt('Encounter Date')?></th>
            </thead>

            <?php foreach ($reportData['encountersMissingProvider'] as $index => $row) { 
             if (substr($row['Encounter Date'],0,10) >= $form_from_date && substr($row['Encounter Date'],0,10) <= $form_to_date ) {
?>
                <tr class='encrow reportrow'
                    bgcolor='<?php echo $index % 2 == 0 ? "#ffdddd" : "#ddddff" ?>'
                    pid='<?php echo attr($row['Pt ID']) ?>' 
                    pubpid='<?php echo attr($row['Pub Pt ID']) ?>' 
                    pname='<?php echo attr($row['LName'] . ', ' . $row['FName']) ?>' 
                    encid='<?php echo attr(oeFormatShortDate($row['Enc ID'])) ?>'
                    encdate='<?php echo attr(oeFormatShortDate(date("Y-m-d", strtotime($row['Encounter Date'])))) ?>'
                    pdob='<?php echo attr($row['Pt DOB']) ?>' 
                >
                    <td class='detail'><?php echo text($row['LName'] . ', ' . $row['FName']) ?></td>
                    <td class='detail'><?php echo htmlspecialchars(oeFormatShortDate(date("Y-m-d", strtotime($row['Encounter Date']))), ENT_QUOTES) ?></td>
                </tr>
             <?php } else{
                     continue;
             }
             } ?>
        </table>

        <h5><?php echo xlt('Incomplete patient insurance subscriber fields') ?></h5>
        <table>
            <thead>
               <th>&nbsp;<?php echo xlt('Patient Name')?></th>
               <th>&nbsp;<?php echo xlt('Insurance Type')?></th>
               <th>&nbsp;<?php echo xlt('Subscriber Relationship')?></th>
               <th>&nbsp;<?php echo xlt('Errors')?></th>            
            </thead>
            
            <?php foreach ($reportData['patientInsuranceMissingSubscriberFields'] as $index => $row) { 
                  if (substr($row['End Date'],0,10) == '0000-00-00'){
            ?>
                <tr class='ptrow reportrow' 
                    bgcolor='<?php echo $index % 2 == 0 ? "#ffdddd" : "#ddddff" ?>'
                    pid='<?php echo attr($row['Pt ID']) ?>' 
                    pubpid='<?php echo attr($row['Pub Pt ID']) ?>' 
                    pname='<?php echo attr($row['LName'] . ', ' . $row['FName']) ?>' 
                    encid='<?php echo attr($row['Enc ID']) ?>'
                    encdate='<?php echo attr(oeFormatShortDate(date("Y-m-d", strtotime($row['Encounter Date'])))) ?>'
                    pdob='<?php echo attr(oeFormatShortDate($row['Pt DOB'])) ?>' 
                >
                    <td class='detail'><?php echo text($row['LName'] . ', ' . $row['FName']) ?></td>
                    <td class='detail'><?php echo text($row['Insurance Type']) ?></td>
                    <td class='detail'><?php echo text($row['Subscriber Relationship']) ?></td>
                    <td class='detail'>
                        <?php foreach ($row['decodedErrors'] as $error) { ?>
                            <?php echo text($error) ?> <br>
                        <?php } ?>
                    </td>                    
                </tr>
            <?php } } ?>
        </table>

        <h5><?php echo xlt('Incomplete patient insurance missing subscriber relationship') ?></h5>
        <table>
            <thead>
               <th>&nbsp;<?php echo xlt('Patient Name')?></th>
               <th>&nbsp;<?php echo xlt('Insurance Type')?></th>
            </thead>
            <?php foreach ($reportData['patientInsuranceMissingSubscriberRelationship'] as $index => $row) { 
                  if (substr($row['End Date'],0,10) == '0000-00-00'){
            ?>
                <tr class='ptrow reportrow' 
                    bgcolor='<?php echo $index % 2 == 0 ? "#ffdddd" : "#ddddff" ?>'
                    pid='<?php echo attr($row['Pt ID']) ?>' 
                    pubpid='<?php echo attr($row['Pub Pt ID']) ?>' 
                    pname='<?php echo attr($row['LName'] . ', ' . $row['FName']) ?>' 
                    encid='<?php echo attr($row['Enc ID']) ?>'
                    encdate='<?php echo attr(oeFormatShortDate(date("Y-m-d", strtotime($row['Encounter Date'])))) ?>'
                    pdob='<?php echo attr(oeFormatShortDate($row['Pt DOB'])) ?>' 
                >
                    <td class='detail'><?php echo text($row['LName'] . ', ' . $row['FName']) ?></td>
                    <td class='detail'><?php echo text($row['Insurance Type']) ?></td>
                </tr>
            <?php } } ?>
        </table>

        <h5><?php echo xlt('Incomplete patient insurance') ?></h5>
        <table>
            <thead>
               <th>&nbsp;<?php echo xlt('Patient Name')?></th>
               <th>&nbsp;<?php echo xlt('Insurance Type')?></th>
               <th>&nbsp;<?php echo xlt('Errors')?></th>
            </thead>            
            <?php foreach ($reportData['patientInsuranceMissingInsuranceFields'] as $index => $row) { 
                  if (substr($row['End Date'],0,10) == '0000-00-00'){
            ?>
                <tr class='ptrow reportrow' 
                    bgcolor='<?php echo $index % 2 == 0 ? "#ffdddd" : "#ddddff" ?>'
                    pid='<?php echo attr($row['Pt ID']) ?>' 
                    pubpid='<?php echo attr($row['Pub Pt ID']) ?>' 
                    pname='<?php echo attr($row['LName'] . ', ' . $row['FName']) ?>' 
                    encid='<?php echo attr($row['Enc ID']) ?>'
                    encdate='<?php echo attr(oeFormatShortDate(date("Y-m-d", strtotime($row['Encounter Date'])))) ?>'
                    pdob='<?php echo attr(oeFormatShortDate($row['Pt DOB'])) ?>' 
                >
                    <td class='detail'><?php echo text($row['LName'] . ', ' . $row['FName']) ?></td>
                    <td class='detail'><?php echo text($row['Insurance Type']) ?></td>
                    <td class='detail'>
                        <?php foreach ($row['decodedErrors'] as $error) { ?>
                            <?php echo text($error) ?> <br>
                        <?php } ?>
                    </td>
                </tr>
            <?php } } ?>
        </table>
    </div>
  <?php } else { ?>
<div class='text'>
     <?php echo xl('Please input search criteria above, and click Submit to view results.', 'e' ); ?>
</div>
<?php } ?>  

</form>
</body>

<!-- stuff for the popup calendar -->
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>

<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d", button:"img_from_date"});
 Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d", button:"img_to_date"});
</script>

</html>
