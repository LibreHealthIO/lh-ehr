<?php
    /* 
     * This will query the database about the available messages.
     * A string on upper right side of the page will appear informing the number of unread messages.
     */
    
    // See getPnotesByUser() on Library/pnotes.inc.php to understand the arguments
    $unreadMessages = getPnotesByUser(true, false, $_SESSION['authUser'], true);
    // If there are more than 0 unread messages, create a phrase (eg. 3 unread messages), if there are NONE, the phrase is empty
    $notification = $unreadMessages > 0 ? $unreadMessages . " unread messages" : "";
?>

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
    <div id="messagesNotification" data-bind="click: unreadMessages"><?php echo xlt($notification); ?></div> 
</script>
