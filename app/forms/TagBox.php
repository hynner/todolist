<?php
use \Nette\Forms\Form,
		\Nette\Utils\Html;

class TagBox extends Nette\Forms\Controls\BaseControl
{
	/** @var Array */
	private $tags = array();
	/** @var string */
	private $className = "tagbox";
	/** @var Array */
	private $availableTags = array();
	/** @var bool Filter out non-available tags? */
	private $availableOnly = false;
	public function __construct($caption = NULL)
	{
		parent::__construct($caption);
	}
	public function setValue($value)
	{
		if(is_array($value))
		{
			$this->tags = array_unique($value);
			if($this->availableOnly)
			{
				$this->tags = array_intersect($this->tags, $this->availableTags);
			}
		}
		else
		{
			$this->tags = array();
		}
		return $this;
	}
	public function getValue()
	{
		return $this->tags;
	}
	public function getAvailableTags()
	{
		return $this->availableTags;
	}
	public function setAvailableTags($tags)
	{
		if(is_array($tags))
		{
			$this->availableTags = $tags;
		}
		else
		{
			$this->availableTags = array();
		}
		return $this;
	}
	public function setAvailableOnly($b)
	{
		$this->availableOnly = (bool)$b;
		return $this;
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
		$availTags = Html::el("span")->id($this->getHtmlId()."-avail");
		foreach($this->availableTags as $t)
		{
			$availTags->add(Html::el("span")->setText($t)->class("hidden"));
		}
		$control = Html::el()->add($ul)->add($availTags);
		return $control;
		
	}
}
