<?php

require_once("../../globals.php");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/htmlspecialchars.inc.php");

?>
<html>
<head>
<?php
  html_header_show();
  call_required_libraries(array("jquery-min-3-1-1","bootstrap"));
?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript">
    $(document).ready( function() {
        $("#close").click(function() {
            parent.$("#appointmentLog-iframe").iziModal('close');
        });
    });
</script>
<style type="text/css">
.log-container{
    max-width: 470px;
    max-height: 300px;
    overflow: auto;
    border: 2px solid black;
}

.log-row{
    border: 1px solid black;
}

.log-font{
    font-size: 1.2em;
    text-align: center;
}
</style>
</head>
<body class="body_top">
    <a class="css_button large_button" id="close" href="#">
        <span class="css_button_span large_button_span" style="font-size: 14px;"><?php echo htmlspecialchars(xl('Close'), ENT_NOQUOTES); ?></span>
    </a>
    <hr>
    <div class="log-container log-font">

    </div>
</body>
</html>

