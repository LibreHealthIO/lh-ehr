<html>
<head>
<?php html_header_show();?>

<link rel="stylesheet" href="<?php echo $this->css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['rootdir'] . '/../library/js/fancybox/jquery.fancybox-1.2.6.css';?>" media="screen" />
<script type="text/javascript" src="<?php echo $GLOBALS['rootdir'] . '/../library/js/jquery.1.3.2.js';?>"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['rootdir'] . '/../library/js/common.js';?>"></script>
</head>
<body class="body_top">

<div>
    <b><?php echo xl("Practice Settings");?></b>
</div>

<div>
    <div class="small">
        <a href="<?php echo $this->top_action;?>pharmacy&action=list"><?php echo xl("Pharmacies");?></a> |
        <a href="<?php echo $this->top_action;?>insurance_company&action=list"><?php echo xl("Insurance Companies");?></a> |
        <a href="<?php echo $this->top_action;?>insurance_numbers&action=list"><?php echo xl("Insurance Numbers");?></a> |
        <a href="<?php echo $this->top_action;?>x12_partner&action=list"><?php echo xl("X12 Partners");?></a> |
        <a href="<?php echo $this->top_action;?>document&action=queue"><?php echo xl("Documents");?></a> |
        <a href="<?php echo $this->top_action;?>hl7&action=default"><?php echo xl("HL7 Viewer");?></a>
    </div>
    
    <br/>
    <div class="section-header">
        <b><?php echo $this->action_name;?></b>
    </div>
    <div class="tabContainer">
        <div class="tab current">
            <?php echo $this->display_action;?>
        </div>
    </div>
    
</div>
</body>
</html>
