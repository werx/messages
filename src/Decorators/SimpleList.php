<?php

namespace werx\Messages\Decorators;

use werx\Messages\Utils\Html;
use werx\Messages\Enums\MessageType;

class SimpleList implements DecoratorInterface
{
	/**
	 * @inheritdoc
	 */
	public static function decorate($data = [], $type = MessageType::INFO)
	{
		switch ($type) {
			case MessageType::ERROR:
				$class = 'error';
				break;
			case MessageType::SUCCESS:
				$class = 'success';
				break;
			default:
				$class = 'info';
				break;
		}

		return Html::ul($data, $class);
	}
}
