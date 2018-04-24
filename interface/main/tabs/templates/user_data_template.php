<?php
    /*
     * This will query the database about the available messages.
     * A string on upper right side of the page will appear informing the number of unread messages.
     */

    // See getPnotesByUser() on Library/pnotes.inc.php to understand the arguments
    $unreadMessages = getPnotesByUser(true, false, $_SESSION['authUser'], true);
    // If there are more than 0 unread messages, create a phrase (eg. 3 unread messages), if there are NONE, the phrase is empty
    $notification = $unreadMessages;
?>

<link rel="stylesheet" type="text/css" href=".../../css/userData.css"/>
<script type="text/html" id="user-data-template">
    <!-- ko with: user -->
    <nav class="nav navbar-nav navbar-right" style="margin-right: 10px !important;">
        <p class="clearfix"></p>
        <p class="clearfix"></p>
        <ul class="list-unstyled" id="userData">
            <a> <span data-bind="click: logout"><b><?php echo xlt("Logout");?></b></span></a>
            <li class="dropdown" >
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span data-bind="text:fname"></span>&nbsp;<span data-bind="text:lname"></span>
                </a>
                <ul class="dropdown-menu">
                    <li data-bind="click: userPrefs"><?php echo xlt("User Preferences");?></li>
                    <li data-bind="click: changePassword"><?php echo xlt("Change Pass Phrase");?></li>
                    <!--<li data-bind="click: logout"><?php //echo xlt("Logout");?></li>-->
                </ul>
            </li>
        </ul>
    </nav>

    <!-- /ko -->
    <div id="messagesNotification" data-bind="click: unreadMessages">
        <?php
            if($notification > 0){
                echo "
            <span class=\"nt-num\">$notification</span>
            <img class=\"img-responsive my-img\" src= $web_root/images/msgs.png width=\"20\">";
            }
        ?>
    </div>
</script>
