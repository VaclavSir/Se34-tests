<?php

namespace App;

use Nette\Application\UI\Form;
use Nette\Database\Connection;

class GuestbookPresenter extends BasePresenter
{

	/** @var \Nette\Database\Connection */
	private $connection;

	function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	protected function startup()
	{
		parent::startup();
		$this->getSession()->start();
	}

	public function renderDefault()
	{
		$this->template->messages = $this->connection->query('SELECT * FROM `guestbook` ORDER BY `submission_time` DESC;');
	}

	protected function createComponentSubmissionForm()
	{
		$form = new Form;
		$form->addProtection();
		$form->addText('name', 'Name: ');
		$form->addText('email', 'E-mail: ')->addRule(Form::EMAIL)->setRequired();
		$form->addTextArea('message', 'Message: ')->setRequired();
		$form->addSubmit('add', 'Add');

		$connection = $this->connection;
		$form->onSuccess[] = function (Form $form) use ($connection)
		{
			$formValues = $form->getValues();
			$connection->query('INSERT INTO `guestbook`', array(
				'name' => $formValues->name,
				'email' => $formValues->email,
				'message' => $formValues->message,
				'submission_time' => new \DateTime,
				'ip_address' => $form->presenter->getHttpRequest()->getRemoteAddress(),
			));
			$form->presenter->flashMessage('Message was saved.');
			$form->presenter->redirect('this');
		};
		return $form;
	}

}
