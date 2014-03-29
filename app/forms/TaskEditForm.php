<?php
use \Nette\Forms\Form;
class TaskEditForm extends BaseForm
{
	public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		$this->addText("name", "Task name")
				->addRule(Form::FILLED, "Task name must be filled!");
		$this->addSelect("priority", "Priority", array("1" => "normal", "-1" => "lowest", "999" => "highest"));
		$this->addTextArea("notes", "Notes");
		$this->addSubmit("submit", "Save");
	}
}