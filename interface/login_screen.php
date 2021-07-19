<?php
$ignoreAuth=true;
include_once("./globals.php");
?>
<html>
<body>

<script LANGUAGE="JavaScript">
 top.location.href='<?php echo attr($rootdir) . "/login/login.php?site=". attr($_SESSION['site_id']); ?>';
</script>

<a href='<?php echo attr($rootdir) . "/login/login.php?site=". attr($_SESSION['site_id']); ?>'><?php xl('Follow manually','e'); ?></a>

<p>
<?php xl('LibreEHR requires Javascript to perform user authentication.','e'); ?>

</body>
</html>
