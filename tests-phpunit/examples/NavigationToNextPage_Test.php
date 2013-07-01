<?php

namespace Tests;

use Se34\Element;
use Se34\PageObject;

class NavigationToNextPage_Test extends FakeRootTestCase
{

	/**
	 * This example shows use of the return type in the method annotation.
	 *
	 * When the submit button on first page is clicked, user is redirected to
	 * the second page and so this method returns instance of SecondPage.
	 */
	public function testSubmitMethodReturnsNextPageObject()
	{
		$another = new FirstPage($this->session);
		$another->navigate();
		$another->msg->value('Some message');
		$nextPage = $another->clickSubmit();

		$this->assertInstanceOf(SecondPage::getReflection()->name, $nextPage);
	}

}

/**
 * @presenterName Homepage
 */
class SecondPage extends PageObject
{
}

/**
 * @presenterName Homepage
 * @presenterParameters action = another
 *
 * @property-read Element $msg name = message, input
 * @property-read Element $submit name = _submit
 * @method SecondPage clickSubmit()
 */
class FirstPage extends PageObject
{
}
