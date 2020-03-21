<?php
// DatePicker minimums
$srcdir = "../library";
require_once "$srcdir/formdata.inc.php";
/** Current format date */
if($GLOBALS['date_display_format'] == 1) {
   $title_tooltip = "MM/DD/YYYY";
} elseif($GLOBALS['date_display_format'] == 2) {
   $title_tooltip = "DD/MM/YYYY";
} else {
   $title_tooltip = "YYYY-MM-DD";
}
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);
if ( isset($_POST['form_from_date']) && !empty($_POST['form_to_date']) && $_POST['form_from_date'] ) {
        $form_from_date = fixDate($_POST['form_from_date'], date(DateFormatRead(true)));
}
?>

<html>
<body>
<script type="text/javascript" src='<?php echo "$srcdir/js/jquery-1.9.1.min.js"; ?>'></script>
<link rel="stylesheet" href="<?php echo "$srcdir/css/jquery.datetimepicker.css"; ?>">

<input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php oeFormatShortDate(attr($form_from_date)); ?>' />

<script type="text/javascript" src='<?php echo "$srcdir/js/jquery.datetimepicker.full.min.js"; ?>'></script>
<script>
    $(function() {
        $("#form_from_date").datetimepicker({
            timepicker: false,
            format: "<?php $DateFormat; ?>"
        });
        $("#form_to_date").datetimepicker({
            timepicker: false,
            format: "<?php $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?php $DateLocale;?>');
    });
</script>
</body>
</html>