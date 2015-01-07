<?php

namespace werx\Messages\Tests;

use werx\Messages\Decorators as Decorators;
use werx\Messages\Enums\MessageType;

class SimpleListTests extends \PHPUnit_Framework_TestCase
{
	public function testDecorateInfoReturnsExpectedString()
	{
		$data = ['Message One', 'Message Two'];
		$result = Decorators\SimpleList::decorate($data, MessageType::INFO);

		$this->assertRegExp('/\<ul class="info">/', $result);
		$this->assertRegExp('/\<li>Message One\<\/li\>/', $result);
		$this->assertRegExp('/\<li>Message Two\<\/li\>/', $result);
	}


	public function testDecorateErrorReturnsExpectedString()
	{
		$data = ['Message One', 'Message Two'];
		$result = Decorators\SimpleList::decorate($data, MessageType::ERROR);

		$this->assertRegExp('/\<ul class="error">/', $result);
		$this->assertRegExp('/\<li>Message One\<\/li\>/', $result);
		$this->assertRegExp('/\<li>Message Two\<\/li\>/', $result);
	}


	public function testDecorateSuccessReturnsExpectedString()
	{
		$data = ['Message One', 'Message Two'];
		$result = Decorators\SimpleList::decorate($data, MessageType::SUCCESS);

		$this->assertRegExp('/\<ul class="success">/', $result);
		$this->assertRegExp('/\<li>Message One\<\/li\>/', $result);
		$this->assertRegExp('/\<li>Message Two\<\/li\>/', $result);
	}

	public function testDecoratorCastsStringToArray()
	{
		$data = 'Message One';

		$result = Decorators\SimpleList::decorate($data);

		$this->assertRegExp('/\<ul class="info">/', $result);
		$this->assertRegExp('/\<li>Message One\<\/li\>/', $result);
	}

	public function testDecoratorReturnsEmptyStringEmptyData()
	{
		$data = [];

		$result = Decorators\SimpleList::decorate($data);

		$this->assertEquals('', $result);
	}
}
