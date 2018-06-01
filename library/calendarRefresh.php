<?php

//this function refreshes Calendar when certain events are triggered
function refreshCalendar() {
    echo "<script type='text/javascript'>";
      echo "var docRoot = top.$('#mainBox');
            //get references to all possible frames for calendar
            var leftFrame = docRoot.find('iframe[name=" . "lst" . "]'); //initial loading of EHR
            var rightFrame = docRoot.find('iframe[name=" . "pat" . "]'); //initial loading of EHR
            var calFrame = docRoot.find('iframe[name=" . "cal" . "]'); //via main menu

            var calFrameStr = 'interface/main/main_info.php'; //url of Calendar Screen used for identification

            //get source string of frames
            var leftFrameSrc = leftFrame.attr('src');
            var rightFrameSrc = rightFrame.attr('src');
            var calFrameSrc = calFrame.attr('src');

            //when Calendar is opened via main menu
            if (calFrameSrc !== undefined) {
                calFrame.attr('src', calFrameSrc); //refresh frame
            } else {
                //when Calendar is opened in one of two initially loaded frames

                //if opened in left frame
                if (leftFrameSrc !== undefined) {
                    if (leftFrameSrc.indexOf(calFrameStr) !== -1) {
                        leftFrame.attr('src', leftFrameSrc); //refresh frame
                    }
                }

                //if opened in right frame
                if (rightFrameSrc !== undefined) {
                    if (rightFrameSrc.indexOf(calFrameStr) !== -1) {
                        rightFrame.attr('src', rightFrameSrc); //refresh frame
                    }
                }
            }";
    echo "</script>";
}

?>
