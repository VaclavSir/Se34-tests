<?php

namespace App;

use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;
use Nette\Object;
use Nette\Templating\FileTemplate;
use Nette\Templating\ITemplate;

interface ITemplateFactory
{

	public function createTemplate(Control $control, $class = NULL);

}

class TemplateFactory extends Object implements ITemplateFactory
{

	/**
	 * Creates and sets up a template object.
	 *
	 * Sets up template variables `$control` and `$presenter`, registers event
	 * onPrepareFilters (callback to `$control->templatePrepareFilters()`),
	 * attaches helpers loader.
	 *
	 * If the control has or is a presenter, sets up these variables:
	 *
	 * - `$user`
	 * - `$netteHttpResponse`
	 * - `$netteCacheStorage`
	 * - `$baseUri`, `$baseUrl`
	 * - `$basePath`
	 * - `$flashes`
	 *
	 * @param Control $control
	 * @param string $class
	 * @return ITemplate
	 */
	public function createTemplate(Control $control, $class = NULL)
	{
		$template = $class ? new $class : new FileTemplate;
		$presenter = $control->getPresenter(FALSE);
		$template->onPrepareFilters[] = $control->templatePrepareFilters;
		$template->registerHelperLoader('Nette\Templating\Helpers::loader');

		// default parameters
		$template->control = $template->_control = $control;
		$template->presenter = $template->_presenter = $presenter;
		if ($presenter instanceof Presenter) {
			$httpResponse = $presenter->getContext()->getByType('Nette\Http\IResponse');
			$httpRequest = $presenter->getContext()->getByType('Nette\Http\IRequest');
			$template->setCacheStorage($presenter->getContext()->getService('nette.templateCacheStorage'));
			$template->user = $presenter->getUser();
			$template->netteHttpResponse = $httpResponse;
			$template->netteCacheStorage = $presenter->getContext()->getByType('Nette\Caching\IStorage');
			$template->baseUri = $template->baseUrl = rtrim($httpRequest->getUrl()->getBaseUrl(), '/');
			$template->basePath = preg_replace('#https?://[^/]+#A', '', $template->baseUrl);

			// flash message
			if ($presenter->hasFlashSession()) {
				$id = $control->getParameterId('flash');
				$template->flashes = $presenter->getFlashSession()->$id;
			}
		}
		if (!isset($template->flashes) || !is_array($template->flashes)) {
			$template->flashes = array();
		}

		return $template;
	}

}
