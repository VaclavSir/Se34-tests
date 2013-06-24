<?php

namespace Tests\Se34;

use Se34;
use Se34\Element;
use Tests\RealApplicationTestCase;

/**
 * @author Václav Šír
 */
class PageObject_Test extends RealApplicationTestCase
{

	public function testNavigatePresenter()
	{
		$loginPage = new Homepage_another($this->session);
		$loginPage->navigate();
		$this->session->waitForDocument();
		$this->assertPresenter('Homepage', 'action=another');
	}

	public function testNavigateUrl()
	{
		$facebook = new FacebookHomepage($this->session);
		$facebook->navigate();
		$this->session->waitForDocument();
		$this->assertSame('https://www.facebook.com/', $this->session->url());
	}

	public function testCheckStatePresenter()
	{
		$loginPage = new Homepage_another($this->session);
		$loginPage->navigate();
		$this->session->waitForDocument();
		$loginPage->checkState();

		$this->session->url('http://www.example.com/');
		$this->setExpectedException('Se34\ViewStateException');
		$loginPage->checkState();
	}

	public function testCheckStateUrl()
	{
		$facebook = new FacebookHomepage($this->session);
		$facebook->navigate();
		$this->session->waitForDocument();
		$facebook->checkState();

		$this->session->url('http://www.example.com/');
		$this->setExpectedException('Se34\ViewStateException');
		$facebook->checkState();
	}

	public function testGetElementByShortcut()
	{
		$page = new Homepage_another($this->session);
		$page->navigate();
		$messageBox = $page->messageBox;
		$this->assertTagName('input', $messageBox);
		$this->assertTagAttributes('type=text, name=message', $messageBox);

		try
		{
			$page->wrongTagName;
			$this->fail('ViewStateException expected');
		}
		catch (Se34\ViewStateException $e)
		{
			;
		}

		try
		{
			$page->wrongAttrib;
			$this->fail('ViewStateException expected');
		}
		catch (Se34\ViewStateException $e)
		{
			;
		}

		$this->assertSame('I\'m not an element.', $page->notAnElement);
	}

	public function testIsset()
	{
		$page = new Homepage_another($this->session);
		$this->assertTrue(isset($page->messageBox));
		$this->assertTrue(isset($page->wrongTagName));
		$this->assertTrue(isset($page->wrongAttrib));
	}

	public function testMagicMethods()
	{
		$page = new Homepage_another($this->session);
		$page->navigate();
		$nextPage = $page->clickMessageBox();
		$this->assertSame($page, $nextPage);
		$this->assertElementEquals($page->messageBox, $this->session->activeElement);

		try
		{
			$page->valueMessageBox('foo');
			$this->fail('Se34\ViewStateException expected.');
		}
		catch (Se34\ViewStateException $e)
		{
			;
		}

		try
		{
			$page->textMessageBox();
			$this->fail('UnexpectedValueException expected.');
		}
		catch (\UnexpectedValueException $e)
		{
			;
		}

		// Výběr z různých návratových typů
		$nextPage = $page->clickTitle();
		$this->assertSame($page, $nextPage);
	}

	public function testMagicFillMethod()
	{
		$page = new Homepage_another($this->session);
		$page->navigate();
		$this->assertSame($page, $page->fillMessageBox('něco'), 'fluent interface');
		$this->assertSame('něco', $page->messageBox->value());
	}

	public function testNonmagicFillMethod()
	{
		$page = new Homepage_another($this->session);
		$page->navigate();
		$returnValue = $page->fill(array(
			'msg' => 'zpráva',
		));
		$this->assertSame($page, $returnValue);
		$this->assertSame('zpráva', $page->messageBox->value());
	}

	public function testAnnotationsInheritance()
	{
		$descendant = new Homepage_another_Descendant($this->session);
		$descendant->navigate();
		$descendant->clickMessageBox();
		$this->assertElementEquals($this->session->activeElement, $descendant->messageBox);
	}

	public function testGetArrayOfElementsByShortcut()
	{
		$page = new Homepage_another($this->session);
		$page->navigate();
		$inputs = $page->allInputs;
		$this->assertInternalType('array', $inputs);
		$this->assertInstanceOf('Se34\Element', $inputs[0]);

		$this->setExpectedException('Se34\ViewStateException');
		$page->allInputsWrongTagName;
	}

}

/**
 * Fixture pro test PageObject_Test.
 */
class FacebookHomepage extends Se34\PageObject
{

	protected $url = 'https://www.facebook.com/';

}

/**
 * PageObject_Test fixture.
 *
 * @property-read \Se34\Element $messageBox name = message, input, [type=text] # Comment
 * @property-read \Se34\Element $title tag name = h1, h1
 * @property-read \Se34\Element $wrongTagName name = message, wrongTagName
 * @property-read \Se34\Element $wrongAttrib name = message, input, [type=wrongValue]
 * @property-read string $notAnElement Samozřejmě lze stále používat klasické Nette\Object properties.
 * @method Homepage_another clickMessageBox() Klikne na prvek messageBox.
 * @method FacebookHomepage valueMessageBox($value) Vyplní prvek message, ale potom failne, protože má špatný return type.
 * @method \stdClass textMessageBox()
 * @property-read Element $password name = password
 * @method FacebookHomepage|Homepage_another clickTitle() Klikne na title a vrátí this.
 * @property-read \Se34\Element[] $allInputs xpath='//input', input
 * @property-read \Se34\Element[] $allInputsWrongTagName xpath='//input', wrongTag
 */
class Homepage_another extends Se34\PageObject
{

	protected $presenterName = 'Homepage';
	protected $parameters = 'action = another';

	public function getNotAnElement()
	{
		return 'I\'m not an element.';
	}

	public function fillMsg($pw)
	{
		return $this->fillMessageBox($pw);
	}

}

class Homepage_another_Descendant extends Homepage_another
{

}
