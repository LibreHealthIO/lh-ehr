<html>
<head>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'];?>/library/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="<?php echo  $GLOBALS['webroot'];?>/library/js/jquery-ui-1.8.6.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'];?>/library/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'];?>/library/js/clickmap.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo $this->form->template_dir;?>/css/ui-lightness/jquery-ui-1.8.6.custom.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->form->template_dir;?>/css/clickmap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['css_header'];?>" />
</head>
<body>
<div class="outer-container">
	<div id="container" class="container graphic-pain-map">
		<img src="<?php echo $this->form->image;?>"/>
	</div>
	<div id="legend" class="legend graphic-pain-map">
		<div class="body">
			<ul></ul>
		</div>
	</div>
</div>
<p style="clear:both">

<div class="nav">
	<button id="btn_save"><?php echo "Save";?></button>
	<button id="btn_clear"><?php echo "Clear";?></button>
	<button id="cancel"><?php echo "Cancel";?></button>
	<p>
        <?php echo "Click a spot on the graphic to add a new annotation, click it again to remove it";?> <br/>
        <?php echo "The 'Clear' button will remove all annotations.";?>
    </p>
</div>

<div class="dialog-form" style="display:none">
	<fieldset>
	<label for="label"><?php echo "Label";?></label>
	<input type="text" name="label" id="label" class="text ui-widget-content ui-corner-all label" />
	<label for="options"></label>
	<select name="options">
	</select>
	<label for="detail"><?php echo "Detail";?></label>
	<textarea name="detail" id="detail" class="textarea ui-widget-content ui-corner-all detail"></textarea>
	</fieldset>
</div>

<div class="marker-template" style="display:none">
	<span class='count'></span>
</div>

<script type="text/javascript">
var cancellink = '<?php echo $this->dont_save_link;?>';

    $(document).ready( function() {
                $("#cancel").click(function() { location.href=cancellink; });
		var optionsLabel = {$this->form->optionsLabel};
		var options = {$this->form->optionList};
		var data = {$this->form->data};
		var hideNav = {$this->form->hideNav};

		clickmap( {
                        hideNav: hideNav,
                        data: data,
			dropdownOptions: { label: optionsLabel, options: options },
			container: $("#container")
		} );
	});

</script>

<form id="submitForm" name="submitForm" method="post" action="<?php echo $this->form->saveAction;?>" onsubmit="return top.restoreSession()">
    <input type="hidden" name="id" value="<?php echo $this->form->get_id();?>" />
    <input type="hidden" name="pid" value="<?php echo $this->form->get_pid();?>" />
    <input type="hidden" name="process" value="true" />
    <input type="hidden" name="data" id="data" value=""/>
</form>
</body>

</html>
