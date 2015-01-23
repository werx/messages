<?php

namespace werx\Messages\Utils;

/**
 * HTML Utilities
 * @package werx\Messages\Utils
 */
class Html
{
	/**
	 * Generate an HTML unordered list from an array.
	 *
	 * @access public
	 * @static
	 * @param mixed $data (default: [])
	 * @param mixed $class (default: null) css class to apply to the <ul> element.
	 * @return string Formatted HTML
	 */
	public static function ul($data = [], $class = null)
	{
		$out = [];

		if (is_string($data)) {
			// Make it an array.
			$data = (array)$data;
		}

		if (!empty($data)) {
			if (!empty($class)) {
				$out[] = sprintf('<ul class="%s">', $class);
			} else {
				$out[] = '<ul>';
			}

			foreach ($data as $item) {
				$out[] = sprintf('<li>%s</li>', $item);
			}

			$out[] = '</ul>';
		}

		return join("\n", $out);
	}
}
