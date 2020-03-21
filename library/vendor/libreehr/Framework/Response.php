<?php

namespace Framework;

class Response extends AbstractModel
{
    public $status = null;
    public $message = null;

    public function __construct( $status, $message )
    {
        $this->status = $status;
        $this->message = $message;
    }
}
