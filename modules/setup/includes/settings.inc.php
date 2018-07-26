<?php
/**
 * FIles for setting various parameters for the setup procedure setup looks up to this for cinstant variable declarations
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package Librehealth EHR
 * @author Mua Laurent <muarachmann@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */
?>

<?php

    // -------------------------------------------------------------------------
    // 1. GLOBAL SETTINGS(constant parameters for LibreEHR would be put in here)
    // -------------------------------------------------------------------------


    // *** default template (could do two templates)
    define('LIBRE_TEMPLATE', 'default');


    // -------------------------------------------------------------------------
    // 2. GENERAL SETTINGS
    // -------------------------------------------------------------------------

    $site = 'default';


    $php_forbid = '7.1';

    // Record name of sql access file
    $LIBRE_SITES_BASE = dirname(__FILE__) .'/../../../sites';
    $LIBRE_SITE_DIR = $LIBRE_SITES_BASE. '/' . $site;

    //configuration file
    $conffile  =  $LIBRE_SITE_DIR . '/sqlconf.php';


    //writable directories
    $docsDirectory = $LIBRE_SITE_DIR."/documents";
    $billingDirectory = $LIBRE_SITE_DIR."/edi";
    $billingDirectory2 = $LIBRE_SITE_DIR."/era";

    $billingLogDirectory = $LIBRE_SITE_DIR."/logs";
    $lettersDirectory = $LIBRE_SITE_DIR."/letter_templates";
    $gaclWritableDirectory = dirname(__FILE__)."/../../../gacl/admin/templates_c";


    // Record name of php-gacl installation files
    $gaclSetupScript1 = dirname(__FILE__) . "/../../../gacl/setup.php";
    $gaclSetupScript2 = dirname(__FILE__) . "/../../../acl_setup.php";



?>