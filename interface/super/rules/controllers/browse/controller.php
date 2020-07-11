<?php
 // Copyright (C) 2010-2011 Aron Racho <aron@mi-squred.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

require_once( src_dir() . "/clinical_rules.php");

class Controller_browse extends BaseController {

    function _action_list() {
        $this->set_view( "list.php" );
    }
    
    function _action_plans_config() {
        // This file isn't currently being used and plans_config.php has security vulnerabilities.
        // Thus, the original code has been commented out and an error message is displayed instead.

        // $this->set_view( "plans_config.php" );
        $this->_action_error();
    }

    function _action_getrows() {
        $rows = array();

        $rules = resolve_rules_sql('','0',TRUE);
        foreach( $rules as $rowRule ) {
            $title = getLabel($rowRule['id'],'clinical_rules');
            $type = "Reminder";

            $row = array(
                "title" => $title,
                "type"  => $type,
                "id"    => $rowRule['id']
            );
            $rows[] = $row;
        }

        $this->emit_json( $rows );
    }

}
?>
