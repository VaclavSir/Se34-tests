<?php

namespace Tests;

use Nette\Configurator;
use Nette\Database\Connection;
use Nette\Database\Helpers;
use Se34;

class FakeRootTestCase extends Se34\TestCase
{

	/** @var Connection */
	private $connection;

	/** @var string */
	private $dbName;

	/** @inheritdoc */
	protected function createContext()
	{
		$configurator = new Configurator();
		$configurator->setTempDirectory(__DIR__ . '/temp');
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/config.local.neon');
		$configurator->addConfig(__DIR__ . '/tests.neon');
		$configurator->addConfig(__DIR__ . '/tests.local-fake.neon');
		$configurator->addParameters(array('container' => array('class' => 'SystemContainer_fake')));
		$systemContainer = $configurator->createContainer();
		$this->connection = $systemContainer->getByType(Connection::getReflection()->name);
		$this->createAndUseTestDatabase();
		$systemContainer->parameters['selenium']['baseUrl'] .= '?testDbName=' . $this->dbName;
		return $systemContainer;
	}

	protected function tearDown()
	{
		parent::tearDown();
		if ($this->dbName !== NULL)
		{
			$this->connection->query('DROP DATABASE ' . $this->connection->supplementalDriver->delimite($this->dbName));
		}
	}

	private function createAndUseTestDatabase()
	{
		for ($i = 1; ; $i++)
		{
			$dbName = 'Se34_' . $i;
			try
			{
				$this->connection->query('CREATE DATABASE ' . $this->connection->supplementalDriver->delimite($dbName) . ' COLLATE=utf8_czech_ci');
				$this->connection->query('USE ' . $this->connection->supplementalDriver->delimite($dbName));
				Helpers::loadFromFile($this->connection, __DIR__ . '/../INSTALL.sql');
				$this->dbName = $dbName;
				return;
			}
			catch (\PDOException $e)
			{
				if (strpos($e->getMessage(), 'database exists') === FALSE)
				{
					throw $e;
				}
			}
		}
	}

	/** @return Connection */
	protected function getDatabase()
	{
		return $this->context->getByType(Connection::getReflection()->name);
	}

}
