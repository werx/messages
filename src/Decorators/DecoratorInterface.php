<?php

namespace werx\Messages\Decorators;

use werx\Messages\Enums\MessageType;

interface DecoratorInterface
{
	/**
	 * @param array $data
	 * @param string $type
	 * @return mixed
	 */
	public static function decorate($data = [], $type = MessageType::INFO);
}
