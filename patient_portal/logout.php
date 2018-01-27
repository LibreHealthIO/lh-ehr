<?php
 /**
 *
 * Patient Portal Logout
 *
 * Copyright (C) 2016-2017 Jerry Padgett <sjpadgett@gmail.com>
 * Copyright (C) 2011 Cassian LUP <cassi.lup@gmail.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Cassian LUP <cassi.lup@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the authors and to the LibreHealth EHR community.
 *
 */
 require_once (dirname( __FILE__ )."/lib/appsql.class.php" );

    //continue session
        session_start();
$logit = new ApplicationTable();
$logit->portalLog('logout',$_SESSION['pid'],($_SESSION['portal_username'].': '.$_SESSION['ptName'].':success'));

    //landing page definition -- where to go after logout
        $landingpage = "index.php?site=".$_SESSION['site_id'];

    //log out by killing the session
    session_destroy();

    //redirect to pretty login/logout page
    header('Location: '.$landingpage.'&logout');
    // 
?>
