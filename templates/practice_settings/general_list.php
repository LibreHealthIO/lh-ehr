<html>
<head>
<link rel="stylesheet" href="<?php echo $this->css_header;?>" type="text/css">
<?php
   call_required_libraries(array("jquery-min-3-1-1","bootstrap","fancybox"));
?>
<script type="text/javascript" src="<?php echo $GLOBALS['rootdir'] . '/../library/js/common.js';?>"></script>
</head>

<body>

<div>
    <h5 style="padding:5px"><b><?php echo xlt("Practice Settings");?></b></h5>
</div>

<div class="col-md-12 col-xs-12 col-lg-12">
    <div class="row well">
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="border-bottom:0px">
            <li class="col-md-2 col-xs-4">
                <a href="<?php echo $this->top_action;?>pharmacy&action=list"><?php echo "<h5>".xlt("Pharmacies")."</h5>";?></a>
            </li>
            <li class="col-md-2 col-xs-4">
                <a href="<?php echo $this->top_action;?>insurance_company&action=list"><?php echo "<h5>".xlt("Insurance Companies")."</h5>";?></a>
            </li>
            <li class="col-md-2 col-xs-4">
                <a href="<?php echo $this->top_action;?>insurance_numbers&action=list"><?php echo "<h5>".xlt("Insurance Numbers")."</h5>";?></a>
            </li>
            <li class="col-md-2 col-xs-4">
                <a href="<?php echo $this->top_action;?>x12_partner&action=list"><?php echo "<h5>".xlt("X12 Partners")."</h5>";?></a>
            </li>
            <li class="col-md-2 col-xs-4">
                <a href="<?php echo $this->top_action;?>document&action=queue"><?php echo "<h5>".xlt("Documents")."</h5>";?></a>
            </li>
            <li class="col-md-2 col-xs-4">
                <a href="<?php echo $this->top_action;?>hl7&action=default"><?php echo "<h5>".xlt("HL7 Viewer")."</h5>";?></a>
            </li>
        </ul>        
    </div>
    
    <div>
        <h5><b><?php echo $this->action_name;?></b></h5>        
    </div>
    
    <div class="tab-content">
        <div class="tab-pane active">
            <?php echo $this->display_action;?>
        </div>
    </div>    
    
</div>
</body>
</html>