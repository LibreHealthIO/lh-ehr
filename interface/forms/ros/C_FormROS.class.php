<?php

require_once ($GLOBALS['fileroot'] . "/library/classes/Controller.class.php");
require_once ($GLOBALS['fileroot'] . "/library/forms.inc");
require_once("FormROS.class.php");

class C_FormROS extends Controller {

    var $template_dir;
                    var $form_action;
                    var $dont_save_link;
                    var $style;
                    var $form;
    
    function __construct($template_mod = "general") {
        parent::__construct();
        $returnurl = 'encounter_top.php';
        $this->template_mod = $template_mod;
        $this->template_dir = dirname(__FILE__) . "/templates/ros/";
        
                    $this->form_action = $GLOBALS['web_root'];
                    $this->dont_save_link = $GLOBALS['webroot'] . "/interface/patient_file/encounter/$returnurl";
                    $this->style = $GLOBALS['style'];
    }
    
    function default_action() {
        $ros = new FormROS();
        
                    $this->form = $ros;
                    ob_start(); //Start output Buffer
                    require_once($this->template_dir . $this->template_mod . "_new.php");
                    $echoed_content = ob_get_clean(); // gets content, discards buffer
                    return $echoed_content;
    }
    
    function view_action($form_id) {
        
        if (is_numeric($form_id)) {
            $ros = new FormROS($form_id);
        }
        else {
            $ros = new FormROS();
        }
        
                    $this->form = $ros;
                    ob_start(); //Start output Buffer
                    require_once($this->template_dir . $this->template_mod . "_new.php");
                    $echoed_content = ob_get_clean(); // gets content, discards buffer
                    return $echoed_content;
                    

    }
    
    function default_action_process() {
        if ($_POST['process'] != "true"){
                    
            return;
        }
        $this->ros = new FormROS($_POST['id']);
        
        parent::populate_object($this->ros);
        $this->ros->persist();
        
        if ($GLOBALS['encounter'] == "") {
            $GLOBALS['encounter'] = date("Ymd");
        }
        if(empty($_POST['id']))
        {
            addForm($GLOBALS['encounter'], "Review Of Systems", $this->ros->id, "ros", $GLOBALS['pid'], $_SESSION['userauthorized']);
            $_POST['process'] = "";
        }
        return;
    }
    
}



?>
