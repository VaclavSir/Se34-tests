<?php

namespace Tests\Guestbook;

use Se34\ElementComponent;
use Se34\PageObject;
use Se34\Element;

/**
 * @presenterName Guestbook
 *
 * @property-read GuestbookForm $form
 * @property-read GuestbookMessage[] $messages
 *
 */
class GuestbookWithComponents extends PageObject
{

	protected $presenterName = 'Guestbook';

	private $form;
	private $messages;

	public function getForm()
	{
		if (!$this->form)
		{
			$this->form = new GuestbookForm($this->session, $this, array('tag name' => 'form', 'form'));
		}
		return $this->form;
	}

	public function getMessages()
	{
		if (!$this->messages)
		{
			$this->messages = array();
			foreach ($this->findElements('css selector', 'div.guestbook-message') as $messageElement)
			{
				$this->messages[] = new GuestbookMessage($this->session, $this, array('element' => $messageElement));
			}
		}
		return $this->messages;
	}

}

/**
 * @property-read Element $name name=name, input, [type=text] # Name input field
 * @property-read Element $email name=email, input, [type=text] # E-mail input field
 * @property-read Element $message name=message, textarea # Message input field
 * @property-read Element $addButton name=add, input, [type=submit] # Add button
 * @method GuestbookWithComponents clickAddButton()
 */
class GuestbookForm extends ElementComponent
{

}

/**
 * @property-read Element $authorAnchor xpath = '//a[starts-with(@href, "mailto:")]'
 * @property-read Element $messageParagraph css selector = p.content
 * @property-read string $name
 * @property-read string $email
 * @property-read string $message
 */
class GuestbookMessage extends ElementComponent
{

	public function getName()
	{
		return $this->authorAnchor->text();
	}

	public function getEmail()
	{
		return substr($this->authorAnchor->attribute('href'), strlen('mailto:'));
	}

	public function getMessage()
	{
		return $this->messageParagraph->text();
	}

}
