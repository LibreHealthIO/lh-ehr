<?php
namespace Framework\DataTable;

class SearchFilter
{
	const TYPE_LIKE = 0;
	const TYPE_STRICT = 1;
	const TYPE_LESS_THAN = 2;
	const TYPE_GREATER_THAN = 3;
	const TYPE_LESS_THAN_TIMESTAMP = 4;
	const TYPE_GREATER_THAN_TIMESTAMP = 5;
    const TYPE_IS_NULL = 6;
	
	protected $keyword = null;
	protected $column = null;
	protected $type = null;
	
	public function __construct( $column, $keyword, $type = self::TYPE_LIKE )
	{
	    $this->column = $column;
	    $this->keyword = $keyword;
		$this->type = $type;
	}
	
	public function getKeyword()
	{
	    return $this->keyword;
	}
	
	public function getColumn()
	{
	    return $this->column;
	}
	
	public function getType()
	{
	    return $this->type;
	}
}
