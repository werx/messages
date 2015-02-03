<?php
namespace werx\Messages\Tests;

use werx\Messages\Decorators;
use werx\Messages\Messages;
use Symfony\Component\HttpFoundation\Session as Session;

class MessagesTests extends \PHPUnit_Framework_TestCase
{
	public $session = null;
	
	public function __construct()
	{
		$this->session = new Session\Session(new Session\Storage\MockArraySessionStorage);
	}

	public function setup()
	{
		//$messages = new Messages($this->session);
		Messages::getInstance($this->session);
	}

	public function teardown()
	{
		Messages::destroy();
	}

	public function testNullSessionInterfaceShouldUseDefault()
	{
		Messages::destroy();
		$session = Messages::getInstance()->session;
		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Session\Session', $session);
	}

	public function testCanAddError()
	{
		Messages::error('Foo');

		$all = Messages::all();

		$this->assertArrayHasKey('error', $all);
		$this->assertEquals('Foo', $all['error'][0]);
	}

	public function testCanAddInfo()
	{
		Messages::info('Foo');

		$all = Messages::all();

		$this->assertArrayHasKey('info', $all);
		$this->assertEquals('Foo', $all['info'][0]);
	}

	public function testCanAddSuccess()
	{
		Messages::success('Foo');

		$all = Messages::all();

		$this->assertArrayHasKey('success', $all);
		$this->assertEquals('Foo', $all['success'][0]);
	}

	public function testCanAddWarning()
	{
		Messages::warning('Foo');

		$all = Messages::all();

		$this->assertArrayHasKey('warning', $all);
		$this->assertEquals('Foo', $all['warning'][0]);
	}

	public function testCanAddArrayOfMessages()
	{
		Messages::success( ['Foo', 'Bar'] );

		$all = Messages::all();

		$this->assertArrayHasKey('success', $all);
		$this->assertEquals('Foo', $all['success'][0]);
		$this->assertEquals('Bar', $all['success'][1]);
	}

	public function testCanAddArrayOfMessagesDifferentTypes()
	{
		Messages::success( ['Foo', 'Bar'] );
		Messages::error( ['Baz', 'Qux'] );

		$all = Messages::all();

		$this->assertArrayHasKey('success', $all);
		$this->assertEquals('Foo', $all['success'][0]);
		$this->assertEquals('Bar', $all['success'][1]);

		$this->assertArrayHasKey('error', $all);
		$this->assertEquals('Baz', $all['error'][0]);
		$this->assertEquals('Qux', $all['error'][1]);
	}

	public function testNoMessagesReturnsEmptyArray()
	{
		$all = Messages::all();

		$this->assertInternalType('array', $all);
		$this->assertCount(0, $all);
	}

	public function testCanDeleteMessages()
	{
		Messages::error('error');
		Messages::info('info');
		Messages::success('success');
		Messages::success('warning');

		$messages = Messages::all(false);

		// We should have messages.
		$this->assertFalse(empty($messages));

		// After calling delete, messages should be empty array.
		Messages::delete();
		$messages = Messages::all(false);

		$this->assertTrue(empty($messages));
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Messages not initialized
	 */
	public function testUnitializedObjectShouldThrowException()
	{
		Messages::destroy();

		$all = Messages::all();
	}

	public function testCanFormatArray()
	{
		$string = 'The %s brown %s jumped over the log';
		$data = ['quick', 'fox'];

		$this->assertEquals('The quick brown fox jumped over the log', Messages::format($string, $data));
	}

	public function testCanFormatString()
	{
		$string = 'The quick brown %s jumped over the log';
		$data = 'fox';

		$this->assertEquals('The quick brown fox jumped over the log', Messages::format($string, $data));
	}

	public function testCanFormatInt()
	{
		$string = 'The quick brown fox jumped over %s logs';
		$data = 3;

		$this->assertEquals('The quick brown fox jumped over 3 logs', Messages::format($string, $data));
	}

	public function testCanFormatFloat()
	{
		$string = 'The quick brown fox jumped over %s logs';
		$data = 3.5;

		$this->assertEquals('The quick brown fox jumped over 3.5 logs', Messages::format($string, $data));
	}

	public function testCanFormatObject()
	{
		$string = 'The quick brown %s jumped over the log';
		$data = (object) ['fox'];

		$this->assertEquals('The quick brown fox jumped over the log', Messages::format($string, $data));
	}

	public function testFormatEmptyPlaceholderShouldReturnOriginalString()
	{
		$string = 'The quick brown fox jumped over the log';

		$this->assertEquals($string, Messages::format($string));
	}

	public function testDisplayNoMessagesShouldReturnEmptyString()
	{
		$result = Messages::display();

		$this->assertEquals('', $result);
	}

	public function testDisplayNoMessagesReturnsNull()
	{
		$result = Messages::display();

		$this->assertNull($result);
	}

	public function testSetNullDecoratorDefaultsToSimpleList()
	{
		Messages::setDecorator();

		$decorator = Messages::getInstance()->decorator;

		$this->assertInstanceOf('\werx\Messages\Decorators\SimpleList', $decorator);
	}

	public function testNoDecoratorSpecifiedDefaultsToSimpleList()
	{
		$decorator = Messages::getInstance()->decorator;

		$this->assertInstanceOf('\werx\Messages\Decorators\SimpleList', $decorator);
	}

	public function testSetBootstrapDecoratorReturnsCorrectClass()
	{
		Messages::setDecorator(new Decorators\Bootstrap());

		$decorator = Messages::getInstance()->decorator;

		$this->assertInstanceOf('\werx\Messages\Decorators\Bootstrap', $decorator);
	}

	public function testSetSimpleListDecoratorReturnsCorrectClass()
	{
		Messages::setDecorator(new Decorators\SimpleList);

		$decorator = Messages::getInstance()->decorator;

		$this->assertInstanceOf('\werx\Messages\Decorators\SimpleList', $decorator);
	}

	public function testDisplayErrorsReturnsExpectedString()
	{
		Messages::error('Message One');
		$result = Messages::display();

		$this->assertRegExp('/\<ul class="error">/', $result);
		$this->assertRegExp('/\<li>Message One\<\/li\>/', $result);
	}

	public function testDisplayInfoReturnsExpectedString()
	{
		Messages::info('Message One');
		$result = Messages::display();

		$this->assertRegExp('/\<ul class="info">/', $result);
		$this->assertRegExp('/\<li>Message One\<\/li\>/', $result);
	}

	public function testDisplaySuccessReturnsExpectedString()
	{
		Messages::success('Message One');
		$result = Messages::display();

		$this->assertRegExp('/\<ul class="success">/', $result);
		$this->assertRegExp('/\<li>Message One\<\/li\>/', $result);
	}
}
