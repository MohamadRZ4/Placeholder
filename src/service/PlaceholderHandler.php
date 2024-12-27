<?php

declare(strict_types=1);

namespace MohamadRZ4\Placeholder\service;

use MohamadRZ4\Placeholder\config\ConfigManager;
use MohamadRZ4\Placeholder\expansion\PlaceholderExpansion;
use MohamadRZ4\Placeholder\PlaceholderAPI;
use pocketmine\player\Player;

class PlaceholderHandler
{
	private array $patterns = [
		"/%(\w+)%/",   // %%
		"/\[(\w+)\]/", // []
		"/\{(\w+)\}/", // {}
		"/\$(\w+)\$/", // $$
	];

	private array $placeholders = [];
	private ConfigManager $configManager;
	public function __construct(ConfigManager $configManager)
	{
		$this->configManager = $configManager;
	}

	public function replacePlaceholders(?Player $player, string $text, PlaceholderExpansion $expansion): string
	{
		foreach ($this->patterns as $pattern) {
			if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
				foreach ($matches as $match) {
					$params = $match[1];
					$placeholder = $match[0];

					if (isset($this->placeholders[$placeholder])) {
						$value = $this->placeholders[$placeholder];
					} else {
						$value = $expansion->onPlaceholderRequest($player, $params);
						if ($value !== null) {
							$this->placeholders[$placeholder] = $value;
						}
					}

					if ($value !== null) {
						$text = str_replace($placeholder, $value, $text);
					}
				}
			}
		}

		return $text;
	}

	public function get(string $placeholder): ?string
	{
		return $this->placeholders[$placeholder] ?? null;
	}

	public function exist(string $placeholder): bool
	{
		return isset($this->placeholders[$placeholder]);
	}

	public function unregister(string $placeholder): void
	{
		unset($this->placeholders[$placeholder]);
	}
}
