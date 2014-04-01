<?php

class Tasks extends Nette\Object
{
	/** @var Nette\Database\Context */
	private $db;
	/** @var Nette\Database\Table\Selection */
	private $table;
	private $tableName = "tasks";
	public function __construct(\Nette\Database\Context $db)
	{
		$this->db = $db;
		$this->table = $db->table($this->tableName);
	}
	
	public function save($values)
	{
		$data = $values;
		unset($data["tags"]);
		if(!$values["id_task"])
		{
			$row = $this->table->insert($data);
		}
		else
		{
			$row = $this->table->where("id_task", $data["id_task"])->update($data);
		}
		// save tags
	}
	public function delete($id)
	{
		$succ = $this->table->where("id_task", $id)->delete() === 1;
		// delete tags
		return $succ;
	}
}