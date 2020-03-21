<?php

use Framework\DataTable\AbstractSql;
use Framework\DataTable\RowClassFilterIF;

abstract class Entry extends AbstractSql
{
    public function __construct()
    {
        $this->init();
    }

    public abstract function init();
}