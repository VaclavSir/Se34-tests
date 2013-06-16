<?php

namespace Tests;

use Nette\Application\IRouter;
use Nette\Application\Request;
use Nette\Object;
use Nette;

class FakeRootRouter extends Object implements IRouter
{

	/** @var \Nette\Application\IRouter */
	private $originalRouter;

	function __construct(IRouter $originalRouter)
	{
		$this->originalRouter = $originalRouter;
	}

	/**
	 * Maps HTTP request to a Request object.
	 * @param \Nette\Http\IRequest $httpRequest
	 * @return Request|NULL
	 */
	function match(Nette\Http\IRequest $httpRequest)
	{
		return $this->originalRouter->match($httpRequest);
	}

	/**
	 * Constructs absolute URL from Request object.
	 *
	 * Adds the "testDbName" query string parameter.
	 *
	 * @param \Nette\Application\Request $appRequest
	 * @param \Nette\Http\Url $refUrl
	 * @return string|NULL
	 */
	function constructUrl(Request $appRequest, Nette\Http\Url $refUrl)
	{
		parse_str($refUrl->getQuery(), $query);
		$testDbName = $query['testDbName'];

		$parameters = $appRequest->parameters;
		$parameters['testDbName'] = $testDbName;
		$appRequest->parameters = $parameters;

		return $this->originalRouter->constructUrl($appRequest, $refUrl);
	}
}
