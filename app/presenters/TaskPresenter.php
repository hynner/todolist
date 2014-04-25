<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Homepage presenter.
 */
class TaskPresenter extends BasePresenter
{
	/** @var \Tasks */
	private $tasks = null;
	/** @var \Tags */
	private $tags = null;
	/** @var \Nette\Http\Session\Section */
	private $session;
	private $sessionSection = "TaskPresenterSession";
	private $priorities = array("0" => "normal", "-1" => "lowest", "1" => "highest");
	private $colors = array("#FFFFFF" => "#FFFFFF","#FF0000" => "#FF0000",
		"#00FF00" => "#00FF00", "#0000FF" => "#0000FF");
	public function injectTags(\Tags $tags)
	{
		if($this->tags)
		{
			throw new \Nette\InvalidStateException("Tags has already been set!");
		}
		$this->tags = $tags;
	}
	public function injectTasks(\Tasks $tasks)
	{
		if($this->tasks)
		{
			throw new \Nette\InvalidStateException("Tasks has already been set!");
		}
		$this->tasks = $tasks;
	}
	public function injectSession(\Nette\Http\Session $session)
	{
		if($this->session)
		{
			throw new \Nette\InvalidStateException("Session has already been set!");
		}
		$this->session = $session->getSection($this->sessionSection);
		if($this->session->filter === NULL) $this->session->filter = array();
	}
	public function actionList()
	{
		$this->template->tasks = $this->tasks->getTaskList($this->session->filter);
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
		$tags = $this->tags->getAvailableTags();
		$form =  new \TaskEditForm($this->priorities,$this->colors,$tags);
		$form->onSuccess[] = $this->taskEditFormSubmitted;
		return $form;
	}
	public function createComponentTaskFilterForm()
	{
		$tags = $this->tags->getAvailableTags();
		$form =  new \TaskFilterForm($this->priorities, $this->colors, $tags);
		$form->setDefaults($this->session->filter);
		$form->onSuccess[] = $this->taskFilterFormSubmitted;
		return $form;
	}
	public function taskFilterFormSubmitted(\Nette\Forms\Form $form)
	{
		$this->session->filter = $form->getValues();
		$this->redirect("this");
		
	}
	public function actionFinish($id, $param)
	{
		$ret = $this->tasks->setFinished($id, $param);
		if($ret == 0) $this->flashMessage ("Task update failed!", "error");
		else $this->flashMessage ("Task updated!", "success");
		$this->redirect("list");
	}
	public function taskEditFormSubmitted(\Nette\Forms\Form $form)
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
