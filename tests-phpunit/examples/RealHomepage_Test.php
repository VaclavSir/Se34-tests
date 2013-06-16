<?php

namespace Tests;

/**
 * This test access the real homepage and verifies some printed values there.
 */
class RealHomepage_Test extends RealApplicationTestCase
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
