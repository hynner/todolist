<?php

class TaskFilterForm  extends \BaseForm
{
	public function __construct($priorities=array(),$colors=array(),$availableTags = array(), \Nette\ComponentModel\IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		$this->addTagBox("tags", "Tags", array(), $availableTags)
				->setAvailableOnly(true);
		$this->addSelect("priority", "Priority", $priorities)
				->setPrompt("--- all ---");
		$this->addColorSelect("color", "Color", $colors)
				->setPrompt("--- all ---");
		$this->addSelect("finished", "Status:", array("0" => "unfinished", "1" => "finished"))
				->setPrompt("--- all ---");
		$this->addSubmit("filter");
		$this->addSubmit("cancel", "Cancel filter")
			->setValidationScope(FALSE);
	}
	public function getValues($asArray = FALSE)
	{
		$ret = parent::getValues($asArray);
		$s = $this->isSubmitted();
		if($s !== FALSE && $s->name === "cancel")
		{
			return ($asArray) ? array() : new \Nette\ArrayHash();
		}
		return $ret;
	}
}
