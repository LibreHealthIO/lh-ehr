<?php            
/**
 * Used for displaying log of dated reminders.
 *
 * Copyright (C) 2012 tajemo.co.za <http://www.tajemo.co.za/>
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
 * @package LibreHealth EHR
 * @author  Craig Bezuidenhout <http://www.tajemo.co.za/>
 * @link    http://librehealth.io
 */

  $fake_register_globals=false;
  $sanitize_all_escapes=true;

  require_once("../../globals.php");
  require_once("$srcdir/htmlspecialchars.inc.php");
  require_once("$srcdir/acl.inc");    
  require_once("$srcdir/dated_reminder_functions.php"); 
  require_once("$srcdir/formatting.inc.php");
  require_once("$srcdir/headers.inc.php");
  $DateFormat = DateFormatRead();
  $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);
  
  
  $isAdmin =acl_check('admin', 'users'); 
?>
<?php
  /*
    -------------------  HANDLE POST ---------------------
  */
  if($_GET){
    if(!$isAdmin){ 
      if(empty($_GET['sentBy']) and empty($_GET['sentTo']))
        $_GET['sentTo'] = array(intval($_SESSION['authId'])); 
    }  
    echo '<table border="1" width="100%" cellpadding="5px" id="logTable">
            <thead>
              <tr>
                <th>'.xlt('ID').'</th>
                <th>'.xlt('Sent Date').'</th>
                <th>'.xlt('From').'</th>
                <th>'.xlt('To').'</th>
                <th>'.xlt('Patient').'</th>
                <th>'.xlt('Message').'</th>
                <th>'.xlt('Due Date').'</th>
                <th>'.xlt('Processed Date').'</th>
                <th>'.xlt('Processed By').'</th>
              </tr>
            </thead>
            <tbody>';
    $remindersArray = array();
    $TempRemindersArray = logRemindersArray(); 
    foreach($TempRemindersArray as $RA){
      $remindersArray[$RA['messageID']]['messageID'] = $RA['messageID']; 
      $remindersArray[$RA['messageID']]['ToName'] = ($remindersArray[$RA['messageID']]['ToName'] ? $remindersArray[$RA['messageID']]['ToName'].', '.$RA['ToName'] : $RA['ToName']);
      $remindersArray[$RA['messageID']]['PatientName'] = $RA['PatientName'];
      $remindersArray[$RA['messageID']]['message'] = $RA['message'];   
      $remindersArray[$RA['messageID']]['dDate'] = $RA['dDate'];       
      $remindersArray[$RA['messageID']]['sDate'] = $RA['sDate'];  
      $remindersArray[$RA['messageID']]['pDate'] = $RA['pDate'];  
      $remindersArray[$RA['messageID']]['processedByName'] = $RA['processedByName'];   
      $remindersArray[$RA['messageID']]['fromName'] = $RA['fromName']; 
    }
    foreach($remindersArray as $RA){ 
      echo '<tr class="heading">
              <td>',text($RA['messageID']),'</td>
              <td>',text($RA['sDate']),'</td>
              <td>',text($RA['fromName']),'</td>
              <td>',text($RA['ToName']),'</td>
              <td>',text($RA['PatientName']),'</td>     
              <td>',text($RA['message']),'</td>    
              <td>',text($RA['dDate']),'</td>    
              <td>',text($RA['pDate']),'</td>      
              <td>',text($RA['processedByName']),'</td>
            </tr>';
    }
    echo '</tbody></table>'; 
    
    die;
  }
?> 
<html>
  <head>
    <?php 
      call_required_libraries(array('bootstrap', 'jquery-min-3-1-1'));
    ?>
    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css"> 
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.7.2.min.js"></script>

    <script language="JavaScript">   
      $(document).ready(function (){  
        $("#submitForm").click(function(){ 
          // top.restoreSession(); --> can't use this as it negates this ajax refresh
          $.get("dated_reminders_log.php?"+$("#logForm").serialize(), 
               function(data) {
                  $("#resultsDiv").html(data);
                  <?php
                    if(!$isAdmin){
                      echo '$("select option").removeAttr("selected");';
                    } 
                  ?>  
                    return false;
               }
             );
          return false;
        })
      }) 
    </script> 
  </head>
  <body class="body_top"> 
<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

             
<?php     
  $allUsers = array(); 
  $uSQL = sqlStatement('SELECT id, fname,   mname, lname  FROM  `users` WHERE  `active` = 1 AND `facility_id` > 0 AND id != ?',array(intval($_SESSION['authId'])));
  for($i=0; $uRow=sqlFetchArray($uSQL); $i++){ $allUsers[] = $uRow; } 
?>     
    <form method="get" id="logForm" onsubmit="return top.restoreSession()">         
    <p class="lead"><?php echo xlt('Filters:')?></p><br/>
    <p class="lead"><?php echo xlt('Date The Message Was Sent') ?></p>
    <?php echo xlt('Start Date') ?> : <input class="form-control" style="display: inline-block; width: auto; margin-left:1px"  id="sd" type="text" name="sd" value=""title='<?php echo xla('yyyy-mm-dd'); ?>' />   &nbsp;&nbsp;&nbsp;
    <?php echo xlt('End Date') ?> : <input class="form-control" style="display: inline-block; width: auto;" id="ed" type="text" name="ed" value="" title='<?php echo xla('yyyy-mm-dd'); ?>' />   <br /><br />
    
    <table style="width:100%">
      <tr>
        <td style="width:50%">
          <p class="lead"><?php echo xlt('Sent By, Leave Blank For All') ?>: </p>
          <select class="form-control" style="width:100%;" id="sentBy" name="sentBy[]" multiple="multiple">
            <option value="<?php echo attr(intval($_SESSION['authId'])); ?>"><?php echo xlt('Myself') ?></option>
            <?php  
              if($isAdmin)    
                foreach($allUsers as $user)
                  echo '<option value="',attr($user['id']),'">',text($user['fname'].' '.$user['mname'].' '.$user['lname']),'</option>'; 
            ?>
          </select>   
        </td>
        <td style="width:50%">
          <p class="lead"><?php echo xlt('Sent To, Leave Blank For All') ?>: </p>
          <select class="form-control" style="width:100%" id="sentTo" name="sentTo[]" multiple="multiple">    
            <option value="<?php echo attr(intval($_SESSION['authId'])); ?>"><?php echo xlt('Myself') ?></option>
            <?php                    
              if($isAdmin)
                foreach($allUsers as $user) 
                  echo '<option value="',attr($user['id']),'">',text($user['fname'].' '.$user['mname'].' '.$user['lname']),'</option>';  
            ?>
          </select>  
        </td>
      </tr>
    </table>
    <input type="checkbox" name="processed" id="processed"><label for="processed"><?php echo xlt('Processed') ?></label>      
    <input type="checkbox" name="pending" id="pending"><label for="pending"><?php echo xlt('Pending') ?></label>          
    <br /><br />  
    <button style="margin-bottom:16px" value="Refresh" class="cp-submit" id="submitForm"><?php echo xlt('Refresh') ?></button>
  </form>
  
  <div id="resultsDiv"></div>
 
  </body> 
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/css/jquery.datetimepicker.css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.datetimepicker.full.min.js"></script>
<script>
  $(function() {
      $("#sd, #ed").datetimepicker({
        timepicker: false,
        format: "<?= $DateFormat; ?>"
      });
      $.datetimepicker.setLocale('<?= $DateLocale;?>');
  });
</script>
</html> 
