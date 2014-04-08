<?php
use \Nette\Forms\Form,
		\Nette\Utils\Html;

class TagBox extends Nette\Forms\Controls\BaseControl
{
	/** @var Array */
	private $tags = array();
	/** @var string */
	private $className = "tagbox";
	
	
	public function __construct($caption = NULL)
	{
		parent::__construct($caption);
	}
	public function setValue($value)
	{
		if(is_array($value))
		{
			$this->tags = array_unique($value);
		}
		else
		{
			$this->tags = array();
		}
	}
	public function getValue()
	{
		return $this->tags;
	}
	public function loadHttpData()
	{
		$this->setValue($this->getHttpData(Form::DATA_TEXT, '[]'));
	}
	public function getControl()
	{
		$name = $this->getName();
		$ul = Html::el('ul')->name($name)->id($this->getHtmlId())->class($this->className);
		foreach($this->tags as $t)
		{
			$ul->add(Html::el("li")->setText($t));
		}
		$control = Html::el()->add($ul);
		return $control;
		
	}
}
