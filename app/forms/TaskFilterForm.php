<?php

class TaskFilterForm  extends \BaseForm
{
	public function __construct($availableTags, \Nette\ComponentModel\IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		$this->addTagBox("tags", "Tags", array(), $availableTags)
				->setAvailableOnly(true);
		$this->addSelect("priority", "Priority", array("-1" => "lowest", "0" => "normal", "1" => "highest"));
		$this->addSelect("color", "Color", array("#FFFFFF","#FF0000", "#00FF00", "#0000FF"));
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
