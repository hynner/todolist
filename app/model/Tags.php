<?php

class Tags extends Table
{
	protected $tableName = "tags";
	/**
	 * Get a list of available tags
	 * @return array id_tag => name
	 */
	public function getAvailableTags()
	{
		return $this->table->fetchPairs("id_tag", "name");
	}
	/**
	 * Inserts multiple tags, ignores those already there
	 * @param array $tags
	 */
	public function insertTags($tags)
	{
		$tags_placeholder = str_pad("", (count($tags)-1)*12,"(DEFAULT,?),")."(DEFAULT,?)";
		$this->db->queryArgs("insert ignore into tags values $tags_placeholder", $tags);
	}
	/**
	 * @param int $id
	 * @return array tags
	 */
	public function getTagsForTask($id)
	{
		$ret = $this->db->queryArgs("select name from tags join tasks_tags on tasks_tags.id_tag = tags.id_tag where id_task = ?", array($id));
		$tags = array();
		while($n = $ret->fetchField("name"))
		{
				$tags[] = $n;			
		}
		return $tags;
	}
}
