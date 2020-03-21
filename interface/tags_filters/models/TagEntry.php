<?php

use Framework\DataTable\Column;
use Framework\DataTable\ColumnBehavior\ActiveStatic;
use Framework\DataTable\ColumnBehavior\ActiveListbox;
use Framework\DataTable\ColumnBehavior\DetailView;
use Framework\DataTable\ColumnBehavior\ColorElement;
use Framework\ListOptions;

class TagEntry extends Entry
{
    public function init()
    {
        $repo = new TagRepository();
        $this->setColumns(array(
            new Column(array('width' => '120px', 'behavior' => new ActiveStatic(array('class' => 'created_at', 'name' => 'created_at')), 'title' => 'Created At', 'data' => 'T.created_at')),
            new Column(array('width' => '120px', 'behavior' => new ActiveStatic(array('name' => 'tag_name', 'class' => 'tag_name' )), 'title' => 'Tag Name', 'data' => 'T.tag_name')),
            new Column( array( 'width => 120px', 'behavior' => new ActiveListbox( array( 'map' => $repo->getColorMap(), 'name' => 'tag_color' ) ), 'title' => 'Tag Color', 'data' => 'T.tag_color' ) ),
            new Column(array('width' => '80px', 'behavior' => new ActiveStatic(array('class' => 'update-column', 'name' => 'last_updated', 'attributes' => array('id', 'updated_at'))), 'title' => 'Last Updated', 'data' => "CONCAT(T.updated_at,' by ',T.updated_by) AS last_updated" )),
        ));
    }

    public function getStatement()
    {
        $statement = "SELECT
            T.id,
            T.created_at,
            T.created_by,
            T.tag_name,
            T.tag_color,
            CONCAT(T.updated_at,' by ',T.updated_by) AS last_updated
            FROM tf_tags T";
        return $statement;
    }
}