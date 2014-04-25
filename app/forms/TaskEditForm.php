<?php
use \Nette\Forms\Form;
class TaskEditForm extends BaseForm
{
	public function __construct($priorities=array(),$colors=array(),$availableTags = array(), \Nette\ComponentModel\IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		$this->addHidden("id_task");
		$this->addText("name", "Task name")
				->addRule(Form::FILLED, "Task name must be filled!");
		$this->addSelect("priority", "Priority", $priorities);
		$this->addSelect("color", "Color", $colors);
		$this->addTagBox("tags", "Tags", array(), $availableTags);
		$this->addTextArea("notes", "Notes");
		$this->addSubmit("submit", "Save");
	}
	public function render($task = null)
	{
		if($task !== null)
			$this->setDefaults($task, true);
		parent::render();
	}
}