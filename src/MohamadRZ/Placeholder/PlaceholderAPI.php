<?php

declare(strict_types=1);

namespace MohamadRZ\Placeholder;

use pocketmine\Player;

class PlaceholderAPI {

	/**
	 * Get the value of a placeholder.
	 *
	 * @param string $identifier
	 * @param array $params
	 * @return string|null
	 */
	public static function get(string $identifier, array $params = []): ?string {
		return PlaceholderManager::getPlaceholderValue($identifier, $params);
	}


	/**
	 * Replace placeholders in a given string.
	 *
	 * @param string $text
	 * @param array $params
	 * @return string
	 */
	public static function replace(string $text, ?Player $player = null, array $additionalParams = []): string {
		$params = $additionalParams;
		if ($player !== null) {
			$params['player'] = $player;
		}
		return preg_replace_callback('/%([a-zA-Z0-9_]+)%/', function($matches) use ($params) {
			$placeholder = $matches[1];
			$value = self::get($placeholder, $params);
			return $value !== null ? $value : $matches[0];
		}, $text);
	}

}
