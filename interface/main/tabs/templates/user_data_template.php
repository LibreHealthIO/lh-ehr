<link rel="stylesheet" type="text/css" href=".../../css/userdata.css"/>
<script type="text/html" id="user-data-template">    
    <!-- ko with: user -->        
        <ul id="userdata" class="nav navbar-nav navbar-right" title="<?php echo xla('Authorization group') ?>">                  
            <li class="dropdown" > 
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-user"></span>                
                </a>            
                <ul class="dropdown-menu">
                    <li><span data-bind="text:fname"></span>&nbsp;<span data-bind="text:lname"></span></li>
                    <li class="divider"></li>
                    <?php if ($GLOBALS['development_flag'] ) { ?>
                    <li data-bind="click: framesMode"><?php echo xlt("Frames Mode");?></li>
                    <?php } ?>
                    <li data-bind="click: userPrefs"><?php echo xlt("User Preferences");?></li>
                    <li data-bind="click: changePassword"><?php echo xlt("Change Pass Phrase");?></li>
                    <li data-bind="click: logout"><?php echo xlt("Logout");?></li>            
                </ul>
            </li>            
        </ul>     
        
    <!-- /ko -->    
</script>
