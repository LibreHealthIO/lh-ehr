<?php

/** 
* 
* Copyright (C) 2008-2016 Rod Roark <rod@sunsetsystems.com>
* Copyright (C) 2016-2018 Nilesh Prasad <prasadnilesh96@gmail.com
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
* @author  Nilesh Prasad <prasadnilesh96@gmail.com
* @author  Sherwin Gaddis <sherwingaddis@gmail.com>
* @author  Rod Roark <rod@sunsetsystems.com>
* @author  Brady Miller <brady@sparmy.com>
* @link    http://librehealth.io
*/

require_once ($GLOBALS['fileroot'] . "/library/classes/Controller.class.php");
require_once ($GLOBALS['fileroot'] . "/library/forms.inc");
require_once("FormPriorAuth.class.php");

class C_FormPriorAuth extends Controller {

	var $template_dir;
	var $form_action;
    var $dont_save_link;
    var $style;
	var $prior_auth;
	var $prior_auth_number;
	var $NoAuth;
	var $alert;
	var $override;
    var $view;
	
    function C_FormPriorAuth($template_mod = "general") {
    	parent::__construct();
    	$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
    	$this->template_mod = $template_mod;
    	$this->template_dir = dirname(__FILE__) . "/templates/prior_auth/";
    	$this->form_action = $GLOBALS['web_root'];
        $this->dont_save_link = $GLOBALS['webroot'] . "/interface/patient_file/encounter/$returnurl";
        $this->style =  $GLOBALS['style'];
		
    }
    
    function default_action() {
		$prior_auth = new FormPriorAuth();
		$this->prior_auth = $prior_auth;
		$this->prior_auth_number = "";    	
		//	Start output Buffer
		ob_start(); 
		require_once($this->template_dir . $this->template_mod . "_new.php");
		$echoed_content = ob_get_clean(); // gets content, discards buffer
		return $echoed_content;
	}
	
	function view_action($form_id) {
		if (is_numeric($form_id)) {
    		$prior_auth = new FormPriorAuth($form_id);
    	}
    	else {
    		$prior_auth = new FormPriorAuth();
    	}
		$this->view = true;
		$this->prior_auth = $prior_auth;
		$this->NoAuth= $prior_auth->get_not_req();
		$this->alert = $prior_auth->get_units();
		$this->override = $prior_auth->get_override();		
		//Start output Buffer
		ob_start(); 
		require_once($this->template_dir . $this->template_mod . "_new.php");
		$echoed_content = ob_get_clean(); // gets content, discards buffer
		return $echoed_content; 

	}
	
	function default_action_process() {
		if ($_POST['process'] != "true")
			return;
		$this->prior_auth = new FormPriorAuth($_POST['id']);
		parent::populate_object($this->prior_auth);
		
		
		$this->prior_auth->persist();
		if ($GLOBALS['encounter'] == "") {
			$GLOBALS['encounter'] = date("Ymd");
		}
		addForm($GLOBALS['encounter'], "Prior Authorization Form", $this->prior_auth->id, "prior_auth_enhanced", $GLOBALS['pid'], $_SESSION['userauthorized']);
		$_POST['process'] = "";
		return;
	}
    
}



?>