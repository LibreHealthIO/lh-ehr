<?php
   /**
    * Used for displaying dated reminders. 
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
    
   // removed as jquery is already called in messages page (if you need to use jQuery, uncomment it futher down)
   // not neeeded as messages page handles this
   //       $fake_register_globals=false;
   //       $sanitize_all_escapes=true;
   require_once('../../globals.php');
   require_once("$srcdir/htmlspecialchars.inc.php");
   require_once("$srcdir/dated_reminder_functions.php");
   
           $days_to_show = 5;
           $alerts_to_show = 15;
           $updateDelay = 60; // time is seconds 
           
           
   // ----- get time stamp for start of today, this is used to check for due and overdue reminders
           $today = strtotime(date('Y/m/d'));
           
    // ----- set $hasAlerts to false, this is used for auto-hiding reminders if there are no due or overdue reminders        
           $hasAlerts = false;
   
   // mulitply $updateDelay by 1000 to get miliseconds             
           $updateDelay = $updateDelay * 1000;  
           
   //-----------------------------------------------------------------------------
   // HANDEL AJAX TO MARK REMINDERS AS READ
   // Javascript will send a post
   // ----------------------------------------------------------------------------         
       if(isset($_POST['drR'])){ 
           // set as processed
             setReminderAsProcessed($_POST['drR']); 
           // ----- get updated data
             $reminders = RemindersArray($days_to_show,$today,$alerts_to_show); 
           // ----- echo for ajax to use        
             echo getRemindersHTML($reminders,$today); 
           // stop any other output  
             exit;
       }
   //-----------------------------------------------------------------------------
   // END HANDEL AJAX TO MARK REMINDERS AS READ 
   // ----------------------------------------------------------------------------       
     
         $reminders = RemindersArray($days_to_show,$today,$alerts_to_show);
         
         ?> 
<style type="text/css"> 
   div.dr{     
   margin:0;
   font-size:0.6em;
   }  
   .dr_container a{
   font-size:0.6em;
   }    
   .dr_container{
   padding:5px 5px 8px 5px;
   }  
   .dr_container p{
   margin:6px 0 0 0;
   }      
   .patLink{ 
   font-weight: bolder;
   cursor:pointer; 
   text-decoration: none;  
   }       
   .patLink:hover{ 
   font-weight: bolder;
   cursor:pointer; 
   text-decoration: underline;
   }
</style>
<script type="text/javascript">
   $(document).ready(function (){ 
         
      $(".dr_container").accordion({
          collapsible: true,
          active: false
      });
        var link = '';
       $(".sendReminder").click(function () {
         var val = $(this).attr("data-text");
         if(val = 0){
             link = "<?php echo $GLOBALS['webroot']; ?>/interface/main/dated_reminders/dated_reminders_add.php";
             initIziLink(link);
         }
         else {
             link = "<?php echo $GLOBALS['webroot']; ?>/interface/main/dated_reminders/dated_reminders_add.php?mID='"+val+"";
             initIziLink(link);
         }
       });

       $("#viewLog-iframe").iziModal({
           title: 'Dated Message Log',
           subtitle: 'Filter Message Log at Any Point in time',
           headerColor: '#88A0B9',
           closeOnEscape: true,
           fullscreen:true,
           overlayClose: false,
           closeButton: true,
           theme: 'light',  // light
           iframe: true,
           width:900,
           focusInput: true,
           padding:5,
           iframeHeight: 400,
           iframeURL: "<?php echo $GLOBALS['webroot']; ?>/interface/main/dated_reminders/dated_reminders_log.php"
       });
       
       function initIziLink(link) {
           $("#sendReminder-iframe").iziModal({
               title: 'Send A Reminder',
               subtitle: 'Get to send a reminder to Patient/Clinician',
               headerColor: '#88A0B9',
               closeOnEscape: true,
               fullscreen:true,
               overlayClose: false,
               closeButton: true,
               theme: 'light',  // light
               iframe: true,
               width:700,
               focusInput: true,
               padding:5,
               iframeHeight: 400,
               iframeURL: link
           });
           
           setTimeout(function () {
               call_izi();
           },500);
       }

      

       function call_izi() {
           $("#sendReminder-iframe").iziModal('open');
       }



       // run updater after 30 seconds
     var updater = setTimeout("updateme(0)", 1);
   });
     
     function openAddScreen(id){
       if(id == 0){
         top.restoreSession();
         dlgopen('<?php echo $GLOBALS['webroot']; ?>/interface/main/dated_reminders/dated_reminders_add.php', '_drAdd', 700, 500);
       }else{
         top.restoreSession();
         dlgopen('<?php echo $GLOBALS['webroot']; ?>/interface/main/dated_reminders/dated_reminders_add.php?mID='+id, '_drAdd', 700, 500);
       }
     }
     
     function updateme(id){ 
       refreshInterval = <?php echo $updateDelay ?>;
       if(id > 0){
        $(".drTD").html('<p style="text-size:3em; margin-left:200px; color:black; font-weight:bold;"><?php echo xla("Processing") ?>...</p>'); 
       }
       if(id == 'new'){
        $(".drTD").html('<p style="text-size:3em; margin-left:200px; color:black; font-weight:bold;"><?php echo xla("Processing") ?>...</p>');
       }    
       top.restoreSession();
       // Send the skip_timeout_reset parameter to not count this as a manual entry in the
       //  timing out mechanism in LibreHealth EHR.
       $.post("<?php echo $GLOBALS['webroot']; ?>/interface/main/dated_reminders/dated_reminders.php",
         { drR: id, skip_timeout_reset: "1" }, 
         function(data) {
          if(data == 'error'){     
            alert("<?php echo addslashes(xl('Error Removing Message')) ?>");  
          }else{  
            if(id > 0){
              $(".drTD").html('<p style="text-size:3em; margin-left:200px; color:black; font-weight:bold;"><?php echo xla("Refreshing Reminders") ?> ...</p>');
            }
            $(".drTD").html(data); 
          }   
        // run updater every refreshInterval seconds 
        var repeater = setTimeout("updateme(0)", refreshInterval); 
       });
     }
      
      function openLogScreen(){
          top.restoreSession();
          $("#viewLog-iframe").iziModal('open');
      }
      
   
      function goPid(pid) {
        top.restoreSession();
        <?php 
      echo "  top.RTop.location = '../../patient_file/summary/demographics.php' " .
      "+ '?set_pid=' + pid;\n"; 
      ?>
   }
</script>
<?php  
   // initialize html string        
   $pdHTML = '<div class="dr_container">
                <h3>'.xlt("Show Reminders").'</h3>
                <div>
                  <div class="drHide">
                  <div id="viewLog-iframe"></div>
                  <div id="sendReminder-iframe"></div><!-- to initialize the izimodal -->
                    <p><a title="'.xla('View Past and Future Reminders').'" onclick="openLogScreen();" class="css_button_small cp-misc" href="#"><span>'.xlt('View Log').'</span></a>
                    <a data-text="0" class="css_button_small cp-misc sendReminder" href="#"><span>'.xlt('Send A Dated Reminder').'</span></a></p>
                    <br><br>';  

   $pdHTML .= getRemindersHTML($reminders,$today);
   $pdHTML .= '</div>
              </div> 
              </div>            
                ';
   // print output
   echo $pdHTML; 
?>