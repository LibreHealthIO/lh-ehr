<?php
    // Need globals.php because it references htmlspecialchars.inc.php
    // Which contains the translation functions
    require_once("../../../interface/globals.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <span class="title" style="display: none;"><?php echo xlt("XML Editor"); ?></span>
        <link rel="stylesheet" href="screen.css">
    </head>
    <body>
        <header>
            <h1><?php echo xlt("XML Editor"); ?></h1>
            <i></i><b></b><u></u><s></s>
        </header>

        <div class="editor-container">
            <div id="editor1"></div>
        </div>

        <script src="doctored/doctored.js"></script>
        <script>
            var callback = function(){
                if(window.console && window.console.log) console.log("Doctored.js: initialized " + this.id + "!")
            };

            doctored.init("#editor1", {
                onload: callback,
                id:     "editor1" // unique id per domain used for saving data (localStorage key). Doesn't need to be the same as the element Id.
            });
        </script>
    </body>
</html>
