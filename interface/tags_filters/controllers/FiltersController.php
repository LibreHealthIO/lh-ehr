<?php
use Framework\AbstractController;
use Framework\DataTable\DataTable;

require_once(__DIR__."/../../../library/pid.inc");
require_once(__DIR__."/../../../library/formatting.inc.php");
require_once(__DIR__."/../../../library/patient.inc");

class FiltersController extends AbstractController
{
    public function getTitle()
    {
        return xl("Filters");
    }

    public function getEntry()
    {
        return new FilterEntry();
    }

    public function buildDataTable()
    {
        $entry = $this->getEntry();
        $dataTable = new DataTable(
            $entry,
            'filters-table',
            $this->getBaseUrl()."/index.php?action=filters!results",
            null,
            $this->getBaseUrl()."/index.php?action=filters!setpid" );
        return $dataTable;
    }

    public function _action_patient_search()
    {
        $query = $this->request->getParam( 'query' );
        if ( strpos( $query, ',' ) !== false ) {
            $parts = explode( ',', $query );
            $fname = trim( $parts[1] );
            $lname = trim( $parts[0] );
            $statement = "SELECT PD.*, ( SELECT left(FE.date,10) FROM form_encounter FE WHERE PD.pid = FE.pid ORDER BY FE.date DESC LIMIT 1 ) AS last_encounter FROM patient_data PD WHERE PD.lname = ? AND PD.fname LIKE ?";
            $result = sqlStatement( $statement, array( $lname, "$fname%" ) );
        } else {
            $lname = trim( $query );
            $statement = "SELECT PD.*, ( SELECT left(FE.date,10) FROM form_encounter FE WHERE PD.pid = FE.pid ORDER BY FE.date DESC LIMIT 1 ) AS last_encounter FROM patient_data PD WHERE PD.lname LIKE ?";
            $result = sqlStatement( $statement, array( "$lname%" ) );
        }

        $patients = array();
        while ( $row = sqlFetchArray( $result ) ) {
            $patients []= array(
                'id' => $row['pid'],
                'name' => $row['lname'].", ".$row['fname'],
                'DOB' => $row['DOB'],
                'sex' => $row['sex'],
                'pid' => $row['pid'],
                'lastEncounter' => $row['last_encounter'],
                'displayKey' => $row['lname'].", ".$row['fname']." (".$row['pid']." ".$row['DOB'].") "
            );
        }

        echo json_encode( $patients );
        exit;
    }

    public function getProviders()
    {
        $providers = array();
        $select = sqlStatement("SELECT id, username, fname, lname, specialty FROM users " .
            "WHERE active = 1 AND ( info IS NULL OR info NOT LIKE '%Inactive%' ) " .
            "AND authorized = 1 " .
            "ORDER BY fname, lname");
        while ( $Row = sqlFetchArray( $select ) ) {
            $provider = new stdClass();
            $provider->username = $Row['username'];
            $provider->id = $Row['id'];
            $provider->name = $Row["fname"] . ' ' . $Row["lname"];
            $providers[]= $provider;
        }

        return $providers;
    }

    public function _action_index()
    {
        $this->view->dataTable = $this->buildDataTable();
        $this->view->title = $this->getTitle();
        $this->view->tags = TagRepository::fetchAll();
        $this->view->users = $this->getProviders();
        $this->view->groups = acl_get_group_title_list();
        $this->view->navbar = __DIR__."/../views/navbars/filters.php";
        $this->view->modal = __DIR__."/../views/modals/create_filter_modal.php";
        $this->setViewScript( 'list.php', 'layouts/filters_layout.php' );
    }

    public function _action_results()
    {
        $dataTable = $this->buildDataTable();
        echo $dataTable->getResults( $this->getRequest() );
    }

    public function _action_create_filter()
    {
        $repo = new FilterRepository();
        $repo->create( $this->request->getParams() );
        exit;
    }

    public function _action_delete_filter()
    {
        $filterId = $this->request->getParam( 'id' );
        $repo = new FilterRepository();
        $repo->delete( $filterId );
        exit;
    }

    public function _action_details()
    {
        $filterId = $this->request->getParam('id');
        $this->view->filterId = $filterId;
        $pid = $this->request->getParam('pid');
        $this->view->encounter = $encounterId;
        $this->view->pid = $pid;
        $this->setViewScript('details/filters.php');
    }
}