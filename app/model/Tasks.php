<?php

class Tasks extends Table
{	
	protected $tableName = "tasks";
	/** @var \Tags */
	private $tags;
	public function injectTags(\Tags $tags)
	{
		if($this->tags)
		{
			throw new \Nette\InvalidStateException("Tags has already been set!");
		}
		$this->tags = $tags;
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
			$this->tags->insertTags($tags);
			$this->db->queryArgs("insert ignore into tasks_tags (id_task, id_tag) select ? as id_task, tags.id_tag from tags where name in (?)", array($values["id_task"],$tags));
		}
		return true;
	}
	/**
	 * 
	 * @param int $id
	 * @param bool $f finished
	 * @return int
	 */
	public function setFinished($id, $f)
	{
		return $this->table->where("id_task", $id)->update(array("finished" => (bool) $f));
	}
	public function get($id)
	{
		$task = $this->table->where("id_task", $id)->fetch();
		if($task === false) return false;
		// get tags
		$task = $task->toArray();
		$task["tags"] = $this->tags->getTagsForTask($task["id_task"]);
		return $task;
	}
	public function delete($id)
	{
		$succ = $this->table->where("id_task", $id)->delete() === 1;
		// delete task_tags associations
		$this->db->table("tasks_tags")->where("id_task", $id)->delete();
		return $succ;
	}
	public function getTaskList($filter = array())
	{
		$params = array();
		$where = "";
		if(!empty($filter["tags"]))
		{
			$params[] = $filter["tags"];
			$where .= " AND tags.name IN (?) ";
		}
		if(isset($filter["priority"]))
		{
			$params[] = $filter["priority"];
			$where .= " AND tasks.priority = ?";
		}
		if(isset($filter["color"]))
		{
			$params[] = $filter["color"];
			$where .= " AND tasks.color = ?";
		}
		if(isset($filter["finished"]))
		{
			$params[] = $filter["finished"];
			$where .= " AND tasks.finished = ? ";
		}
		return $this->db
				->queryArgs("select tasks.*, group_concat(tags.name) AS tag_list from tasks left join tasks_tags on tasks.id_task = tasks_tags.id_task "
				. " left join tags on tasks_tags.id_tag = tags.id_tag where 1 "
				. $where
				. " GROUP BY tasks.id_task"
				. "  ORDER BY priority ASC, name ASC", $params)
				->fetchPairs("id_task");
		
	}
}