<?php
    require_once("../../globals.php");
?>

<html>
    <head>
        <title><?php echo xlt("Error 404") ?></title>
    </head>
    <body>
        <h1>Not Found</h1>
        <p>The requested URL was not found.</p> 
        <p>Please enter a valid URL or return to the <a href="<?php echo $GLOBALS['webroot'].$GLOBALS['default_tab_1'];?>" onclick="top.restoreSession()">default tab</a>.</p>
    </body>
</html>