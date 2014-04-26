<?php
/**
 * Select colors
 *
 * @author hynner
 */
use Nette\Forms\Controls\SelectBox;
class ColorSelectBox extends SelectBox {
	public function setItems(array $items, $useKeys = TRUE)
	{
		$col = array();
		foreach($items as $c)
		{
			$col[$c] = \Nette\Utils\Html::el("option")->add($c);
			$tmp = 	\Nette\Utils\Html::el("div")
					->addAttributes(array("class" => "task-color", "style" => "background-color: $c;"));
			$col[$c]->addAttributes(array("data-content" => (string) $tmp." $c"));
		}
		parent::setItems($col);
		return $this;
	}
}
