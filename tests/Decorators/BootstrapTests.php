<?php

namespace werx\Messages\Tests;

use werx\Messages\Decorators as Decorators;
use werx\Messages\Enums\MessageType;

class BootstrapTests extends \PHPUnit_Framework_TestCase
{
	public function testDecorateInfoReturnsExpectedString()
	{
		$data = ['Message One', 'Message Two'];
		$result = Decorators\Bootstrap::decorate($data, MessageType::INFO);

		$this->assertRegExp('/alert-info/i', $result);
		$this->assertRegExp('/message one/i', $result);
		$this->assertRegExp('/message two/i', $result);
	}


	public function testDecorateErrorReturnsExpectedString()
	{
		$data = ['Message One', 'Message Two'];
		$result = Decorators\Bootstrap::decorate($data, MessageType::ERROR);

		$this->assertRegExp('/alert-danger/i', $result);
		$this->assertRegExp('/message one/i', $result);
		$this->assertRegExp('/message two/i', $result);
	}


	public function testDecorateSuccessReturnsExpectedString()
	{
		$data = ['Message One', 'Message Two'];
		$result = Decorators\Bootstrap::decorate($data, MessageType::SUCCESS);

		$this->assertRegExp('/alert-success/i', $result);
		$this->assertRegExp('/message one/i', $result);
		$this->assertRegExp('/message two/i', $result);
	}

	public function testDecoratorCastsStringToArray()
	{
		$data = 'Foo';

		$result = Decorators\Bootstrap::decorate($data);

		$this->assertRegExp('/foo/i', $result);
		$this->assertRegExp('/alert-info/i', $result);
	}

	public function testDecoratorReturnsEmptyStringEmptyData()
	{
		$data = [];

		$result = Decorators\Bootstrap::decorate($data);

		$this->assertEquals('', $result);
	}
}
