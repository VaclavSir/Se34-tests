<?php

namespace Tests;

class FakeRootHomepage_Test extends FakeRootTestCase
{

	public function testHomepageCongratulatesToInstallingNetteFramework()
	{
		$this->session->navigate('Homepage');
		$mainTitle = $this->session->byTag('h1');
		$subTitle = $this->session->byTag('h2');

		$this->assertSame('Congratulations!', $mainTitle->text());
		$this->assertSame('You have successfully created your Nette Framework project.', $subTitle->text());
	}

}
