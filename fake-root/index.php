<?php

require __DIR__ . '/../libs/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode(TRUE);
$configurator->enableDebugger();
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../app')
	->addDirectory(__DIR__ . '/../libs')
	->addDirectory(__DIR__ . '/../tests-phpunit')
	->register();

$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
$configurator->addConfig(__DIR__ . '/../tests-phpunit/tests.neon');
$configurator->addConfig(__DIR__ . '/../tests-phpunit/tests.local-fake.neon');
$container = $configurator->createContainer();

// Switch database, use the one from the "testDbName" query string parameter
$testDbName = $container->httpRequest->getQuery('testDbName');
if ($testDbName !== NULL)
{
	$db = $container->getByType(Nette\Database\Connection::getReflection()->name);
	/* @var $db \Nette\Database\Connection */
	$db->query('USE ' . $db->supplementalDriver->delimite($testDbName));
}

$container->application->run();
