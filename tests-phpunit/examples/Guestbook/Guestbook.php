<?php

namespace Tests\Guestbook;

use Se34\PageObject;
use Se34\Element;

/**
 * @property-read Element $name name=name, input, [type=text] # Name input field
 * @property-read Element $email name=email, input, [type=text] # E-mail input field
 * @property-read Element $message name=message, textarea # Message input field
 * @property-read Element $addButton name=add, input, [type=submit] # Add button
 * @method Guestbook clickAddButton()
 */
class Guestbook extends PageObject
{

	protected $presenterName = 'Guestbook';

}
