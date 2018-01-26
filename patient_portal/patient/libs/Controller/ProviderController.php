<?php
/** @package LibreHealth EHR::Controller */

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

/** import supporting libraries */
require_once("AppBaseController.php");

/**
 * DefaultController is the entry point to the application
 *
 * @package LibreHealth EHR::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class ProviderController extends AppBaseController
{
    /**
     * Override here for any controller-specific functionality
     */
    protected function Init()
    {
        parent::Init();

        // $this->RequirePermission(SecureApp::$PERMISSION_USER,'SecureApp.LoginForm');
    }

    /**
     * Display the home page for the application
     */
    public function Home()
    {
        $cpid=$cuser=0;
        if (isset($_SESSION['authUser'])) {
            $cuser = $_SESSION['authUser'];
        } else {
                header( "refresh:5;url= ./provider" );
                echo 'Shared session not allowed with Portal!!!  <br>Onsite portal is using this session<br>Waiting until Onsite Portal is logged out........';
                exit;
            }
        $this->Assign ( 'cpid', $GLOBALS['pid'] );
        $this->Assign ( 'cuser', $cuser );

        $this->Render();
    }

    /**
     * Displayed when an invalid route is specified
     */
    public function Error404()
    {
        $this->Render();
    }

    /**
     * Display a fatal error message
     */
    public function ErrorFatal()
    {
        $this->Render();
    }

    public function ErrorApi404()
    {
        $this->RenderErrorJSON('An unknown API endpoint was requested.');
    }

}
?>