<?php

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

 require_once("../../globals.php");
 require_once("$srcdir/patient.inc");
 require_once("history.inc.php");
 require_once("$srcdir/options.inc.php");
 require_once("$srcdir/acl.inc");
 require_once("$srcdir/options.js.php");

?>
<html>
<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="../../../library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="../../../library/js/common.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    tabbify();
});
</script>

<style type="text/css">
</style>

</head>
<body class="body_top">

<?php
 if (acl_check('patients','med')) {
  $tmp = getPatientData($pid, "squad");
  if ($tmp['squad'] && ! acl_check('squads', $tmp['squad'])) {
   echo "<p>(".xlt('History not authorized').")</p>\n";
   echo "</body>\n</html>\n";
   exit();
  }
 }
 else {  
  echo "<p>(".xlt('History not authorized').")</p>\n";
  echo "</body>\n</html>\n";
  exit();
 }

 $result = getHistoryData($pid);
 if (!is_array($result)) {
  newHistoryData($pid);
  $result = getHistoryData($pid);
 }
?>

<?php if (acl_check('patients','med','',array('write','addonly') )) { ?>
<div>
    <span class="title"><?php echo xlt('Patient History / Lifestyle'); ?></span>
</div>
<div style='float:left;margin-right:10px'>
<?php echo xlt('for');?>&nbsp;<span class="title"><a href="../summary/demographics.php" onclick="top.restoreSession()"><?php echo text(getPatientName($pid)) ?></a></span>
</div>
<div>
    <a href="history_full.php"
     class="css_button"
     onclick="top.restoreSession()">
    <span><?php echo xlt("Edit");?></span>
    </a>
    <a href="../summary/demographics.php" class="css_button" onclick="top.restoreSession()">
        <span><?php echo xlt('Back To Patient');?></span>
    </a>
</div>
<br/>
<?php } ?>

<div style='float:none; margin-top: 10px; margin-right:20px'>
    <table>
    <tr>
        <td>
            <!-- Demographics -->
            <div id="HIS">
                <ul class="tabNav">
                   <?php display_layout_tabs('HIS', $result, $result2); ?>
                </ul>
                <div class="tabContainer">
                   <?php display_layout_tabs_data('HIS', $result, $result2); ?>
                </div>
            </div>
        </td>
    </tr>
    </table>
</div>

<script language='JavaScript'>
    // Array of skip conditions for the checkSkipConditions() function.
    var skipArray = [
        <?php echo $condition_str; ?>
    ];
    checkSkipConditions();
</script>

</body>
</html>
