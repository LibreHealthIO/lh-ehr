<?php
/* Copyright © 2010 by Andrew Moore */
/* Licensing information appears at the end of this file.
* @author  mua rachmann <muarachmann@gmail.com>
*/

class Installer
{

    public function __construct($cgi_variables)
    {
        // Installation variables
        $this->iuser = $cgi_variables['iuser'];
        $this->iuserpass = $cgi_variables['iuserpass'];
        $this->iuname = $cgi_variables['iuname'];
        $this->iufname = $cgi_variables['iufname'];
        $this->igroup = $cgi_variables['igroup'];
        $this->server = $cgi_variables['server']; // mysql server (usually localhost)
        $this->loginhost = $cgi_variables['loginhost']; // php/apache server (usually localhost)
        $this->port = $cgi_variables['port'];
        $this->root = $cgi_variables['root'];
        $this->rootpass = $cgi_variables['rootpass'];
        $this->login = $cgi_variables['login'];
        $this->pass = $cgi_variables['pass'];
        $this->dbname = $cgi_variables['dbname'];
        $this->collate = $cgi_variables['collate'];
        $this->site = $cgi_variables['site'];
        $this->source_site_id = $cgi_variables['source_site_id'];
        $this->clone_database = $cgi_variables['clone_database'];
        $this->no_root_db_access = $cgi_variables['no_root_db_access']; // no root access to database. user/privileges pre-configured
        $this->development_translations = $cgi_variables['development_translations'];

        // Make this true for IPPF.
        $this->ippf_specific = false;

        // Record name of sql access file
        $GLOBALS['OE_SITES_BASE'] = dirname(__FILE__) . '/../../sites';
        $GLOBALS['OE_SITE_DIR'] = $GLOBALS['OE_SITES_BASE'] . '/' . $this->site;
        $this->conffile = $GLOBALS['OE_SITE_DIR'] . '/sqlconf.php';

        // Record names of sql table files
        $this->main_sql = dirname(__FILE__) . '/../../sql/database.sql';
        $this->translation_sql = dirname(__FILE__) . '/../../modules/language_translations/currentLanguage_utf8.sql';
        $this->devel_translation_sql = "http://opensourceemr.com/cvs/languageTranslations_utf8.sql";
        $this->ippf_sql = dirname(__FILE__) . "/../../sql/ippf_layout.sql";
        $this->icd9 = dirname(__FILE__) . "/../../sql/icd9.sql";
        $this->cvx = dirname(__FILE__) . "/../../sql/cvx_codes.sql";
        $this->additional_users = dirname(__FILE__) . "/../../sql/official_additional_users.sql";
        $this->menu_def = dirname(__FILE__) . "/../../sql/menu_definitions.sql";

        // Record name of php-gacl installation files
        $this->gaclSetupScript1 = dirname(__FILE__) . "/../../gacl/setup.php";
        $this->gaclSetupScript2 = dirname(__FILE__) . "/../../acl_setup.php";

        // Prepare the dumpfile list
        $this->initialize_dumpfile_list();

        // Entities to hold error and debug messages
        $this->error_message = '';
        $this->debug_message = '';

        // Entity to hold sql connection
        $this->dbh = false;
    }

}

    ?>