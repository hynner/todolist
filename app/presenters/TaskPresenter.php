<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Homepage presenter.
 */
class TaskPresenter extends BasePresenter
{
	/** @var string table name */
	private $table = "tasks";
	public function renderList()
	{
		$this->template->tasks = $this->db->table($this->table)
				->where("id_parent IS NULL")
				->order("priority ASC, name ASC")->fetchPairs("id_task");
	}
	public function createComponentTaskEditForm() {
		$form =  new \TaskEditForm();
		$form->onSuccess[] = $this->taskEditFormSubmitted;
		return $form;
	}
	public function taskEditFormSubmitted($form)
	{
		$values = $form->getValues();
		$this->db->table($this->table)->insert($values);
		$this->redirect("list");
	}
	

}
