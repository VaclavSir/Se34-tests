<?php

namespace Tests\Se34;

use Se34\Utils;

/** @sampleAnnotation foo
 * @author Václav Šír
 * @sampleAnnotation bar
 * @sampleAnnotation john.doe@example.com
 */
class Utils_Test extends \PHPUnit_Framework_TestCase
{

	public function testStrToArray()
	{
		$this->assertSame(array('a' => 'b', 'asdf', 'bflm', array('foo' => 'bar')), Utils::strToArray('a=b, asdf, bflm, [foo = bar]'));
	}

	/**
	 * @return NULL|foo|bar Description
	 */
	public function testGetReturnTypes()
	{
		$this->assertSame(array('NULL', 'foo', 'bar'), Utils::getReturnTypes(__CLASS__, __FUNCTION__));
	}

	public function testGetClassAnnotations()
	{
		$annotations = Utils::getClassAnnotations($this);
		$this->assertSame(array('Václav Šír'), $annotations['author']);
		$this->assertSame(array('foo', 'bar', 'john.doe@example.com'), $annotations['sampleAnnotation']);
	}

}
