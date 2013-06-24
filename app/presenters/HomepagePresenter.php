<?php

namespace App;

use Nette,
	Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

	protected function createComponentSetFlashAndRedirectToHomepageForm()
	{
		$form = new Nette\Application\UI\Form;
		$form->addText('message', 'Message to show: ');
		$form->addSubmit('submit', 'Submit');
		$form->onSuccess[] = function (Nette\Application\UI\Form $form)
		{
			$form->getPresenter()->flashMessage($form->values->message);
			$form->getPresenter()->redirect('Homepage:default');
		};
		return $form;
	}

}
