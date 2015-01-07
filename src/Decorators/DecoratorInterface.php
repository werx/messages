<?php

namespace werx\Messages\Decorators;

interface DecoratorInterface
{
	/**
	 * @param array $data
	 * @param string $type
	 * @return mixed
	 */
	public static function decorate($data = [], $type = Messages::MESSAGE_TYPE_INFO);
}
