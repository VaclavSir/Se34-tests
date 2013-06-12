<?php

namespace App;

use Nette,
	Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	/** @var ITemplateFactory */
	private $templateFactory;

	public function injectTemplateFactory(ITemplateFactory $templateFactory)
	{
		$this->templateFactory = $templateFactory;
	}

	protected function createTemplate($class = NULL)
	{
		return $this->templateFactory->createTemplate($this, $class);
	}

}
