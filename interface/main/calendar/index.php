<?php
require_once('../../globals.php');
?>
<html>
<head>
  <link href='full_calendar/fullcalendar.min.css' rel='stylesheet' />
  <link href='full_calendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />
  <link href='full_calendar_scheduler/scheduler.min.css' rel='stylesheet' />
  <script src='full_calendar/lib/moment.min.js'></script>
  <script src='full_calendar/lib/jquery.min.js'></script>
  <script src='full_calendar/fullcalendar.min.js'></script>
  <script src='full_calendar_scheduler/scheduler.min.js'></script>
</head>
<body>
  
  <div id='calendar'></div>
  
  <script>
    $(document).ready(function() {

      $('#calendar').fullCalendar({
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
        },
        defaultView: 'agendaDay',
        allDaySlot: false,
        events: {
          url: 'api/get_events.php',
          type: 'POST',
          error: function() {
              alert('There was an error while fetching events.');
          }
        }
      })

      });
  </script>
</body>
</html>
