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
		$tags = $values["tags"];
		unset($values["tags"]);
		if(!$values["id_task"])
		{
			$succ = $this->table->insert($values);
			if($succ === FALSE || $succ === 0) return false;
			$values["id_task"] = $succ->id_task;
		}
		else
		{
			$succ = $this->table->where("id_task", $values["id_task"])->update($values);
		}
		// save tags
		if(empty($tags))
		{
			$this->db->queryArgs("delete from tasks_tags where id_task = ?", array($values["id_task"]));
		}
		else
		{
			$this->db->queryArgs("delete from tasks_tags where id_tag in (select id_tag from tags where name not in (?)) and id_task = ?", array($tags, $values["id_task"]));
			$tags_placeholder = str_pad("", (count($tags)-1)*12,"(DEFAULT,?),")."(DEFAULT,?)";
			$this->db->queryArgs("insert ignore into tags values $tags_placeholder", $tags);
			$this->db->queryArgs("insert ignore into tasks_tags (id_task, id_tag) select ? as id_task, tags.id_tag from tags where name in (?)", array($values["id_task"],$tags));
		}
		return true;
	}
	public function get($id)
	{
		$task = $this->table->where("id_task", $id)->fetch();
		if($task === false) return false;
		// get tags
		$task = $task->toArray();
		$ret = $this->db->queryArgs("select name from tags join tasks_tags on tasks_tags.id_tag = tags.id_tag where id_task = ?", array($id));
		$task["tags"] = array();
		while($n = $ret->fetchField("name"))
		{
				$task["tags"][] = $n;			
		}
		return $task;
	}
	public function delete($id)
	{
		$succ = $this->table->where("id_task", $id)->delete() === 1;
		// delete tags
		$this->db->table("tasks_tags")->where("id_task", $id)->delete();
		return $succ;
	}
}