<?php

namespace Tests;

use Se34;
use Nette\Configurator;

/**
 * This is a base class for tests that access the real application.
 */
abstract class RealApplicationTestCase extends Se34\TestCase
{

	/** @inheritdoc */
	protected function createContext()
	{
		$configurator = new Configurator;
		$configurator->setTempDirectory(__DIR__ . '/temp');
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/config.local.neon');
		$configurator->addConfig(__DIR__ . '/tests.neon');
		$configurator->addConfig(__DIR__ . '/tests.local-real.neon');
		$configurator->addParameters(array('container' => array('class' => 'SystemContainer_real')));
		return $configurator->createContainer();
	}

}
