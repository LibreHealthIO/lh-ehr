<?php
/**
 *
 * Copyright (C) 2016-2017 Jerry Padgett <sjpadgett@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Jerry Padgett <sjpadgett@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>LibreEHR <?php echo xlt(' Portal'); ?> | <?php echo xlt('Home'); ?></title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<meta name="description" content="LibreEHR Patient Portal">

<link href="<?php echo $GLOBALS['fonts_path']; ?>font-awesome-4-6-3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<link href="<?php echo $GLOBALS['standard_js_path']; ?>bootstrap-3-3-4/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<?php if ($_SESSION['language_direction'] == 'rtl') { ?>
    <link href="<?php echo $GLOBALS['standard_js_path']; ?>bootstrap-rtl-3-3-4/dist/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
<?php } ?>


<link href="assets/css/style.css" rel="stylesheet" type="text/css" />
<link href="sign/css/signer.css" rel="stylesheet" type="text/css" />
<link href="sign/assets/signpad.css" rel="stylesheet">

<script src="<?php echo $GLOBALS['standard_js_path']; ?>jquery-min-1-11-3/index.js" type="text/javascript"></script>
<script src="<?php echo $GLOBALS['standard_js_path']; ?>bootstrap-3-3-4/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="sign/assets/signpad.js" type="text/javascript"></script>
<script src="sign/assets/signer.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>emodal-1-2-65/dist/eModal.js"></script>

</head>
<body class="skin-blue fixed">
    <header class="header">
        <a href="home.php" class="logo"><img src='<?php echo $GLOBALS['images_path']; ?>/logo-full-con.png'/></a>
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas"
                role="button"> <span class="sr-only"><?php echo xlt('Toggle navigation'); ?></span> <span
                class="icon-bar"></span> <span class="icon-bar"></span> <span
                class="icon-bar"></span>
            </a>
            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    <li class="dropdown messages-menu"><a href="#"
                        class="dropdown-toggle" data-toggle="dropdown"> <i
                            class="fa fa-envelope"></i> <span class="label label-success"> <?php echo text($newcnt);?></span>
                    </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?php echo xlt('You have'); ?> <?php echo text($newcnt);?> <?php echo xlt('new messages'); ?></li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                <?php
                                 foreach ( $msgs as $i ) {
                                    if($i['message_status']=='New'){
                                        echo "<li><a href='" . $GLOBALS['web_root'] . "/patient_portal/messaging/messages.php'><h4>" . text($i['title']) . "</h4></a></li>";
                                    }
                                }
                                ?>
                                </ul>
                            </li>
                            <li class="footer"><a href="<?php echo $GLOBALS['web_root']; ?>/patient_portal/messaging/messages.php"><?php echo xlt('See All Messages'); ?></a></li>
                        </ul></li>

                    <li class="dropdown user user-menu"><a href="#"
                        class="dropdown-toggle" data-toggle="dropdown"> <i
                            class="fa fa-user"></i> <span><?php echo text($result['fname']." ".$result['lname']); ?>
                                <i class="caret"></i></span>
                    </a>
                        <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                            <li class="dropdown-header text-center"><?php echo xlt('Account'); ?></li>
                            <li><a href="<?php echo $GLOBALS['web_root']; ?>/patient_portal/messaging/messages.php"> <i class="fa fa-envelope-o fa-fw pull-right"></i>
                                    <span class="badge badge-danger pull-right"> <?php echo text($msgcnt);?></span> <?php echo xlt('Messages'); ?></a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo $GLOBALS['web_root']; ?>/patient_portal/messaging/secure_chat.php?fullscreen=true"> <i class="fa fa-user fa-fw pull-right"></i><?php echo xlt('Chat'); ?></a>
                                <a href="#openSignModal" data-toggle="modal" data-backdrop="true" data-target="#openSignModal"> <i
                                    class="fa fa-cog fa-fw pull-right"></i> <?php echo xlt('Settings'); ?></a></li>

                            <li class="divider"></li>

                            <li><a href="logout.php"><i class="fa fa-ban fa-fw pull-right"></i>
                                    <?php echo xlt('Logout'); ?></a></li>
                        </ul></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="left-side sidebar-offcanvas">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="pull-left info">
                        <p><?php echo xlt('Welcome') . ' ' . text($result['fname']." ".$result['lname']); ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> <?php echo xlt('Online'); ?></a>
                    </div>
                </div>
                <ul class="nav  nav-pills nav-stacked" style='font-color:#fff;'><!-- css class was sidebar-menu -->
                    <li data-toggle="pill"><a href="#profilepanel" data-toggle="collapse"
                        data-parent="#panelgroup"> <i class="fa fa-calendar-o"></i> <span><?php echo xlt('Profile'); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#lists" data-toggle="collapse"
                        data-parent="#panelgroup"> <i class="fa fa-list"></i> <span><?php echo xlt('Lists'); ?></span>
                    </a></li>
                    <li><a href="<?php echo $GLOBALS['web_root']; ?>/patient_portal/patient/onsitedocuments?pid=<?php echo attr($pid); ?>"> <i class="fa fa-gavel"></i> <span><?php echo xlt('Patient Documents'); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#appointmentpanel" data-toggle="collapse"
                        data-parent="#panelgroup"> <i class="fa fa-calendar-o"></i> <span><?php echo xlt("Appointment"); ?></span>
                    </a></li>

                    <li class="dropdown accounting-menu"><a href="#"
                        class="dropdown-toggle" data-toggle="dropdown"> <i
                            class="fa fa-book"></i> <span><?php echo xlt('Accountings'); ?></span>
                    </a>
                        <ul class="dropdown-menu">
                            <li data-toggle="pill"><a href="#ledgerpanel" data-toggle="collapse"
                                data-parent="#panelgroup"> <i class="fa fa-folder-open"></i> <span><?php echo xlt('Ledger'); ?></span>
                            </a></li>
                            <?php if ($GLOBALS['portal_two_payments']) { ?>
                                <li data-toggle="pill"><a href="#paymentpanel" data-toggle="collapse"
                                    data-parent="#panelgroup"> <i class="fa fa-credit-card"></i> <span><?php echo xlt('Make Payment'); ?></span>
                                </a></li>
                            <?php } ?>
                        </ul></li>
                    <li class="dropdown reporting-menu"><a href="#"
                        class="dropdown-toggle" data-toggle="dropdown"> <i
                            class="fa fa-calendar"></i> <span><?php echo xlt('Reports'); ?></span>
                    </a>
                        <ul class="dropdown-menu">
 <!-- this goes in the 3rd release of portal -->
                            <?php if ($GLOBALS['ccda_alt_service_enable'] > 1) { ?>
                                <li><a id="callccda" href="<?php echo $GLOBALS['web_root']; ?>/ccdaservice/ccda_gateway.php?action=startandrun">
                                        <i class="fa fa-envelope" aria-hidden="true"></i><span><?php echo xlt('View CCD'); ?></span></a></li>
                            <?php } ?>
<!-- end  -->
                            <li data-toggle="pill"><a href="#reportpanel" data-toggle="collapse"
                                data-parent="#panelgroup"> <i class="fa fa-folder-open"></i> <span><?php echo xlt('Report Content'); ?></span></a></li>
                            <li data-toggle="pill"><a href="#downloadpanel" data-toggle="collapse"
                                data-parent="#panelgroup"> <i class="fa fa-download"></i> <span><?php echo xlt('Download Documents'); ?></span></a></li>
                        </ul></li>

                    <li><a href="<?php echo $GLOBALS['web_root']; ?>/patient_portal/messaging/messages.php"><i class="fa fa-envelope" aria-hidden="true"></i>
                            <span><?php echo xlt('Secure Messaging'); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#messagespanel" data-toggle="collapse"
                        data-parent="#panelgroup"> <i class="fa fa-envelope"></i> <span><?php echo xlt("Secure Chat"); ?></span>
                    </a></li>
                    <li data-toggle="pill"><a href="#openSignModal" data-toggle="modal" > <i
                            class="fa fa-sign-in"></i><span><?php echo xlt('Signature on File'); ?></span>
                    </a></li>

                    <li><a href="logout.php"><i class="fa fa-ban fa-fw"></i> <span><?php echo xlt('Logout'); ?></span></a></li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>