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
	/** 
	 * @inject 
	 * @var \Tasks 
	 */
	public $tasks;
	
	public function actionList()
	{
		$this->template->tasks = $this->db->table($this->table)
				->where("id_parent IS NULL")
				->order("priority ASC, name ASC")->fetchPairs("id_task");
	}
	public function actionEdit($id)
	{
		$this->template->task = $this->tasks->get($id);
		if($this->template->task === false)
		{
			$this->flashMessage("Requested task doesn't exists!", "error");
			$this->redirect("list");
		}
	}
	public function actionDelete($id)
	{
		$succ = $this->tasks->delete($id);
		if($succ)
		{
			$this->flashMessage("Task #$id deleted!", "success");
		}
		else
		{
			$this->flashMessage("Error while trying to delete task #$id!", "error");
		}
		$this->redirect("list");
	}
	public function createComponentTaskEditForm() {
		$tags = $this->db->table("tags")->fetchPairs("id_tag", "name");
		$form =  new \TaskEditForm($tags);
		$form->onSuccess[] = $this->taskEditFormSubmitted;
		return $form;
	}
	public function taskEditFormSubmitted($form)
	{
		$values = $form->getValues();
		$succ = $this->tasks->save($values);
		if($succ)
		{
			$this->flashMessage("Task saved!", "success");
		}
		else
		{
			$this->flashMessage("Error while saving task!", "error");
		}
		$this->redirect("list");
	}
	

}
