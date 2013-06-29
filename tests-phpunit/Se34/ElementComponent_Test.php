<?php

namespace Tests\Se34;

use Se34\Element;
use Se34\ElementComponent;
use Se34\IPageComponent;
use Tests\RealApplicationTestCase;

class ElementComponent_Test extends RealApplicationTestCase
{

	/** @var TestComponent */
	private $component;
	/** @var IPageComponent|\PHPUnit_Framework_MockObject_MockObject */
	private $parent;

	protected function setUp()
	{
		parent::setUp();
		$this->parent = $this->getMock('Se34\IPageComponent', array(), array(), '', FALSE);
		$session = $this->getMock('Se34\BrowserSession', array(), array(), '', FALSE);
		$this->component = new TestComponent($session, $this->parent, array('strategy' => 'value', 'tag', array('e' => 'mc2')));
	}

	public function testElementsAreSearchedInScopeOfElementFromParameters()
	{
		$componentRoot = $this->getMock('Se34\Element', array(), array(), '', FALSE);
		$this->parent->expects($this->once())
			->method('findElement')
			->with('strategy', 'value')
			->will($this->returnValue($componentRoot));
		$componentRoot->expects($this->once())
			->method('findElement')
			->with('foo', 'bar')
			->will($this->returnValue('searched element'));
		$componentRoot->expects($this->once())
			->method('findElements')
			->with('strategy', 'value')
			->will($this->returnValue('searched elements'));

		$this->assertSame('searched element', $this->component->findElement('foo', 'bar'));
		$this->assertSame('searched elements', $this->component->findElements('strategy', 'value'));
	}

	public function testShortcutFromPropertyReadAnnotationsIsAccessible()
	{
		$componentRoot = $this->getMock('Se34\Element', array(), array(), '', FALSE);
		$this->parent->expects($this->once())
			->method('findElement')
			->with('strategy', 'value')
			->will($this->returnValue($componentRoot));
		$shortcut = $this->getMock('Se34\Element', array('name', 'attribute'), array(), '', FALSE);
		$componentRoot->expects($this->once())
			->method('findElement')
			->with('name', 'foo')
			->will($this->returnValue($shortcut));
		$shortcut->expects($this->once())
			->method('name')
			->will($this->returnValue('tagName'));
		$shortcut->expects($this->once())
			->method('attribute')
			->with('param to check')
			->will($this->returnValue('expected value'));

		$this->assertSame($shortcut, $this->component->shortcut);
	}

	public function testTagNameAndAttributesOfTheRootElementsAreChecked()
	{
		$componentRoot = $this->getMock('Se34\Element', array('name', 'attribute', 'findElement'), array(), '', FALSE);
		$this->parent->expects($this->once())
			->method('findElement')
			->with('strategy', 'value')
			->will($this->returnValue($componentRoot));
		$componentRoot->expects($this->once())
			->method('name')
			->will($this->returnValue('tag'));
		$componentRoot->expects($this->once())
			->method('attribute')
			->with('e')
			->will($this->returnValue('mc2'));
		$componentRoot->expects($this->once())
			->method('findElement')
			->with('foo', 'bar');

		$this->component->findElement('foo', 'bar');
	}

}

/**
 * @property-read Element $shortcut name=foo, tagName, [ param to check = expected value ] # comment
 */
class TestComponent extends ElementComponent
{
}
