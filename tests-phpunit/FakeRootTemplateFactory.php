<?php

namespace Tests;

use App\ITemplateFactory;
use Nette\Application\UI\Control;
use Nette\Object;

class FakeRootTemplateFactory extends Object implements ITemplateFactory
{

	/** @var \App\ITemplateFactory */
	private $originalFactory;

	function __construct(ITemplateFactory $originalFactory)
	{
		$this->originalFactory = $originalFactory;
	}

	public function createTemplate(Control $control, $class = NULL)
	{
		$template = $this->originalFactory->createTemplate($control, $class);
		$template->baseUrl = $template->baseUrl . '/../www';
		$template->baseUri = $template->baseUri . '/../www';
		$template->basePath = $template->basePath . '/../www';
		return $template;
	}

}
