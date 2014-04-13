<?php

class Table extends \Nette\Object
{
	/** @var Nette\Database\Context */
	protected $db;
	/** @var Nette\Database\Table\Selection */
	protected $table;
	protected $tableName = "";
	public function __construct(\Nette\Database\Context $db)
	{
		if(!$this->tableName) throw new \Nette\Application\ApplicationException("Missing tableName in ".  get_class($this));
		$this->db = $db;
		$this->table = $db->table($this->tableName);
	}
}
