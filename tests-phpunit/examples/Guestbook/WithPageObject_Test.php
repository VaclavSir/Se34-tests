<?php

namespace Tests\Guestbook;

use Nette\Database\Connection;
use Tests\FakeRootTestCase;

class WithPageObject_Test extends FakeRootTestCase
{

	/**
	 * This example fills in the guestbook form, submits and then checks that
	 * the record was saved into the database.
	 */
	public function testSubmittingFormAddsMessageToDatabase()
	{
		$guestbook = new Guestbook($this->session);
		$guestbook->navigate();
		$guestbook->fill(array(
			'name' => 'Someone',
			'email' => 'nevim@example.org',
			'message' => 'Hello World',
		));
		$guestbook->clickAddButton();

		$messages = $this->getDatabase()->query('SELECT * FROM `guestbook`')->fetchAll();
		$this->assertCount(1, $messages);
		$this->assertSame('Someone', $messages[0]->name);
		$this->assertSame('nevim@example.org', $messages[0]->email);
		$this->assertSame('Hello World', $messages[0]->message);
	}

	/**
	 * This example shows how to work with javascript alerts.
	 */
	public function testMessageAndEmailAreRequired()
	{
		$guestbook = new Guestbook($this->session);
		$guestbook->navigate();
		$guestbook->fillName('Someone');

		// Here you can't use $guestbook->clickAddButton(), because this click
		// results in JS alert, and that disables some things clickAddButton()
		// does.
		$guestbook->addButton->click();

		// BrowserSession::waitForAlert() waits until the alert pops up and then
		// it returns its text. The same method works with JS alert/confirm/prompt.
		$this->assertSame('Please enter a valid email address.', $this->session->waitForAlert());

		// After the alert was caught, it has to be confirmed or cancelled.
		//
		// - $this->session->acceptAlert() - click "Ok".
		// - $this->session->dismissAlert() - click "Cancel" (confirm/prompt).
		// - $this->session->alertText('some text') - fill text into prompt.
		$this->session->acceptAlert();

		$guestbook->fillEmail('valid@email.address');
		$guestbook->addButton->click();
		$this->assertSame('Please complete mandatory field.', $this->session->waitForAlert());
		$this->session->acceptAlert();

		$messages = $this->getDatabase()->query('SELECT * FROM `guestbook`')->fetchAll();
		$this->assertCount(0, $messages);
	}

}
