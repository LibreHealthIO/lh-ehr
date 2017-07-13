<?php

require_once ($GLOBALS['fileroot'] . "/library/classes/Controller.class.php");
require_once ($GLOBALS['fileroot'] . "/library/forms.inc");
require_once("FormSOAP.class.php");

class C_FormSOAP extends Controller {

	var $template_dir;
                    var $form_action;
                    var $dont_save_link;
                    var $style;
                    var $data;
                    

    function __construct($template_mod = "general") {
    	parent::__construct();
    	$this->template_mod = $template_mod;
    	$this->template_dir = dirname(__FILE__) . "/templates/";    	
                    $this->form_action = $GLOBALS['web_root'];
                    $this->dont_save_link = $GLOBALS['form_exit_url'];
                    $this->style = $GLOBALS['style'];    }

    function default_action() {
    	$form = new FormSOAP();    	
                   $this->data = $form;
    	
                    ob_start(); //Start output Buffer
                    require_once($this->template_dir . $this->template_mod . "_new.php");
                    $echoed_content = ob_get_clean(); // gets content, discards buffer
                    return $echoed_content;
	}
	
	function view_action($form_id) {
		if (is_numeric($form_id)) {
    		$form = new FormSOAP($form_id);
    	}
    	else {
    		$form = new FormSOAP();
    	}
    	$dbconn = $GLOBALS['adodb']['db'];    	
    	
                    $this->data = $form;    	
                    ob_start(); //Start output Buffer
                    require_once($this->template_dir . $this->template_mod . "_new.php");
                    $echoed_content = ob_get_clean(); // gets content, discards buffer
                    return $echoed_content;                   

	}
	
	function default_action_process() {
		if ($_POST['process'] != "true")
			return;
		$this->form = new FormSOAP($_POST['id']);
		parent::populate_object($this->form);
		
		$this->form->persist();
		if ($GLOBALS['encounter'] == "") {
			$GLOBALS['encounter'] = date("Ymd");
		}
		if(empty($_POST['id']))
		{
			addForm($GLOBALS['encounter'], "SOAP", $this->form->id, "soap", $GLOBALS['pid'], $_SESSION['userauthorized']);
			$_POST['process'] = "";
		}
		return;
	}
    
}



?>
