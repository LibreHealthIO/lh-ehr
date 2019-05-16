<?php
/* Copyright © 2010 by Andrew Moore */
/* Licensing information appears at the end of this file.
* @author  mua rachmann <muarachmann@gmail.com>
*/

class Installer
{

    public function __construct()
    {
        // Installation variables
        $this->iuser                    = '';
        $this->iuserpass                = '';
        $this->iuname                   = '';
        $this->iufname                  = '';
        $this->igroup                   = '';
        $this->server                   = '';
        $this->loginhost                = '';
        $this->port                     = '';
        $this->root                     = '';
        $this->rootpass                 = '';
        $this->login                    = '';
        $this->pass                     = '';
        $this->dbname                   = '';
        $this->collate                  = '';
        $this->site                     = 'default';
        $this->source_site_id           = '';
        $this->clone_database           = '';
        $this->no_root_db_access        = '';
        $this->development_translations = '';

        // Record name of sql access file
        $GLOBALS['OE_SITES_BASE'] = dirname(__FILE__) .'/../../../sites';
        $GLOBALS['OE_SITE_DIR'] = $GLOBALS['OE_SITES_BASE'] . '/' . $this->site;
        $this->conffile  =  $GLOBALS['OE_SITE_DIR'] . '/sqlconf.php';

        // Record names of sql table files
        $this->main_sql = dirname(__FILE__) . '/../../sql/database.sql';
        $this->translation_sql = dirname(__FILE__) . '/../../modules/language_translations/currentLanguage_utf8.sql';
        //$this->devel_translation_sql = "https://github.com/LibreHealthIO/lh-ehr-contribs/currentLanguage_utf8.sql";  //does not exist
        $this->cvx = dirname(__FILE__) . "/../../sql/cvx_codes.sql";
        $this->additional_users = dirname(__FILE__) . "/../../sql/official_additional_users.sql";
        //$this->menu_def = dirname(__FILE__) . "/../../sql/menu_definitions.sql";  //REVIEW

        // Record name of php-gacl installation files
        $this->gaclSetupScript1 = dirname(__FILE__) . "/../../gacl/setup.php";
        $this->gaclSetupScript2 = dirname(__FILE__) . "/../../acl_setup.php";

        // Prepare the dumpfile list

        // Entities to hold error and debug messages
        $this->error_message = '';
        $this->debug_message = '';

        // Entity to hold sql connection
        $this->dbh = false;
    }

}

    ?>