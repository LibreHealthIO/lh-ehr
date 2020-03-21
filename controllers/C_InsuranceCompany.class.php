<?php

require_once($GLOBALS['fileroot'] . "/library/classes/Controller.class.php");
require_once($GLOBALS['fileroot'] . "/library/classes/InsuranceCompany.class.php");
require_once($GLOBALS['fileroot'] . "/library/classes/X12Partner.class.php");

class C_InsuranceCompany extends Controller {

    var $template_mod;
    var $icompanies;
    var $form_action;
    var $style;
    var $current_action;
    var $webroot;
    var $x12_partners;
    var $insurancecompany;

    //var $icompanies;

    function __construct($template_mod = "general") {
        parent::__construct();
        $this->icompanies = array();
        $this->template_mod = $template_mod;

        $this->form_action = $GLOBALS['webroot'] . "/controller.php?" . $_SERVER['QUERY_STRING'];
        $this->current_action = $GLOBALS['webroot'] . "/controller.php?" . "practice_settings&insurance_company&";
        $this->style = $GLOBALS['style'];
        $this->webroot = $GLOBALS['webroot'];
        $this->InsuranceCompany = new InsuranceCompany();
    }

    function default_action() {
        return $this->list_action();
    }

    function edit_action($id = "", $patient_id = "", $p_obj = null) {
        if ($p_obj != null && get_class($p_obj) == "insurancecompany") {
            $this->icompanies[0] = $p_obj;
        } elseif (get_class($this->icompanies[0]) != "insurancecompany") {
            $this->icompanies[0] = new InsuranceCompany($id);
        }

        $x = new X12Partner();
        $this->x12_partners = $x->_utility_array($x->x12_partner_factory());
        $this->insurancecompany = $this->icompanies[0];

        ob_start();
        require_once($GLOBALS['template_dir'] . "insurance_companies/" . $this->template_mod . "_edit.php");
        $echoed_content = ob_get_clean(); // gets content, discards buffer
        return $echoed_content;
    }

    function list_action($sort = "") {

        if (!empty($sort)) {
            $this->icompanies = $this->InsuranceCompany->insurance_companies_factory("", $sort);
        } else {
            $this->icompanies = $this->InsuranceCompany->insurance_companies_factory();
        }
        ob_start();
        require_once($GLOBALS['template_dir'] . "insurance_companies/" . $this->template_mod . "_list.php");
        $echoed_content = ob_get_clean(); // gets content, discards buffer
        return $echoed_content;
    }

    function edit_action_process() {

        //echo "hi";
        if ($_POST['process'] != "true") {
            //echo "failed";
            return;
        }
        //print_r($_POST);
        if (is_numeric($_POST['id'])) {
            $this->icompanies[0] = new InsuranceCompany($_POST['id']);
        } else {
            $this->icompanies[0] = new InsuranceCompany();
        }

        parent::populate_object($this->icompanies[0]);

        $this->icompanies[0]->persist();
        $this->icompanies[0]->populate();

        //echo "action processeed";
        $_POST['process'] = "";
        header('Location:' . $GLOBALS['webroot'] . "/controller.php?" . "practice_settings&insurance_company&action=list"); //Z&H
    }

}

?>
