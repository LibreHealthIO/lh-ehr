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

    $this->assign('title', xlt("LibreHealth EHR Patient Portal Secure"));
    $this->assign('nav','secureapp');

    $this->display('_Header.tpl.php');
?>

<div class="container">

    <?php if ($this->feedback) { ?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php $this->eprint($this->feedback); ?>
        </div>
    <?php } ?>

    <!-- #### this view/tempalate is used for multiple pages.  the controller sets the 'page' variable to display differnet content ####  -->

    <?php if ($this->page == 'login') { ?>

        <div class="hero-unit">
            <h1><?php echo xlt('Login'); ?></h1>
            <p><?php echo xlt('This portals authentication.'); ?> <strong><?php echo xlt('Your credentials are provided by your provider'); ?></strong>.</p>
            <p>
                <a href="secureuser" class="btn btn-primary btn-large"><?php echo xlt('Patient Access'); ?></a>
                <a href="secureadmin" class="btn btn-primary btn-large"><?php echo xlt('Provider Access'); ?></a>
                <?php if (isset($this->currentUser)) { ?>
                    <a href="logout" class="btn btn-primary btn-large"><?php echo xlt('Logout'); ?></a>
                <?php } ?>
            </p>
        </div>

        <form class="well" method="post" action="login">
            <fieldset>
            <legend><?php echo xlt('Enter your credentials'); ?></legend>
                <div class="control-group">
                <input id="username" name="username" type="text" placeholder="<?php echo xlt('Username'); ?>..." />
                </div>
                <div class="control-group">
                <input id="password" name="password" type="password" placeholder="<?php echo xlt('Password'); ?>..." />
                </div>
                <div class="control-group">
                <button type="submit" class="btn btn-primary"><?php echo xlt('Login'); ?></button>
                </div>
            </fieldset>
        </form>

    <?php } else { ?>

        <div class="hero-unit">
            <h1><?php echo xlt('Secure'); ?> <?php $this->eprint($this->page == 'userpage' ? 'Patient' : 'Provider'); ?> <?php echo xlt('Page'); ?></h1>
            <p><?php echo xlt('This page is accessible only to'); ?> <?php $this->eprint($this->page == 'userpage' ? 'authenticated patients' : 'administrators'); ?>.
            <?php echo xlt('You are currently logged in as'); ?> '<strong><?php $this->eprint($this->currentUser->Username); ?></strong>'</p>
            <p>
                <a href="secureuser" class="btn btn-primary btn-large"><?php echo xlt('Visit Patient Home Page'); ?></a>
                <a href="secureadmin" class="btn btn-primary btn-large"><?php echo xlt('Visit Provider Home Page'); ?></a>
                <a href="logout" class="btn btn-primary btn-large"><?php echo xlt('Logout'); ?></a>
            </p>
        </div>
    <?php } ?>

</div> <!-- /container -->

<?php
    $this->display('_Footer.tpl.php');
?>