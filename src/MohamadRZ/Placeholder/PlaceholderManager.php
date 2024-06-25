<?php

declare(strict_types=1);

namespace MohamadRZ\Placeholder;

class PlaceholderManager {

	private static $placeholders = [];

	public static function init(): void {
		self::$placeholders = [];
	}

	/**
	 * Add a placeholder.
	 *
	 * @param string $identifier
	 * @param callable $callback
	 */
	public static function addPlaceholder(string $identifier, callable $callback): void {
		self::$placeholders[$identifier] = $callback;
	}

	/**
	 * Get the value of a placeholder.
	 *
	 * @param string $identifier
	 * @param array $params
	 * @return string|null
	 */
	public static function getPlaceholderValue(string $identifier, array $params = []): ?string {
		if (isset(self::$placeholders[$identifier])) {
			$callback = self::$placeholders[$identifier];
			return call_user_func($callback, $params);
		}
		return null;
	}


}
