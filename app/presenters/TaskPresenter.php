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
		$form =  new \TaskEditForm($tags);
		$form->onSuccess[] = $this->taskEditFormSubmitted;
		return $form;
	}
	public function createComponentTaskFilterForm()
	{
		$tags = $this->tags->getAvailableTags();
		$form =  new \TaskFilterForm($tags);
		$form->setDefaults($this->session->filter);
		$form->onSuccess[] = $this->taskFilterFormSubmitted;
		return $form;
	}
	public function taskFilterFormSubmitted(\Nette\Forms\Form $form)
	{
		$this->session->filter = $form->getValues();
		$this->redirect("this");
		
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
