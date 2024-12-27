<?php

namespace MohamadRZ4\Placeholder\expansion;

use pocketmine\player\Player;

abstract class RelationalPlaceholderExpansion extends PlaceholderExpansion
{
	/**
	 * Handle placeholders with two players as input.
	 *
	 * @param Player|null $one The first player.
	 * @param Player|null $two The second player.
	 * @param string $params The placeholder parameters.
	 * @return string|null The processed placeholder result.
	 */
	abstract public function onRelationalPlaceholderRequest(?Player $one, ?Player $two, string $params): ?string;
}
