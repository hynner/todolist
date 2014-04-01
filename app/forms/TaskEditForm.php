<?php
use \Nette\Forms\Form;
class TaskEditForm extends BaseForm
{
	public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		$this->addHidden("id_task");
		$this->addText("name", "Task name")
				->addRule(Form::FILLED, "Task name must be filled!");
		$this->addSelect("priority", "Priority", array("-1" => "lowest", "0" => "normal", "1" => "highest"));
		$this->addSelect("color", "Color", array("#FFFFFF","#FF0000", "#00FF00", "#0000FF"));
		$this->addTextArea("tags", "Tags");
		$this->addTextArea("notes", "Notes");
		$this->addSubmit("submit", "Save");
		$this->setDefaults(array("priority" => "0"));
	}
	public function render($task = null)
	{
		if($task !== null)
			$this->setDefaults($task, true);
		parent::render();
	}
}