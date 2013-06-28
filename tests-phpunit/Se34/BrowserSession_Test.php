<?php

namespace Tests\Se34;

use Nette\Http\Url;
use Tests\RealApplicationTestCase;

/**
 * @author Václav Šír
 */
class BrowserSession_Test extends RealApplicationTestCase
{

	public function testGetLink()
	{
		$expectedUrl = new Url($this->context->parameters['selenium']['baseUrl']);
		$expectedUrl->query = NULL;
		$this->assertSame($expectedUrl->absoluteUrl, $this->session->getLink('Homepage'));
	}

	public function testGetAppRequest()
	{
		$this->session->navigate('Homepage', 'action=another');
		$appRequest = $this->session->getAppRequest();
		$this->assertSame('Homepage', $appRequest->presenterName);
		$this->assertSame('another', $appRequest->parameters['action']);
	}

	public function testCheckForBluescreen()
	{
		$this->setExpectedException('Se34\BluescreenException', 'Page not found.');
		$this->session->navigate('Homepage', 'action=fuck');
	}

	public function testWaitForAlert()
	{
		$this->session->navigate('Homepage', 'action=alert');
		$alertText = $this->session->waitForAlert();
		$this->assertSame('Hello World', $alertText);
		$this->session->dismissAlert();
	}

	public function testWaitForCondition()
	{
		$this->session->navigate('Homepage', 'action=another');
		$this->session->byName('message')->value('Hello World.');
		$this->session->byName('_submit')->click();
		$this->session->waitForDocument();
		$this->assertPresenter('Homepage', 'action=default');
	}

	public function testElements()
	{
		$this->session->url($this->session->getLink('Homepage', 'action=another'));
		$messageInput = $this->session->byName('message');
		$this->assertInstanceOf('Se34\Element', $messageInput);

		$allInputs = $this->session->elements($this->session->using('css selector')->value('input'));
		$this->assertCount(3, $allInputs);
		foreach ($allInputs as $element)
		{
			$this->assertInstanceOf('Se34\Element', $element);
		}

		$form = $this->session->byCssSelector('form');
		$this->assertInstanceOf('Se34\Element', $form->element($this->session->using('name')->value('message')));
		foreach ($form->elements($this->session->using('css selector')->value('input')) as $element)
		{
			$this->assertInstanceOf('Se34\Element', $element);
		}
	}

	public function testGetActiveElement()
	{
		$this->session->navigate('Homepage', 'action=another');
		$inputBox = $this->session->byName('message');
		$inputBox->click();
		$this->assertElementEquals($inputBox, $this->session->activeElement);
	}

	public function testGetAppRequestForSomeOtherUrl()
	{
		$url = $this->session->getLink('Guestbook');
		$this->assertSame('Guestbook', $this->session->getAppRequest($url)->presenterName);
	}

	public function testGetUrlScript()
	{
		$this->session->url($this->session->getLink('Homepage'));
		$urlScript = $this->session->urlScript;
		$baseUrlParts = parse_url($this->context->parameters['selenium']['baseUrl']);
		$this->assertSame($baseUrlParts['host'], $urlScript->host);
		$this->assertSame($baseUrlParts['path'], $urlScript->path);
		$this->assertSame($baseUrlParts['path'], $urlScript->scriptPath);
	}

	public function testDoubleClick()
	{
		$this->session->navigate('Homepage', 'action=another');
		$this->session->moveto($this->session->byId('leButton'));
		$this->session->doubleclick();
		$this->assertTagAttributes(array('value' => 'Doubleclicked.'), $this->session->byId('leButton'));
	}

}
