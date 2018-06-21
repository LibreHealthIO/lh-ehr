<?php
/**
 * Created by PhpStorm.
 * User: mua rachmann
 * Date: 6/16/18
 * Time: 10:07 PM
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



?>