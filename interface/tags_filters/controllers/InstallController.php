<?php
use Framework\AbstractController;
use Framework\DataTable\DataTable;

require_once(__DIR__."/../../../library/pid.inc");
require_once(__DIR__."/../../../library/formatting.inc.php");
require_once(__DIR__."/../../../library/patient.inc");

class InstallController extends AbstractController
{
    public function _action_index()
    {
        $this->setViewScript( 'install.php' );
    }
}