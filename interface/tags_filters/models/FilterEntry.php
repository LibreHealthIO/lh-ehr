<?php

use Framework\DataTable\Column;
use Framework\DataTable\ColumnBehavior\ActiveStatic;
use Framework\DataTable\ColumnBehavior\DetailView;

require 'ColumnObjectEntity.php';

class FilterEntry extends Entry
{
    public function init()
    {
        $this->setColumns(array(
            new Column(array('width' => '65px', 'behavior' => new ActiveStatic(array('class' => 'created_at', 'name' => 'created_at')), 'title' => 'Created At', 'data' => 'F.created_at')),
            new Column(array('width' => '65px', 'behavior' => new ActiveStatic(array('class' => 'created_by', 'name' => 'created_by')), 'title' => 'Created By', 'data' => 'F.created_by')),
            new Column(array('width' => '65px', 'behavior' => new ActiveStatic(array('class' => 'requesting_action', 'name' => 'requesting_action')), 'title' => 'Action', 'data' => 'F.requesting_action')),
            new Column(array('width' => '65px', 'behavior' => new ActiveStatic(array('class' => 'requesting_type', 'name' => 'requesting_type')), 'title' => 'Requesting Type', 'data' => 'F.requesting_type')),
            new Column(array('width' => '65px', 'behavior' => new ActiveStatic(array('class' => 'requesting_entity', 'name' => 'requesting_entity')), 'title' => 'Requesting Entity', 'data' => 'F.requesting_entity')),
            new Column(array('width' => '65px', 'behavior' => new ActiveStatic(array('class' => 'object_type', 'name' => 'object_type')), 'title' => 'Object Type', 'data' => 'F.object_type')),
            new Column(array('width' => '65px', 'behavior' => new ColumnObjectEntity(array('class' => 'object_entity', 'name' => 'object_entity')), 'title' => 'Object Entity', 'data' => 'F.object_entity')),
            new Column(array('width' => '65px', 'behavior' => new ActiveStatic(array('class' => 'effective_datetime', 'name' => 'effective_datetime')), 'title' => 'Effective Date', 'data' => 'F.effective_datetime')),
            new Column(array('width' => '65px', 'behavior' => new ActiveStatic(array('class' => 'expiration_datetime', 'name' => 'expiration_datetime')), 'title' => 'Expiration Date', 'data' => 'F.expiration_datetime')),
//            new Column(array('width' => '65px', 'behavior' => new ActiveStatic(array('class' => 'priority', 'name' => 'priority')), 'title' => 'Priority', 'data' => 'F.priority')),
            new Column(array('width' => '65px', 'behavior' => new ActiveStatic(array('class' => 'note', 'name' => 'note')), 'title' => 'Note', 'data' => 'F.note')),
            new Column(array('width' => '70px', 'behavior' => new DetailView($GLOBALS['webroot'] . '/interface/tags_filters/index.php?action=filters!details', array( 'id' ) ), 'class' => 'details-control', 'searchable' => false, 'orderable' => false, 'title' => 'Details', 'data' => '', 'defaultContent' => '')),
            new Column(array('width' => '70px', 'behavior' => new ActiveStatic(array('class' => 'update-column', 'name' => 'last_updated', 'attributes' => array('id', 'updated_at'))), 'title' => 'Last Updated', 'data' => "CONCAT(F.updated_at,' by ',F.updated_by) AS last_updated" )),
        ));
    }

    public function getStatement()
    {
        $statement = "SELECT
            F.id,
            F.created_at,
            F.created_by,
            F.requesting_action,
            F.requesting_type,
            F.requesting_type AS requesting_type_2,
            F.requesting_entity,
            F.object_type,
            F.object_entity,
            F.effective_datetime,
            F.expiration_datetime,
            F.priority,
            F.note,
            CONCAT(F.updated_at,' by ',F.updated_by) AS last_updated
            FROM tf_filters F WHERE deleted = 0";
        return $statement;
    }
}