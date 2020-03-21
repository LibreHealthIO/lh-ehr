<?php

require_once ($GLOBALS['fileroot'] . "/library/classes/Controller.class.php");
require_once($GLOBALS['fileroot'] . "/library/classes/Pharmacy.class.php");

class C_Pharmacy extends Controller {

    var $template_mod;
    var $pharmacies;
    var $form_action;
    var $style;
    var $web_root;
    var $current_action;
    var $pharmacy;

    function __construct($template_mod = "general") {
        parent::__construct();
        $this->pharmacies = array();
        $this->template_mod = $template_mod;

        $this->form_action = $GLOBALS['webroot'] . "/controller.php?" . $_SERVER['QUERY_STRING'];
        $this->current_action = $GLOBALS['webroot'] . "/controller.php?" . "practice_settings&pharmacy&";
        $this->style = $GLOBALS['style'];
        $this->web_root = $GLOBALS['webroot'];
        $this->Pharmacy = new Pharmacy();
    }

    function default_action() {
        return $this->list_action();
    }

    function edit_action($id = "", $patient_id = "", $p_obj = null) {
        if ($p_obj != null && get_class($p_obj) == "pharmacy") {
            $this->pharmacies[0] = $p_obj;
        } elseif (get_class($this->pharmacies[0]) != "pharmacy") {
            $this->pharmacies[0] = new Pharmacy($id);
        }

        if (!empty($patient_id)) {
            $this->pharmacies[0]->set_patient_id($patient_id);
            $this->pharmacies[0]->set_provider($this->pharmacies[0]->patient->get_provider());
        }

        $this->pharmacy = $this->pharmacies[0];

        ob_start(); //Start output Buffer
        require_once($GLOBALS['template_dir'] . "pharmacies/" . $this->template_mod . "_edit.php");
        $echoed_content = ob_get_clean(); // gets content, discards buffer
        return $echoed_content;
    }

    function list_action($sort = "") {


        if (!empty($sort)) {

            $this->pharmacies = $this->Pharmacy->pharmacies_factory("", $sort);
        } else {
            $this->pharmacies = $this->Pharmacy->pharmacies_factory();
        }
        //print_r(Prescription::prescriptions_factory($id));

        ob_start();
        require_once($GLOBALS['template_dir'] . "pharmacies/" . $this->template_mod . "_list.php");
        $echoed_content = ob_get_clean(); // gets content, discards buffer
        return $echoed_content;
    }

    function edit_action_process() {
        if ($_POST['process'] != "true")
            return;
        //print_r($_POST);
        if (is_numeric($_POST['id'])) {
            $this->pharmacies[0] = new Pharmacy($_POST['id']);
        } else {
            $this->pharmacies[0] = new Pharmacy();
        }
        parent::populate_object($this->pharmacies[0]);
        //print_r($this->pharmacies[0]);
        //echo $this->pharmacies[0]->toString(true);
        $this->pharmacies[0]->persist();
        //echo "action processeed";
        $_POST['process'] = "";
        header('Location:' . $GLOBALS['webroot'] . "/controller.php?" . "practice_settings&pharmacy&action=list"); //Z&H
    }

}

?>
