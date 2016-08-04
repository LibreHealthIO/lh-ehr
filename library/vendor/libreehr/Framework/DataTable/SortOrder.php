<?php
namespace Framework\DataTable;

class SortOrder
{
	const SORT_ASC = 'ASC';
	const SORT_DESC = 'DESC';
	
	protected $column;
	protected $direction;
	
	public function __construct( $column, $direction = SortOrder::SORT_ASC )
	{
		$this->column = $column;
		$this->direction = $direction;
	}
	
	public function getColumn()
	{
	    return $this->column;
	}
	
	public function getDirection()
	{
	    return $this->direction;
	}
}
