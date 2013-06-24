<?php

namespace Tests\Se34;

use Tests\RealApplicationTestCase;

/**
 * @author Václav Šír
 */
class TestCase_Test extends RealApplicationTestCase
{
	public function testAssertPresenter()
	{
		$this->session->navigate('Homepage');

		$this->assertPresenter('Homepage', 'action=default');

		$this->setExpectedException('PHPUnit_Framework_AssertionFailedError');
		$this->assertPresenter('Asdf');
	}

	public function testAssertTagName()
	{
		$this->session->navigate('Homepage');
		$element = $this->session->byXPath('//*[text()="Congratulations!"]');

		$this->assertTagName('h1', $element);

		$this->setExpectedException('PHPUnit_Framework_AssertionFailedError');
		$this->assertTagName('div', $element);
	}

	public function testAssertTagAttributes()
	{
		$this->session->navigate('Homepage');
		$element = $this->session->byId('content');

		$this->assertTagAttributes('id=content', $element);

		$this->setExpectedException('PHPUnit_Framework_AssertionFailedError');
		$this->assertTagAttributes('foo=bar', $element);
	}

	public function testAssertElementEquals()
	{
		$this->session->navigate('Homepage');
		$element = $this->session->byTag('h1');
		$sameElement = $this->session->byXPath('//h1');
		$this->assertElementEquals($element, $sameElement);

		$anotherElement = $this->session->byTag('h2');
		$this->setExpectedException('PHPUnit_Framework_AssertionFailedError');
		$this->assertElementEquals($element, $anotherElement);
	}

}
