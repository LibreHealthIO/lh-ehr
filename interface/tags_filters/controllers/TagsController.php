<?php
use Framework\AbstractController;
use Framework\DataTable\DataTable;
use Framework\ListOptions;

require_once(__DIR__."/../../../library/pid.inc");
require_once(__DIR__."/../../../library/formatting.inc.php");
require_once(__DIR__."/../../../library/patient.inc");

class TagsController extends AbstractController
{
    public function getTitle()
    {
        return xl("Tags");
    }

    public function getEntry()
    {
        return new TagEntry();
    }

    public function buildDataTable()
    {
        $entry = $this->getEntry();
        $dataTable = new DataTable(
            $entry,
            'tags-table',
            $this->getBaseUrl()."/index.php?action=tags!results",
            null,
            $this->getBaseUrl()."/index.php?action=tags!setpid" );
        return $dataTable;
    }

    public function _action_create_tag()
    {
        // Created a tag
        $repo = new TagRepository();
        $repo->create( $this->request->getParams() );
        exit;

        // Create it's associated ARO

    }

    public function _action_index()
    {
        $repo = new TagRepository();
        $this->view->dataTable = $this->buildDataTable();
        $this->view->title = $this->getTitle();
        $this->view->navbar = __DIR__."/../views/navbars/tags.php";
        $this->view->modal = __DIR__."/../views/modals/create_tag.php";
        $this->view->colorOptions = $repo->getColorOptions();
        $this->setViewScript( 'list.php', 'layouts/tags_layout.php' );
    }

    public function _action_results()
    {
        $dataTable = $this->buildDataTable();
        echo $dataTable->getResults( $this->getRequest() );
    }

    public function _action_details()
    {
        $encounterId = $this->request->getParam('id');
        $pid = $this->request->getParam('pid');
        $this->view->encounter = $encounterId;
        $this->view->pid = $pid;
        $this->setViewScript('details.php');
    }

    public function _action_tag_colors()
    {
        $repo = new TagRepository();
        return $repo->getColorOptionsJson();
    }

    public function _action_tags_json()
    {
        $repo = new TagRepository();
        echo json_encode( $repo->fetchAll() );
        exit;
    }
}