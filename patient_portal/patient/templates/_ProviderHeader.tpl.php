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
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <title><?php $this->eprint($this->title); ?></title>
        <meta content="width=device-width, initial-scale=1, user-scalable=yes" name="viewport">
        <base href="<?php $this->eprint($this->ROOT_URL); ?>" />
        <meta name="description" content="Provider Portal" />
        <!--<meta name="author" content="Dashboard | sjpadgett@gmail.com" />-->

        <!-- Styles -->
        <link href="<?php echo $GLOBALS['standard_js_path']; ?>/bootstrap-3-3-4/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <?php if ($_SESSION['language_direction'] == 'rtl') { ?>
            <link href="<?php echo $GLOBALS['standard_js_path']; ?>/bootstrap-rtl-3-3-4/dist/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
        <?php } ?>

        <link href="styles/style.css" rel="stylesheet" />
        <link href="<?php echo $GLOBALS['fonts_path']; ?>/font-awesome-4-6-3/css/font-awesome.min.css" rel="stylesheet" />
        <link href="../sign/css/signer.css" rel="stylesheet" type="text/css" />
        <link href="../sign/assets/signpad.css" rel="stylesheet">

        <script type="text/javascript" src="scripts/libs/LAB.min.js"></script>
        <script type="text/javascript">
            $LAB.setGlobalDefaults({BasePath: "<?php $this->eprint($this->ROOT_URL); ?>"});
            $LAB.script("<?php echo $GLOBALS['standard_js_path']; ?>/jquery-min-1-11-3/index.js")
                //.script("../sign/assets/signpad.js")
                .script("../sign/assets/signer.js")
                .script("<?php echo $GLOBALS['standard_js_path']; ?>/bootstrap-3-3-4/dist/js/bootstrap.min.js")
                .script("<?php echo $GLOBALS['standard_js_path']; ?>/underscore-1-8-3/underscore-min.js")
                .script("<?php echo $GLOBALS['standard_js_path']; ?>/moment-2-13-0/moment.js")
                .script("<?php echo $GLOBALS['standard_js_path']; ?>/backbone-1-3-3/backbone-min.js")

                .script("scripts/app.js")
                .script("scripts/model.js").wait()
                .script("scripts/view.js").wait()
        </script>
    </head>

    <body>
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                    <div class="navbar-header"><a class="navbar-brand" href="./provider"><?php echo xlt('Home'); ?></a>
                        <a class="navbar-toggle btn-default" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="glyphicon glyphicon-bar"></span>
                            <span class="glyphicon glyphicon-bar"></span>
                            <span class="glyphicon glyphicon-bar"></span>
                        </a>
                        </div>
                        <div class="container">
                        <div class="navbar-collapse">
                            <ul class="nav navbar-nav">
                                <!-- <li <?php //if ($this->nav=='patientdata') { echo 'class="active"'; } ?>><a href="./patientdata?pid=30">Patient Demo's</a></li>
                                <li <?php //if ($this->nav=='onsiteactivityviews') { echo 'class="active"'; } ?>><a href="./onsiteactivityviews">Patient's Activities</a></li> -->
                                </ul>
                            <!-- <ul class="nav pull-right navbar-nav">
                                <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-lock"></i> Login <i class="caret"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="./loginform">Login</a></li>
                                    <li class="divider"></li>
                                    <li><a href="./secureuser">Patient Dashboard<i class="icon-lock"></i></a></li>
                                    <li><a href="./secureadmin">Provider Dashboard<i class="icon-lock"></i></a></li>
                                </ul>
                                </li>
                            </ul> -->
                        </div><!--/.nav-collapse -->
                    </div>
                </div>
            </div>