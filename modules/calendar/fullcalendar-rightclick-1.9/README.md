fullcalendar-rightclick.js
This small file adds two new callbacks to FullCalendar:

dayRightclick(date, jsEvent, view)
eventRightclick(event, jsEvent, view)
To use the file, include it after fullcalendar.js:

<script type="text/javascript" src="fullcalendar.js">
<script type="text/javascript" src="fullcalendar-rightclick.js">
You can then define the callbacks in FullCalendar's options dictionary:

$('#calendar').fullCalendar({
    dayRightclick: function(date, jsEvent, view) {
        alert('a day has been rightclicked!');
        // Prevent browser context menu:
        return false;
    },
    eventRightclick: function(event, jsEvent, view) {
    	alert('an event has been rightclicked!');
        // Prevent browser context menu:
        return false;
    }
});
The script is known to work with FullCalendar versions 2.3.1 - 2.6.1 but is likely to also work with later versions.

Live example
https://jsfiddle.net/a17kuyL0/

Contributing
When you submit a pull request, please make sure that the plugin still works with each FullCalendar version >= 2.3.1 ie. test it with 2.3.1, 2.3.2, 2.4.0, ... . The demo.html page in the repository makes testing very easy. Finally, please follow the same coding conventions as in the existing code, in particular the use of tabs instead of spaces.