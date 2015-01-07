<?php

namespace werx\Messages\Decorators;

use werx\Messages\Utils\Html;
use werx\Messages\Enums\MessageType;

class Bootstrap implements DecoratorInterface
{
	/**
	 * @inheritdoc
	 */
	public static function decorate($data = null, $type = MessageType::INFO)
	{
		if (is_string($data)) {
			// Make it an array.
			$data = (array)$data;
		}

		switch ($type) {
			case MessageType::ERROR:
				$class = 'alert alert-danger';
				break;
			case MessageType::SUCCESS:
				$class = 'alert alert-success';
				break;
			default:
				$class = 'alert alert-info';
				break;
		}

		if (count($data) > 0) {
			$html = [];
			$html[] = sprintf('<div class="%s">', $class);
			$html[] = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
			$html[] = Html::ul($data);
			$html[] = '</div>';

			return join("\n", $html);
		} else {
			return '';
		}
	}
}
