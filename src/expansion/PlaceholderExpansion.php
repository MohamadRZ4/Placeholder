<?php

namespace MohamadRZ4\Placeholder\expansion;

use pocketmine\player\Player;

abstract class PlaceholderExpansion
{
	abstract public function getIdentifier(): string;
	abstract public function getAuthor(): string;
	abstract public function getVersion(): string;

	abstract public function onPlaceholderRequest(?Player $player, string $params): ?string;
}