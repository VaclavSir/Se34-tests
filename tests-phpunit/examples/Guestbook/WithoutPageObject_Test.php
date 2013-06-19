<?php

namespace Tests\Guestbook;

use Tests\FakeRootTestCase;

/**
 * This test shows how you can test an application without use of Page Objects.
 */
class WithoutPageObject_Test extends FakeRootTestCase
{

	public function testSubmittingFormAddsMessageToDatabase()
	{
		$this->session->navigate('Guestbook');
		$this->session->byName('name')->value('Someone');
		$this->session->byName('email')->value('nevim@example.org');
		$this->session->byName('message')->value('Hello World');
		$this->session->byName('add')->click();

		$messages = $this->getDatabase()->query('SELECT * FROM `guestbook`')->fetchAll();
		$this->assertCount(1, $messages);
		$this->assertSame('Someone', $messages[0]->name);
		$this->assertSame('nevim@example.org', $messages[0]->email);
		$this->assertSame('Hello World', $messages[0]->message);
	}

}
