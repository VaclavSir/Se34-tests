<?php

namespace Tests\Guestbook;

use Tests\FakeRootTestCase;

class WithComponents_Test extends FakeRootTestCase
{

	public function testSubmittingFormAddsTheMessage()
	{
		$guestbook = new GuestbookWithComponents($this->session);
		$guestbook->navigate();
		$guestbook->form->fill(array(
			'name' => 'Someone',
			'email' => 'nevim@example.org',
			'message' => 'Hello World',
		));
		$guestbook->form->clickAddButton();

		$this->assertCount(1, $guestbook->messages);
		$this->assertSame('Someone', $guestbook->messages[0]->name);
		$this->assertSame('nevim@example.org', $guestbook->messages[0]->email);
		$this->assertSame('Hello World', $guestbook->messages[0]->message);
	}

}
