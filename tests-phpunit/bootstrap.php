<?php

require __DIR__ . '/../libs/autoload.php';

$configurator = new Nette\Configurator;
$configurator->setDebugMode(TRUE);
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../app')
	->addDirectory(__DIR__ . '/../libs')
	->addDirectory(__DIR__)
	->register();
