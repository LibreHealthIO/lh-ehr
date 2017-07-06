<?php
require_once('../../globals.php');
require_once('../../../library/calendar.inc');
require_once('../../../library/patient.inc');
require('includes/session.php');

?>
<html>
<head>  
  <link href='full_calendar/fullcalendar.min.css' rel='stylesheet' />
  <link href='full_calendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />
  <link href='full_calendar_scheduler/scheduler.min.css' rel='stylesheet' />
  
  <link href='css/index.css' rel='stylesheet' />
  
  <script src='full_calendar/lib/moment.min.js'></script>
  <script src='full_calendar/lib/jquery.min.js'></script>
  <script src='full_calendar/fullcalendar.min.js'></script>
  <script src='full_calendar_scheduler/scheduler.min.js'></script>
  
  <script type="text/javascript" src="../../../library/dialog.js"></script>
</head>
<body>
  <div id="sidebar">
    <form name='theform' id='theform' method='post' onsubmit='return top.restoreSession()'>
    <?php
      // CHEMED
      $facilities = getUserFacilities($_SESSION['authId']); // from users_facility
      if ( $_SESSION['pc_facility'] ) {
         $provinfo = getProviderInfo('%', true, $_SESSION['pc_facility']);
      } else {
         $provinfo = getProviderInfo();
      }
      
      // lemonsoftware
      if ($_SESSION['authorizeduser'] == 1) {
        $facilities = getFacilities();
      } else {
        $facilities = getUserFacilities($_SESSION['authId']); // from users_facility
        if (count($facilities) == 1)
          $_SESSION['pc_facility'] = key($facilities);
      }
      
      if (count($facilities) > 1) {
        echo "   <select name='pc_facility' id='pc_facility' >\n";
        if ( !$_SESSION['pc_facility'] ) $selected = "selected='selected'";
        echo "    <option value='0' $selected>"  .xl('All Facilities'). "</option>\n";

        foreach ($facilities as $fa) {
            $selected = ( $_SESSION['pc_facility'] == $fa['id']) ? "selected" : "" ;
            echo "    <option style=background-color:".htmlspecialchars($fa['color'],ENT_QUOTES)." value='" .htmlspecialchars($fa['id'],ENT_QUOTES). "' $selected>"  .htmlspecialchars($fa['name'],ENT_QUOTES). "</option>\n";
        }
        echo "   </select>\n";
      }

      // PROVIDERS
      foreach($_SESSION['pc_username'] as $provider) {   //if __PC_ALL__ is one of selected, we set session as all the providers
        if($provider == "__PC_ALL__") {
          $_SESSION['pc_username'] = array();
          foreach($provinfo as $doc) {
            array_push($_SESSION['pc_username'], $doc['username']);
          }
        }
      }
      
      
      // remove those providers which aren't in provinfo from session
      $provinfo_users = array();
      foreach($provinfo as $doc) {
        array_push($provinfo_users, $doc['username']);
      }
      $_SESSION['pc_username'] = array_intersect($_SESSION['pc_username'], $provinfo_users);
      
      echo "   <select multiple size='15' name='pc_username[]' id='pc_username'>\n";
      echo "    <option value='__PC_ALL__' title='All Users'>"  .xl ("All Users"). "</option>\n";
      foreach ($provinfo as $doc) {
        $username = $doc['username'];
        echo "    <option value='$username'";
        foreach ($_SESSION['pc_username'] as $provider) {          
          if ($provider == $username) {
            echo " selected";
          }
        }
        echo " title='" . $doc['lname'] . ", " . $doc['fname'] . "'>" . htmlspecialchars($doc['lname'],ENT_QUOTES) . ", " . htmlspecialchars($doc['fname'],ENT_QUOTES) . "</option>\n";
      }
      echo "   </select>\n";
    ?>
  </form>
    <?php 
    if($_SESSION['pc_facility'] == 0){
      echo '<div id="facilityColor">';
      echo '<table>';
      foreach ($facilities as $f){
        echo "   <tr><td><div style=background-color:".$f['color'].";font-weight:bold>".htmlspecialchars($f['name'],ENT_QUOTES)."</div></td></tr>";
      }
      echo '</table>';
      echo '</div>';
    }
    ?>
  </div>
  
  <div id='calendar-container'>
    <div id='calendar'></div>
  </div>
  
  <script>
    $(document).ready(function() {

      $('#calendar').fullCalendar({
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        height: 'parent',
        header: {
        left: 'prev,next today',
        center: 'title',
        right: 'timelineMonth,timelineWeek,timelineDay, providerAgenda'
        },
        views: {
          week: {
            // options apply to basicWeek and agendaWeek views
            groupByResource: true
          },
          day: {
            // options apply to basicDay and agendaDay views
            groupByDateAndResource: true
          }, 
          providerAgenda: {
            type: 'agenda',
            duration: { days: 1 },
            buttonText: 'agenda',
            groupByDateAndResource: true
          }
        },
        resourceAreaWidth: "25%",
        navLinks: true,
        selectable: true,
        //selectHelper: true,
        defaultView: 'timelineDay', // TODO: Set according to globals
        defaultTimedEventDuration: '00:15:00',
        minTime: '<?php echo $GLOBALS['schedule_start'] ?>:00:00',
        maxTime: '<?php echo $GLOBALS['schedule_end'] + 1 ?>:00:00',  // adding 1 to make ending hour as inclusive
        slotDuration: '00:<?php echo $GLOBALS['calendar_interval'] ?>:00',
        resources: {
          url: 'includes/get_providers.php',
          type: 'POST',
          error: function() {
              alert('There was an error while fetching providers.');
          }
        },
        events: {
          url: 'includes/get_provider_events.php',
          type: 'POST',
          error: function() {
              alert('There was an error while fetching appointments.');
          }
        },
        eventMouseover: function(calEvent, element, view) {
          var tooltip = '<div class="tooltipevent">' + calEvent.title + '</div>';
          var $tooltip = $(tooltip).appendTo('body');

         $(this).mouseover(function(e) {
             $(this).css('z-index', 10000);
             $tooltip.fadeIn('500');
             $tooltip.fadeTo('10', 1.9);
         }).mousemove(function(e) {
             $tooltip.css('top', e.pageY + 10);
             $tooltip.css('left', e.pageX + 20);
         });
        },
        eventMouseout: function(calEvent, element, view) {
          $(this).css('z-index', 8);
          $('.tooltipevent').remove();
        },
        select: function(start, end, jsEvent, view, resource) {
          dlgopen('../calendar/add_edit_event.php?' + '&starttimeh=' + start.get('hours') + '&userid=' + resource.id + 
          '&starttimem=' + start.get('minutes') + '&date=' + start.format('YYYYMMDD') // + '&catid=' + 0
           ,'_blank', 775, 375);
			  },
        eventClick: function(calEvent, jsEvent, view) {
          var pccattype = (calEvent['e_pid'] && calEvent['e_pid'] > 0) ? 0 :  1;
          console.log(pccattype);
          dlgopen('../calendar/add_edit_event.php?date='+ calEvent.start.format('YYYYMMDD') +'&eid=' + calEvent.id +'&prov=' + pccattype, '_blank', 775, 375);
        }
      })
      
      // TODO: Use a global
      setInterval(function() { $('#calendar').fullCalendar( 'refetchEvents' ) }, 3000);
      
    });
    
    
    
    $("#pc_username").change(function() { $('#theform').submit(); });
    $("#pc_facility").change(function() { $('#theform').submit(); });
  </script>
</body>
</html>
